<?php
session_start();
require_once './classes/DbConnector.php';

use classes\DbConnector;

$ride_id = $_GET['ride_id'] ?? null;

if (!$ride_id) {
    echo "No ride ID provided.";
    exit;
}

try {
    $dbConnector = new DbConnector();
    $dbh = $dbConnector->getConnection();

    // Fetch the ride details including driver's live location
    $query = "SELECT postedby, start_lat, start_lng, trip_status FROM tblrides WHERE ride_id=:ride_id";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':ride_id', $ride_id, PDO::PARAM_INT);
    $stmt->execute();
    $ride = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$ride) {
        echo "Ride not found.";
        exit;
    }

    // Check if the session user is the passenger (optional for your logic)
    // $isPassenger = ($_SESSION['fname'] === $ride['passenger_name']);

    // Display driver's live location if trip status is 'in_progress'
    if ($ride['trip_status'] === 'in_progress') {
        $driverName = $ride['postedby'];
        $startLat = $ride['start_lat'];
        $startLng = $ride['start_lng'];
    } else {
        echo "Trip is not in progress.";
        exit;
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Track Driver Live Location</title>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBjouAs7Oot1Awblj7d_9TOf6dUrsBo2ow"></script>
</head>
<body>
    <h1>GoPool Live Tracking</h1>
    <h3>Current Location of Your Driver  <?php echo $driverName; ?></h3>
    <div id="map" style="height: 500px;"></div>

    <script>
        let map;
        let marker;

        function initMap() {
            const initialLat = <?php echo $startLat; ?>;
            const initialLng = <?php echo $startLng; ?>;

            map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: initialLat, lng: initialLng},
                zoom: 15
            });
            marker = new google.maps.Marker({
                map: map,
                position: {lat: initialLat, lng: initialLng},
                title: 'Driver Live Location'
            });

            // Update driver's location every 5 seconds
            setInterval(updateDriverLocation, 5000);
        }

        function updateDriverLocation() {
            // Fetch updated location from server or database
            const xhr = new XMLHttpRequest();
            xhr.open("GET", "update_location.php?ride_id=<?php echo $ride_id; ?>", true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const location = JSON.parse(xhr.responseText);
                    const latLng = new google.maps.LatLng(location.lat, location.lng);
                    marker.setPosition(latLng);
                    map.setCenter(latLng);
                }
            };
            xhr.send();
        }

        window.onload = initMap;
    </script>
</body>
</html>
