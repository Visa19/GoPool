<?php
namespace classes;
Use PDO;


class Booking {
   
    private $dbh;

    public function __construct($dbh) {
        $this->dbh = $dbh;
    }

    public function bookVehicle($fromdate, $todate, $message, $useremail, $vhid) {
        $bookingno = mt_rand(100000000, 999999999);
        $status = 0;

       
        $ret = "SELECT * FROM tblbooking WHERE 
                (:fromdate BETWEEN date(FromDate) AND date(ToDate) 
                OR :todate BETWEEN date(FromDate) AND date(ToDate) 
                OR date(FromDate) BETWEEN :fromdate AND :todate) 
                AND VehicleId = :vhid";
        
        $query1 = $this->dbh->prepare($ret);
        $query1->bindParam(':vhid', $vhid, PDO::PARAM_STR);
        $query1->bindParam(':fromdate', $fromdate, PDO::PARAM_STR);
        $query1->bindParam(':todate', $todate, PDO::PARAM_STR);
        $query1->execute();
        $results1 = $query1->fetchAll(PDO::FETCH_OBJ);

        if ($query1->rowCount() == 0) {
           
            $sql = "INSERT INTO tblbooking (BookingNumber, userEmail, VehicleId, FromDate, ToDate, message, Status) 
                    VALUES (:bookingno, :useremail, :vhid, :fromdate, :todate, :message, :status)";
            
            $query = $this->dbh->prepare($sql);
            $query->bindParam(':bookingno', $bookingno, PDO::PARAM_STR);
            $query->bindParam(':useremail', $useremail, PDO::PARAM_STR);
            $query->bindParam(':vhid', $vhid, PDO::PARAM_STR);
            $query->bindParam(':fromdate', $fromdate, PDO::PARAM_STR);
            $query->bindParam(':todate', $todate, PDO::PARAM_STR);
            $query->bindParam(':message', $message, PDO::PARAM_STR);
            $query->bindParam(':status', $status, PDO::PARAM_STR);
            $query->execute();
            $lastInsertId = $this->dbh->lastInsertId();

            if ($lastInsertId) {
                echo "<script>alert('Booking successful.');</script>";
                echo "<script type='text/javascript'> document.location = 'my-booking.php'; </script>";
            } else {
                echo "<script>alert('Something went wrong. Please try again');</script>";
                echo "<script type='text/javascript'> document.location = 'car-listing.php'; </script>";
            }
        } else {
            echo "<script>alert('Car already booked for these days');</script>";
            echo "<script type='text/javascript'> document.location = 'car-listing.php'; </script>";
        }
    }
}


if (isset($_POST['submit'])) {
    $fromdate = $_POST['fromdate'];
    $todate = $_POST['todate'];
    $message = $_POST['message'];
    $useremail = $_SESSION['login'];
    $vhid = $_GET['vhid'];

   
    $booking = new Booking($dbh);
    $booking->bookVehicle($fromdate, $todate, $message, $useremail, $vhid);
}



