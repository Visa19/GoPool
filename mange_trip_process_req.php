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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = isset($_POST['status']) ? $_POST['status'] : '';
    $request_id = isset($_POST['request_id']) ? $_POST['request_id'] : null;
    $ride_id = isset($_GET['ride_id']) ? $_GET['ride_id'] : null;
    $capacity = isset($_POST['cap']) ? $_POST['cap'] : null;

    // Debugging received variables
    echo "<p>Status: $status</p>";
    echo "<p>Request ID: $request_id</p>";
    echo "<p>Ride ID: $ride_id</p>";
    echo "<p>Capacity: $capacity</p>";

    if ($status && $request_id && $ride_id && $capacity !== null) {
        // Fetch number of seats from ride_requests table
        $sql = "SELECT number_of_seats FROM ride_requests WHERE ride_request_id = :request_id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':request_id', $request_id, PDO::PARAM_INT);
        $query->execute();
        $req_data = $query->fetch(PDO::FETCH_OBJ);

        if ($req_data) {
            $seats = $req_data->number_of_seats;

            // Update the ride request status
            $sql = "UPDATE ride_requests SET Status = :status WHERE ride_request_id = :request_id";
            $query = $dbh->prepare($sql);
            $query->bindParam(':status', $status, PDO::PARAM_STR);
            $query->bindParam(':request_id', $request_id, PDO::PARAM_INT);
            $query->execute();

            // Check for SQL errors
            if ($query->errorCode() !== PDO::ERR_NONE) {
                $errorInfo = $query->errorInfo();
                echo "<h2 class='text-danger' style='color: red; font-size: 16px;'>Database Error: " . $errorInfo[2] . "</h2>";
                exit;
            }

            // If status is Confirmed, update the capacity in tblrides
            if ($status === 'Confirmed') {
                $new_capacity = $capacity - $seats;

                if ($new_capacity < 0) {
                    echo "<h2 class='text-danger' style='color: red; font-size: 16px;'>Error: Capacity cannot be less than 0.</h2>";
                    exit;
                }

                $sql = "UPDATE tblrides SET capacity = :new_capacity WHERE ride_id = :ride_id";
                $query = $dbh->prepare($sql);
                $query->bindParam(':new_capacity', $new_capacity, PDO::PARAM_INT);
                $query->bindParam(':ride_id', $ride_id, PDO::PARAM_INT);
                $query->execute();

                // Check for SQL errors
                if ($query->errorCode() !== PDO::ERR_NONE) {
                    $errorInfo = $query->errorInfo();
                    echo "<h2 class='text-danger' style='color: red; font-size: 16px;'>Database Error: " . $errorInfo[2] . "</h2>";
                    exit;
                }
            }  if ($status === 'Rejected') {
                $new_capacity = $capacity + $seats;

                if ($new_capacity < 0) {
                    echo "<h2 class='text-danger' style='color: red; font-size: 16px;'>Error: Capacity cannot be less than 0.</h2>";
                    exit;
                }

                $sql = "UPDATE tblrides SET capacity = :new_capacity WHERE ride_id = :ride_id";
                $query = $dbh->prepare($sql);
                $query->bindParam(':new_capacity', $new_capacity, PDO::PARAM_INT);
                $query->bindParam(':ride_id', $ride_id, PDO::PARAM_INT);
                $query->execute();

                // Check for SQL errors
                if ($query->errorCode() !== PDO::ERR_NONE) {
                    $errorInfo = $query->errorInfo();
                    echo "<h2 class='text-danger' style='color: red; font-size: 16px;'>Database Error: " . $errorInfo[2] . "</h2>";
                    exit;
                }
            }
            // Redirect back to Manage Trip page
            header("Location: Manage_trips.php?ride_id=" . $ride_id);
            exit;
        } else {
            echo "<h2 class='text-danger' style='color: red; font-size: 16px;'>No request data found.</h2>";
            exit;
        }
    } else {
        echo "<h2 class='text-danger' style='color: red; font-size: 16px;'>Invalid data provided.</h2>";
        exit;
    }
} else {
    echo "<h2 class='text-danger' style='color: red; font-size: 16px;'>Invalid request method.</h2>";
    exit;
}
?>
