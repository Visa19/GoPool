<?php
session_start();
error_reporting(E_ALL); // Change to E_ALL for debugging
require_once './classes/DbConnector.php';

use classes\DbConnector;

try {
    $dbConnector = new DbConnector();
    $dbh = $dbConnector->getConnection();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}

if (isset($_GET['ride_id'])) {
    $ride_id = $_GET['ride_id'];

    // Update trip status to 'in_progress'
    $query = "UPDATE tblrides SET trip_status='in_progress' WHERE ride_id=:ride_id";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':ride_id', $ride_id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Trip status updated
    } else {
        // Failed to update trip status
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Driver GPS</title>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBjouAs7Oot1Awblj7d_9TOf6dUrsBo2ow"></script>
</head>
<body>
    <h1>Your Cuurent Location</h1>
    <div id="map" style="height: 500px;"></div>

    <script>
        let map;
        let marker;

        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: 9.6615, lng: 80.0255}, // Initial center set to Jaffna
                zoom: 15
            });
            marker = new google.maps.Marker({
                map: map,
                title: 'Driver Live Location'
            });
        }

        function updateLocation(lat, lng) {
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "update_location.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.send("ride_id=<?php echo $ride_id; ?>&lat=" + lat + "&lng=" + lng);
        }

        function success(position) {
            const latitude = position.coords.latitude;
            const longitude = position.coords.longitude;

            console.log('Latitude:', latitude, 'Longitude:', longitude); // Debugging output

            updateLocation(latitude, longitude);
            // Update map with driver's location
            const latLng = new google.maps.LatLng(latitude, longitude);
            marker.setPosition(latLng);
            map.setCenter(latLng);
        }

        function error() {
            console.log('Unable to retrieve your location');
        }

        if (navigator.geolocation) {
            setInterval(() => {
                navigator.geolocation.getCurrentPosition(success, error);
            }, 2000); // Update location every 2 seconds
        } else {
            console.log('Geolocation is not supported by your browser');
        }

        window.onload = initMap;
    </script>
</body>
</html>
