<?php

namespace classes;

use PDO;

class Ride {

    private $dbh;

    public function __construct($dbh) {
        $this->dbh = $dbh;
    }

    public function postRide($start_place, $end_place, $postedby, $capacity, $messageContent, $date_of_travel, $time_of_travel, $duration, $distance) {
        try {
            $sql = "INSERT INTO tblrides (start_place, end_place, postedby, capacity, message, date_of_travel, time_of_travel, duration, distance) 
                    VALUES (:start_place, :end_place, :postedby, :capacity, :message, :date_of_travel, :time_of_travel, :duration, :distance)";
            $query = $this->dbh->prepare($sql);
            $query->bindParam(':start_place', $start_place, PDO::PARAM_STR);
            $query->bindParam(':end_place', $end_place, PDO::PARAM_STR);
            $query->bindParam(':postedby', $postedby, PDO::PARAM_STR);
            $query->bindParam(':capacity', $capacity, PDO::PARAM_INT);
            $query->bindParam(':message', $messageContent, PDO::PARAM_STR);  
            $query->bindParam(':date_of_travel', $date_of_travel, PDO::PARAM_STR);
            $query->bindParam(':time_of_travel', $time_of_travel, PDO::PARAM_STR);
            $query->bindParam(':duration', $duration, PDO::PARAM_STR);
            $query->bindParam(':distance', $distance, PDO::PARAM_STR);

            $query->execute();
            $lastInsertId = $this->dbh->lastInsertId();
            if ($lastInsertId) {
                header('Location: Trips.php');
                exit();
            } else {
                $message = "<h6 class='text-danger' style='color: red'; font-size: 20px;'>Ride Failed To Post.</h6>";
            }
        } catch (PDOException $e) {
            $message = "<h6 class='text-danger' style='color: red'; font-size: 20px;'>Error: " . $e->getMessage() . "</h6>";
        }
    }
}
