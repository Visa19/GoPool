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

if (isset($_POST['ride_id']) && isset($_POST['lat']) && isset($_POST['lng'])) {
    $ride_id = $_POST['ride_id'];
    $lat = $_POST['lat'];
    $lng = $_POST['lng'];

    $query = "UPDATE tblrides SET start_lat=:lat, start_lng=:lng WHERE ride_id=:ride_id";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':lat', $lat);
    $stmt->bindParam(':lng', $lng);
    $stmt->bindParam(':ride_id', $ride_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        if ($stmt->rowCount() > 0) {
            echo "Location updated successfully";
        } else {
            echo "No rows updated";
        }
    } else {
        echo "Failed to execute query";
    }
}
?>
