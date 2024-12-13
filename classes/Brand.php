<?php

namespace classes;

use PDO;

class Brand {
    private $dbh;
  
    
    public function __construct($dbh) {
        $this->dbh = $dbh;
        
    }

    
        public function getAllBrands() {
        try {
            $sql = "SELECT * FROM tblbrands";
            $query = $this->dbh->prepare($sql);
            $query->execute();
            $results = $query->fetchAll(PDO::FETCH_OBJ);
            return $results;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }
    
    public function addBrand($brandName) {
        try {
            $sql = "INSERT INTO tblbrands (BrandName) VALUES (:brand)";
            $query = $this->dbh->prepare($sql);
            $query->bindParam(':brand', $brandName, PDO::PARAM_STR);
            $query->execute();
            return $this->dbh->lastInsertId();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    
    public function updateBrand($id, $brandName) {
        try {
            $sql = "UPDATE tblbrands SET BrandName = :brand WHERE id = :id";
            $query = $this->dbh->prepare($sql);
            $query->bindParam(':brand', $brandName, PDO::PARAM_STR);
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            return $query->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    
     public function getBrandById($id) {
        try {
            $sql = "SELECT * FROM tblbrands WHERE id = :id";
            $query = $this->dbh->prepare($sql);
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->execute();
            return $query->fetch(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    
    public function deleteBrand($id) {
        try {
            $sql = "DELETE FROM tblbrands WHERE id = :id";
            $query = $this->dbh->prepare($sql);
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            return $query->execute();
        } catch (PDOException $e) {
            return false;
        }
}
}