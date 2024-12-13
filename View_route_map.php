<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once './classes/DbConnector.php';

use classes\DbConnector;

try {
    $dbConnector = new DbConnector();
    $dbh = $dbConnector->getConnection();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}

$ride_id = isset($_GET['ride_id']) ? intval($_GET['ride_id']) : 0;

if ($ride_id <= 0) {
    echo "Invalid Ride ID.";
    exit;
}

// Fetch ride details from the database
$query = $dbh->prepare("SELECT start_place, end_place FROM ride_requests WHERE ride_id = ?");
$query->execute([$ride_id]);
$ride = $query->fetch(PDO::FETCH_ASSOC);

if (!$ride) {
    echo "Ride not found.";
    exit;
}

$start_place = $ride['start_place'];
$end_place = $ride['end_place'];

// Fetch all pickup points and associated passenger names
$queryPickupPoints = $dbh->prepare("SELECT passenger_name, pickup_point FROM ride_requests WHERE ride_id = ?");
$queryPickupPoints->execute([$ride_id]);
$pickup_points = $queryPickupPoints->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta charset="UTF-8">
    <title>Ride Map</title>
    <style>
        #map {
            height: 100vh;
            width: 100%;
        }
    </style>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBjouAs7Oot1Awblj7d_9TOf6dUrsBo2ow&callback=initMap" async defer></script>
    <script>
        function initMap() {
            var start = '<?= $start_place ?>';
            var end = '<?= $end_place ?>';
            var pickupPoints = <?= json_encode($pickup_points) ?>;

            // Debugging
            console.log('Start Place:', start);
            console.log('End Place:', end);
            console.log('Pickup Points:', pickupPoints);

            var map = new google.maps.Map(document.getElementById('map'), {
                center: { lat: 0, lng: 0 }, // Default center
                zoom: 8
            });

            var directionsService = new google.maps.DirectionsService();
            var directionsRenderer = new google.maps.DirectionsRenderer();
            directionsRenderer.setMap(map);

            // Initialize the Geocoder
            var geocoder = new google.maps.Geocoder();
            var bounds = new google.maps.LatLngBounds();

            // Function to geocode addresses and add markers
            pickupPoints.forEach(function(point) {
                geocoder.geocode({ 'address': point['pickup_point'] }, function(results, status) {
                    if (status === 'OK') {
                        var location = results[0].geometry.location;
                        var marker = new google.maps.Marker({
                            position: location,
                            map: map,
                            title: point['passenger_name'] + "'s Pickup Point"
                        });

                        // Add an info window to the marker
                        var infoWindow = new google.maps.InfoWindow({
                            content: point['passenger_name'] + "'s Pickup Point"
                        });
                        marker.addListener('click', function() {
                            infoWindow.open(map, marker);
                        });

                        // Extend bounds to include the marker
                        bounds.extend(location);

                        // Adjust map center to fit all markers
                        map.fitBounds(bounds);
                    } else {
                        console.error('Geocode was not successful for the following reason: ' + status);
                    }
                });
            });

            // Define the route request
            var waypoints = pickupPoints.map(function(point) {
                return { location: point['pickup_point'], stopover: true };
            });

            var request = {
                origin: start,
                destination: end,
                waypoints: waypoints,
                travelMode: 'DRIVING'
            };

            directionsService.route(request, function(result, status) {
                if (status === 'OK') {
                    directionsRenderer.setDirections(result);
                } else {
                    alert('Directions request failed due to ' + status);
                }
            });
        }
    </script>
</head>
<body>
    <h4>Ride Details</h4>
    <h5><b> Start Place: </b><?php  echo $start_place ;?></h5>
    
    <h5><b> End Place: </b><?php echo $end_place;  
            ?></h5>
    <div id="map"></div>
</body>
</html>
