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

if (isset($_GET['booking_number'])) {
    $bookingnum = intval($_GET['booking_number']);

    // Delete the ride request record from the database
    $sql = "DELETE FROM tblbooking WHERE BookingNumber = :bookingnum";
    $query = $dbh->prepare($sql);
    $query->bindParam(':bookingnum', $bookingnum, PDO::PARAM_INT);
    $query->execute();

    // Check if the deletion was successful
    if ($query->rowCount() > 0) {
        // Redirect back to the previous page or a confirmation page
        header("Location: my-booking.php");
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
