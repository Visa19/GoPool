<?php
session_start();
error_reporting(0);
require_once './classes/DbConnector.php';

use classes\DbConnector;

try {
    $dbConnector = new DbConnector();
    $dbh = $dbConnector->getConnection();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GoPool | Trips</title>
    <!--Bootstrap -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css">
    <!--Custom Style -->
    <link rel="stylesheet" href="assets/css/style.css" type="text/css">
    <link href="assets/css/font-awesome.min.css" rel="stylesheet">
</head>
<body>
    <!--Header-->
    <?php include('includes/header.php');?>
    
    <section class="page-header listing_page">
        <div class="container">
            <div class="page-header_wrap">
                <div class="page-heading">
                    <h1>My Trips</h1>
                </div>
            </div>
        </div>
        <!-- Dark Overlay-->
        <div class="dark-overlay"></div>
    </section>
    <br>
    <br>
    <div class="container mt-5">
        
        <!-- Driving Section -->
        <div class="mb-5">
            <h3>Driving</h3>
            <?php 
            $useremail = $_SESSION['login'];
            $sql = "SELECT * FROM tblusers WHERE EmailId = :useremail";
            $query = $dbh->prepare($sql);
            $query->bindParam(':useremail', $useremail, PDO::PARAM_STR);
            $query->execute();
            $result = $query->fetch(PDO::FETCH_OBJ);

            if ($result) {
                $postedby = $result->FullName;

                $sql = "SELECT * FROM tblrides WHERE PostedBy = :postedby";
                $query = $dbh->prepare($sql);
                $query->bindParam(':postedby', $postedby, PDO::PARAM_STR);
                $query->execute();
                $results = $query->fetchAll(PDO::FETCH_OBJ);

                if ($query->rowCount() > 0) { ?>
                    <table class="table table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>Start Place</th>
                                <th>End Place</th>
                                <th>Date</th>
                                <th>Available Seats</th>
                                <th>Pickup Points</th>
                                <th>Action</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($results as $ride) { ?>
                            <tr>
                                <td><?php echo htmlentities($ride->start_place); ?></td>
                                <td><?php echo htmlentities($ride->end_place); ?></td>
                                <td><?php echo htmlentities($ride->date_of_travel); ?></td>
                                <td><?php echo htmlentities($ride->capacity); ?></td>
                               <td><a href="View_route_map.php?ride_id=<?php echo htmlentities($ride->ride_id); ?>" target="_blank" class="btn btn-success">View Ride Map</a></td>
                               <td><a href="Manage_trips.php?ride_id=<?php echo htmlentities($ride->ride_id); ?>" class="btn btn-success">Manage Trip</a></td>
                                 
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php } else { ?>
                    <p>No Rides To Be Shown. Use Below Button To Add New Ride.</p>
                <?php }
            } else { ?>
                <p>No User Found</p>
            <?php } ?>
            <button class="btn btn-info mt-3" onclick="window.location.href='Give_ride.php'">+ Add New Ride</button>
        </div>
        <br>
        <br>
        <!-- Riding Section -->
        <div class="mb-5">
            <h3>Riding</h3>
            <?php 
                $sql = "SELECT * FROM ride_requests WHERE passenger_name = :postedby";
                $query = $dbh->prepare($sql);
                $query->bindParam(':postedby', $postedby, PDO::PARAM_STR);
                $query->execute();
                $results = $query->fetchAll(PDO::FETCH_OBJ);

                if ($query->rowCount() > 0) { ?>
                    <table class="table table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>Start Place</th>
                                <th>End Place</th>
                                <th>Date</th>
                                <th>My Pickup Point</th>
                                <th>Ride Request Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                       <tbody>
    <?php foreach ($results as $req) { ?>
    <tr>
        <td><?php echo htmlentities($req->start_place); ?></td>
        <td><?php echo htmlentities($req->end_place); ?></td>
        <td><?php echo htmlentities($req->date_of_travel); ?></td>
        <td><?php echo htmlentities($req->pickup_point); ?></td>
        <td><?php echo htmlentities($req->Status); ?></td>
        <td>
            <?php if ($req->Status == 'Confirmed') { ?>
            <a href="Take_ride_manage_trip.php?ride_request_id=<?php echo htmlentities($req->ride_request_id); ?>" class="btn btn-success">Manage Trip</a>

            <?php } elseif ($req->Status == 'Pending') { ?>
                <a href="Delete_take_ride.php?request_id=<?php echo htmlentities($req->ride_request_id); ?>" class="btn btn-success">Cancel Ride</a>
            <?php } ?>
        </td>
    </tr>
    <?php } ?>
</tbody>
                    </table>
                <?php } else { ?>
                    <p>You Didn't Request Any Ride</p>
                <?php }?>
            
            <button class="btn btn-info mt-3" onclick="window.location.href='Take_ride.php'">+ Request New Ride</button>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
