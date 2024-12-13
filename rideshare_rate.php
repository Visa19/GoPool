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

if (isset($_POST['rating']) && isset($_POST['review']) && isset($_POST['driver']) && isset($_POST['ride_request_id'])) {
    $rating = (int)$_POST['rating'];  // Ensure that the rating is an integer
    $review = trim($_POST['review']);  // Trim any extra spaces from the review
    $driver = urldecode($_POST['driver']);  
    $ride_request_id = (int)$_POST['ride_request_id'];

    $useremail = $_SESSION['login'];
    $sql = "SELECT FullName FROM tblusers WHERE EmailId = :useremail";
    $query = $dbh->prepare($sql);
    $query->bindParam(':useremail', $useremail, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);
    $postedby = $result ? $result->FullName : '';

    $rating_type = "driver";

    $sql = "INSERT INTO tblrating (postedby, Rating, review, rating_type, DriverName) VALUES (:postedby, :rating, :review, :rating_type, :driver)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':postedby', $postedby, PDO::PARAM_STR);
    $query->bindParam(':rating', $rating, PDO::PARAM_INT);
    $query->bindParam(':review', $review, PDO::PARAM_STR);
    $query->bindParam(':rating_type', $rating_type, PDO::PARAM_STR);
    $query->bindParam(':driver', $driver, PDO::PARAM_STR);
    $query->execute();

    header('Location: Take_ride_manage_trip.php?ride_request_id=' . urlencode($ride_request_id));
    exit();
}

echo "Error In Submitting Ratings.";
?>
