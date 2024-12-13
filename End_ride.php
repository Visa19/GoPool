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

    // Update trip status to 'completed'
    $query = "UPDATE tblrides SET trip_status='completed' WHERE ride_id=:ride_id";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':ride_id', $ride_id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
         header('Location: Manage_trips.php?ride_id='.$ride_id);
             exit();
    } else {
        // Failed to update trip status
    }
}
?>