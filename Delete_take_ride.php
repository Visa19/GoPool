<?php
session_start();
require_once './classes/DbConnector.php';

use classes\DbConnector;

try {
    $dbConnector = new DbConnector();
    $dbh = $dbConnector->getConnection();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}

if (isset($_GET['request_id'])) {
    $request_id = intval($_GET['request_id']);

    // Delete the ride request record from the database
    $sql = "DELETE FROM ride_requests WHERE ride_request_id = :request_id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':request_id', $request_id, PDO::PARAM_INT);
    $query->execute();

    // Check if the deletion was successful
    if ($query->rowCount() > 0) {
        // Redirect back to the previous page or a confirmation page
        header("Location: Trips.php");
        exit;
    } else {
        // Handle the case where no record was deleted
        echo "<h2 class='text-danger' style='color: red; font-size: 16px;'>Error: No record found to delete.</h2>";
        exit;
    }
} else {
    // Handle the case where request_id is not provided
    echo "<h2 class='text-danger' style='color: red; font-size: 16px;'>Error: Invalid request.</h2>";
    exit;
}
?>
