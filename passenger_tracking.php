<?php
session_start();
error_reporting(0);
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

if ($ride_id) {
    $stmt = $pdo->prepare("SELECT * FROM tblrides WHERE ride_id = ?");
    $stmt->execute([$ride_id]);
    $ride = $stmt->fetch(PDO::FETCH_OBJ);
    
    if ($ride && $ride->trip_status == 'in_progress') {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Passenger Live Tracking</title>
            <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBjouAs7Oot1Awblj7d_9TOf6dUrsBo2ow&libraries=places"></script>
            <script>
                let map;
                let marker;
                let rideId = <?php echo $ride_id; ?>;

                function initMap() {
                    map = new google.maps.Map(document.getElementById('map'), {
                        zoom: 15,
                        center: {lat: 0, lng: 0} // Default location
                    });

                    marker = new google.maps.Marker({
                        map: map,
                        position: {lat: 0, lng: 0}
                    });

                    fetchLocation();
                }

                function fetchLocation() {
                    fetch('update_location.php?ride_id=' + rideId)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const {lat, lng} = data;
                                const position = {lat, lng};
                                map.setCenter(position);
                                marker.setPosition(position);
                            }
                            setTimeout(fetchLocation, 5000); // Update every 5 seconds
                        });
                }

                window.onload = initMap;
            </script>
        </head>
        <body>
            <div id="map" style="height: 100vh; width: 100%;"></div>
        </body>
        </html>
        <?php
    } else {
        echo "Trip is not in progress or invalid ride ID.";
    }
} else {
    echo "No ride ID provided.";
}
?>