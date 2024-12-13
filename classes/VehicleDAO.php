<?php
namespace classes;

use PDO;

class VehicleDAO {
    private $dbh;

    public function __construct($dbh) {
        $this->dbh = $dbh;
    }

    public function getVehicles($limit = 15) {
        $sql = "SELECT tblvehicles.VehiclesTitle, tblbrands.BrandName, tblvehicles.PricePerDay,tblvehicles.AvailableDistrict, tblvehicles.FuelType, tblvehicles.ModelYear, tblvehicles.id, tblvehicles.SeatingCapacity, tblvehicles.VehiclesOverview, tblvehicles.Vimage1 
                FROM tblvehicles 
                JOIN tblbrands ON tblbrands.id = tblvehicles.VehiclesBrand 
                LIMIT :limit";

        $query = $this->dbh->prepare($sql);
        $query->bindParam(':limit', $limit, PDO::PARAM_INT);

        try {
            $query->execute();
        } catch (PDOException $e) {
            echo "Query failed: " . $e->getMessage();
            return [];
        }

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
        public function getVehicleDetails($vhid) {
        $sql = "SELECT tblvehicles.*, tblbrands.BrandName, tblbrands.id as bid 
                FROM tblvehicles 
                JOIN tblbrands ON tblbrands.id = tblvehicles.VehiclesBrand 
                WHERE tblvehicles.id = :vhid";
        $query = $this->dbh->prepare($sql);
        $query->bindParam(':vhid', $vhid, PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    public function displayVehicleDetails($vhid) {
        $results = $this->getVehicleDetails($vhid);
        if (count($results) > 0) {
            foreach ($results as $result) {
                $_SESSION['brndid'] = $result->bid;
               
                echo "<h2>" . htmlentities($result->BrandName) . " " . htmlentities($result->VehicleName) . "</h2>";
                echo "<p>Price: " . htmlentities($result->PricePerDay) . "</p>";
                echo "<p>Model Year: " . htmlentities($result->ModelYear) . "</p>";
                
            }
        } else {
            echo "<p>No vehicle found.</p>";
        }
    }
}
