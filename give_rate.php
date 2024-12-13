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

if (isset($_POST['rating']) && isset($_POST['review']) && isset($_POST['veh-number'])) {
    $rating = (int)$_POST['rating'];  // Ensure that the rating is an integer
    $review = trim($_POST['review']);  // Trim any extra spaces from the review
    $vehicle_id = (int)$_POST['veh-number'];  // Ensure that the vehicle_id is an integer

    $useremail = $_SESSION['login'];
    $sql = "SELECT FullName FROM tblusers WHERE EmailId = :useremail";
    $query = $dbh->prepare($sql);
    $query->bindParam(':useremail', $useremail, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);
    $postedby = $result ? $result->FullName : '';

    $rating_type = "Rental";

    $sql = "INSERT INTO tblrating (postedby, Rating, review, rating_type, VehicleId) VALUES (:postedby, :rating, :review, :rating_type, :vehicle_id)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':postedby', $postedby, PDO::PARAM_STR);
    $query->bindParam(':rating', $rating, PDO::PARAM_INT);
    $query->bindParam(':review', $review, PDO::PARAM_STR);
    $query->bindParam(':rating_type', $rating_type, PDO::PARAM_STR);
    $query->bindParam(':vehicle_id', $vehicle_id, PDO::PARAM_INT);
    $query->execute();

  header('Location: vehical-details.php?vhid=' . $vehicle_id);
             exit();
}
echo "Error In Submitting Ratings.";
?>
