<?php

namespace classes;

use PDO;

class ContactForm {

    private $dbh;

    public function __construct($dbh) {
        $this->dbh = $dbh;
    }

    public function sendContactForm($name, $email, $contactno, $message) {
        $sql = "INSERT INTO tblcontactusquery(name, EmailId, ContactNumber, Message) VALUES(:name, :email, :contactno, :message)";
        $query = $this->dbh->prepare($sql);
        $query->bindParam(':name', $name, PDO::PARAM_STR);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':contactno', $contactno, PDO::PARAM_STR);
        $query->bindParam(':message', $message, PDO::PARAM_STR);
        $query->execute();
        $lastInsertId = $this->dbh->lastInsertId();

        if ($lastInsertId) {
            return "Message Sent Sucessfully.Thank You.";
        } else {
            return "Something went wrong. Please try again";
        }
    }

   public function getContactInfo() {
        $sql = "SELECT Address, EmailId, ContactNo FROM tblcontactusinfo";
        $query = $this->dbh->prepare($sql);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);
        return $results;
    }
    
 public function getAllQueries() {
        try {
            $sql = "SELECT * FROM tblcontactusquery";
            $query = $this->dbh->prepare($sql);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }
    
     public function updateContactInfo($address, $email, $contactno) {
        try {
            $sql = "UPDATE tblcontactusinfo SET Address = :address, EmailId = :email, ContactNo = :contactno";
            $query = $this->dbh->prepare($sql);
            $query->bindParam(':address', $address, PDO::PARAM_STR);
            $query->bindParam(':email', $email, PDO::PARAM_STR);
            $query->bindParam(':contactno', $contactno, PDO::PARAM_STR);
            return $query->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

}
