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

$ride_id = isset($_GET['ride_id']) ? $_GET['ride_id'] : null;

if ($ride_id) {
    $sql = "SELECT * FROM tblrides WHERE ride_id = :ride_id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':ride_id', $ride_id, PDO::PARAM_INT);
    $query->execute();
    $ride = $query->fetch(PDO::FETCH_OBJ);

    if (!$ride) {
        $message = "<h2 class='text-danger' style='color: red; font-size: 16px;'>No matching rides found. Please wait.</h2>";
        echo $message;
        exit;
    }
} else {
    echo "<h2 class='text-danger' style='color: red; font-size: 16px;'>No ride ID provided.</h2>";
    exit;
}
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GoPool | Manage Trip</title>
    <!--Bootstrap -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css">
    <!--Custom Style -->
    <link rel="stylesheet" href="assets/css/style.css" type="text/css">
    <link href="assets/css/font-awesome.min.css" rel="stylesheet">
</head>
<body>
    <!--Header-->
    <?php include('includes/header.php'); ?>
    <section class="page-header listing_page">
        <div class="container">
            <div class="page-header_wrap">
                <div class="page-heading">
                    <h1>Manage Trip</h1>
                </div>
            </div>
        </div>
        <!-- Dark Overlay-->
        <div class="dark-overlay"></div>
    </section>
    <br>
    <br>
    <div class="container mt-5">
        <?php if (isset($ride)) { ?>
            <h2>Trip Details</h2>
            <table class="table table-bordered">
                <tr>
                    <th>Start Place</th>
                    <td><?php echo htmlentities($ride->start_place); ?></td>
                </tr>
                <tr>
                    <th>End Place</th>
                    <td><?php echo htmlentities($ride->end_place); ?></td>
                </tr>
                <tr>
                    <th>Date</th>
                    <td><?php echo htmlentities($ride->date_of_travel); ?></td>
                </tr>
                <tr>
                    <th>Available Seats</th>
                    <td><?php echo htmlentities($ride->capacity); ?></td>
                </tr>
                <tr>
                    <th>You can cancel trip until the first payment is done for your trip</th>
                    <td>
                        <?php
                      

                            $sql = "SELECT COUNT(*) as count FROM tblpayments WHERE ride_id = :ride_id";
                            $query = $dbh->prepare($sql);
                            $query->execute([':ride_id' => $ride_id]);
                            $result = $query->fetch(PDO::FETCH_OBJ);

                            if ($result->count > 0) {
                               
                                echo "Can't cancel Trip";
                            } else {?>
                               
                               <a href="Delete_give_ride.php?ride_id=<?php echo htmlentities($ride->ride_id); ?>" class="btn btn-success">Delete Trip</a>
                      


                   <?php } ?></td>
                   
                </tr>
                <tr>
                    <th>Start Your Trip On <?php echo htmlentities($ride->date_of_travel); ?> at <?php echo htmlentities($ride->time_of_travel); ?></th>
    
 <td>
            <?php if ($ride->trip_status == 'not_started') { ?>
               <a href="Driver_GPS.php?ride_id=<?php echo urlencode($ride->ride_id); ?>" class="btn btn-success"  target="_blank">Start Trip</a>
            <?php } elseif ($ride->trip_status == 'in_progress') { ?>
                <a href="End_ride.php?ride_id=<?php echo htmlentities($ride->ride_id); ?>" class="btn btn-success">End Trip</a>
            <?php } else{
               echo "Trip Finished";
            }
?>
        </td>
                </tr>
              
            </table>
            <h3>Ride Requests</h3>
            <?php
            $sql = "SELECT * FROM ride_requests WHERE ride_id = :ride_id";
            $query = $dbh->prepare($sql);
            $query->bindParam(':ride_id', $ride_id, PDO::PARAM_INT);
            $query->execute();
            $requests = $query->fetchAll(PDO::FETCH_OBJ);

            if ($query->rowCount() > 0) { ?>
                <table class="table table-bordered table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>Passenger Name</th>
                            <th>Pickup Point</th>                                                 
                            <th>Number Of Seats</th>
                            <th>Message</th>  
                            <th>Status</th>
                            <th>Payment Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($requests as $request) { ?>
                        <tr>
                            <td><?php echo htmlentities($request->passenger_name); ?></td>
                            <td><?php echo htmlentities($request->pickup_point); ?></td>                         
                            <td><?php echo htmlentities($request->number_of_seats); ?></td>
                            <td><?php echo htmlentities($request->message); ?></td>
                            <td><?php echo htmlentities($request->Status); ?></td>
                            <td><?php echo htmlentities($request->payment_status); ?></td>
                            <td> <?php if($request->payment_status=="Completed"){
                             echo 'No action to perform'; 
                            } else{?>
                                <form action="mange_trip_process_req.php?ride_id=<?php echo $ride_id; ?>" method="post">
                                    <input type="hidden" name="request_id" value="<?php echo $request->ride_request_id ; ?>">   
                                    <input type="hidden" name="cap" value="<?php echo $ride->capacity ; ?>"> 
                                    <select name="status" class="form-control" onchange="this.form.submit()">
                                        <option value="Pending" <?php echo $request->Status == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="Confirmed" <?php echo $request->Status == 'Confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                        <option value="Rejected" <?php echo $request->Status == 'Rejected' ? 'selected' : ''; ?>>Rejected</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                        <?php }} ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <p>No Ride Requests</p>
            <?php } ?>
        <?php } ?>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
