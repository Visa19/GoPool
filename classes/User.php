<?php
namespace classes;

use PDO;
class User
{
    private $dbh; 

    public function __construct($dbh)
    {
        $this->dbh = $dbh;
    }

 public function register($fullname, $email, $password, $mobile, $dob, $address, $selfieImageName, $nicImageName)
    {
        try {
            $sql = "INSERT INTO tblusers (FullName, EmailId, Password, ContactNo, DOB, Address, Selfie, NIC) 
                    VALUES (:fullname, :email, :password, :contactno, :dob, :address, :selfie_image, :nic_image)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':fullname', $fullname, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);
            $stmt->bindParam(':contactno', $mobile, PDO::PARAM_STR);
            $stmt->bindParam(':dob', $dob, PDO::PARAM_STR);
            $stmt->bindParam(':address', $address, PDO::PARAM_STR);
            $stmt->bindParam(':selfie_image', $selfieImageName, PDO::PARAM_STR); // Store only the image name
            $stmt->bindParam(':nic_image', $nicImageName, PDO::PARAM_STR); // Store only the image name
            $stmt->execute();

            $lastInsertId = $this->db->lastInsertId();
            return $lastInsertId;
        } catch (PDOException $e) {
            // Handle exception
            return false;
        }
    }
     public function getAllUsers() {
        $sql = "SELECT * FROM tblusers";
        $query = $this->dbh->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_OBJ);
    }
   public function confirmUser($userId)
    {
        try {
            $newStatus = 'Confirmed';
            $sql = "UPDATE tblusers SET Status=:status WHERE id=:userId";
            $query = $this->dbh->prepare($sql);
            $query->bindParam(':status', $newStatus, PDO::PARAM_STR);
            $query->bindParam(':userId', $userId, PDO::PARAM_INT);

            if ($query->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            // Handle any database errors
            return false;
        }
    }

    public function deleteUser($userId)
    {
        try {
            $sql = "DELETE FROM tblusers WHERE id=:userId";
            $query = $this->dbh->prepare($sql);
            $query->bindParam(':userId', $userId, PDO::PARAM_INT);

            if ($query->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            // Handle any database errors
            return false;
        }
    }
      public function getUserEmailById($userId)
    {
        try {
            $sql = "SELECT EmailId FROM tblusers WHERE id = :userId";
            $query = $this->dbh->prepare($sql);
            $query->bindParam(':userId', $userId, \PDO::PARAM_INT);
            $query->execute();
            $result = $query->fetch(\PDO::FETCH_ASSOC);
            return $result['EmailId'];
        } catch (\PDOException $e) {
            return false;
        }
    }
}
   



        
        
        

