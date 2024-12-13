<?php
session_start();
require_once './classes/DbConnector.php';

use classes\DbConnector;

$ride_id = $_GET['ride_id'] ?? null;

if (!$ride_id) {
    echo json_encode(['error' => 'No ride ID provided.']);
    exit;
}

try {
    $dbConnector = new DbConnector();
    $dbh = $dbConnector->getConnection();

    // Fetch the driver's live location
    $query = "SELECT start_lat, start_lng FROM tblrides WHERE ride_id=:ride_id";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':ride_id', $ride_id, PDO::PARAM_INT);
    $stmt->execute();
    $location = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$location) {
        echo json_encode(['error' => 'Location not found.']);
        exit;
    }

    echo json_encode([
        'lat' => $location['start_lat'],
        'lng' => $location['start_lng']
    ]);

} catch (PDOException $e) {
    echo json_encode(['error' => 'Error: ' . $e->getMessage()]);
    exit;
}
?>
