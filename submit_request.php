<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the DbConnector class
require_once './classes/DbConnector.php';

use classes\DbConnector;

try {
    $dbConnector = new DbConnector();
    $dbh = $dbConnector->getConnection();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_ride'])) {
    // Retrieve and validate form data
    $pickup_point = htmlspecialchars($_POST['pickup_point']);
    $dr_message = $_POST['message'];
    $message = html_entity_decode($dr_message, ENT_QUOTES, 'UTF-8');
    $number_of_seats = isset($_POST['number_of_seats']) ? intval($_POST['number_of_seats']) : 0; // Convert to integer
    $start_place = htmlspecialchars($_POST['start_place']);
    $end_place = htmlspecialchars($_POST['end_place']);
    $date_of_travel = htmlspecialchars($_POST['date_of_travel']);
    $time_of_travel = htmlspecialchars($_POST['time_of_travel']);
    $postedby = htmlspecialchars($_POST['postedby']);
    $rideid = htmlspecialchars($_POST['ride_id']);
    $price = htmlspecialchars($_POST['cost_person']);
    $useremail = $_SESSION['login'];
   

    $sql = "SELECT FullName FROM tblusers WHERE EmailId = :useremail";
    $query = $dbh->prepare($sql);
    $query->bindParam(':useremail', $useremail, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);
    $passenger = $result ? $result->FullName : '';

    // Insert ride request
    $stmt = $dbh->prepare("
        INSERT INTO ride_requests (ride_id, pickup_point, number_of_seats, passenger_name, message, start_place, end_place, date_of_travel, time_of_travel, driver,cost)
        VALUES (:rideid, :pickup_point, :number_of_seats, :passenger, :message, :start_place, :end_place, :date_of_travel, :time_of_travel, :posted_by, :price)
    ");

    $stmt->bindParam(':rideid', $rideid);
    $stmt->bindParam(':pickup_point', $pickup_point);
    $stmt->bindParam(':number_of_seats', $number_of_seats);
    $stmt->bindParam(':passenger', $passenger);
    $stmt->bindParam(':message', $message);
    $stmt->bindParam(':start_place', $start_place);
    $stmt->bindParam(':end_place', $end_place);
    $stmt->bindParam(':date_of_travel', $date_of_travel);
    $stmt->bindParam(':time_of_travel', $time_of_travel);
    $stmt->bindParam(':posted_by', $postedby);
    $stmt->bindParam(':price', $price);

    if ($stmt->execute()) {
        header('Location: Trips.php');
        exit();
       
        // Optionally redirect to another page
        // header('Location: success_page.php');
    } else {
        header('Location: Request_ride.php?status=1');
        exit();
    }
} else {
    // Redirect to the form page if accessed directly
    header('Location: request_ride.php');
    exit;
}
?>
