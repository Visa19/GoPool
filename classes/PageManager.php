<?php

namespace classes;

use PDO;

class PageManager {
    private $dbh;
   
    
    
    public function __construct($dbh) {
        $this->dbh = $dbh;
    }
   
 

        public function updatePageDetails($pagetype, $pagedetails) {
        $sql = "UPDATE tblpages SET detail = :pagedetails WHERE type = :pagetype";
        $query = $this->dbh->prepare($sql);
        $query->bindParam(':pagetype', $pagetype, PDO::PARAM_STR);
        $query->bindParam(':pagedetails', $pagedetails, PDO::PARAM_STR);
        return $query->execute();
    }

    public function getPageDetails($pagetype) {
        $sql = "SELECT detail FROM tblpages WHERE type = :pagetype";
        $query = $this->dbh->prepare($sql);
        $query->bindParam(':pagetype', $pagetype, PDO::PARAM_STR);
        $query->execute();
        return $query->fetch(PDO::FETCH_OBJ);
    }
    
     public function getPageDetailsFront()
    {
        $pagetype = $_GET['type'];
        $sql = "SELECT type, detail, PageName FROM tblpages WHERE type = :pagetype";
        $query = $this->dbh->prepare($sql);
        $query->bindParam(':pagetype', $pagetype, PDO::PARAM_STR);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);
        return $results;
    }
}
