<?php

namespace classes;

use PDO;

class Testimonial {
    private $dbh;

    public function __construct($dbh) {
        $this->dbh = $dbh;
    }

    public function addTestimonial($email, $testimonial) {
        try {
            $sql = "INSERT INTO tbltestimonial(UserEmail, Testimonial) VALUES(:email, :testimonial)";
            $query = $this->dbh->prepare($sql);
            $query->bindParam(':email', $email, PDO::PARAM_STR);
            $query->bindParam(':testimonial', $testimonial, PDO::PARAM_STR);
            return $query->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getUserTestimonials($email) {
        try {
            $sql = "SELECT * FROM tbltestimonial WHERE UserEmail = :email";
            $query = $this->dbh->prepare($sql);
            $query->bindParam(':email', $email, PDO::PARAM_STR);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getUserDetails($email) {
        try {
            $sql = "SELECT * FROM tblusers WHERE EmailId = :email";
            $query = $this->dbh->prepare($sql);
            $query->bindParam(':email', $email, PDO::PARAM_STR);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_OBJ); 
        } catch (PDOException $e) {
            return null;
        }
    }
}

