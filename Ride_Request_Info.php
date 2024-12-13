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

$postedby = $_POST['postedby'];
$sql = "SELECT * FROM tblrideshare_vehicles WHERE PostedBy = :postedby";
$query = $dbh->prepare($sql);
$query->bindParam(':postedby', $postedby, PDO::PARAM_STR);
$query->execute();
$results = $query->fetchAll(PDO::FETCH_OBJ);

if ($query->rowCount() > 0) {
    foreach ($results as $result) {
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <title>GoPool | Car Details</title>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css">
    <!-- Custom Style -->
    <link rel="stylesheet" href="assets/css/style.css" type="text/css">
    <!-- Owl Carousel Slider -->
    <link rel="stylesheet" href="assets/css/owl.carousel.css" type="text/css">
    <link rel="stylesheet" href="assets/css/owl.transitions.css" type="text/css">
    <!-- Slick Slider -->
    <link href="assets/css/slick.css" rel="stylesheet">
    <!-- Bootstrap Slider -->
    <link href="assets/css/bootstrap-slider.min.css" rel="stylesheet">
    <!-- FontAwesome Font Style -->
    <link href="assets/css/font-awesome.min.css" rel="stylesheet">
    <!-- Switcher -->
    <link rel="stylesheet" id="switcher-css" type="text/css" href="assets/switcher/css/switcher.css" media="all" />
    <link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/red.css" title="red" media="all" data-default-color="true" />
    <link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/orange.css" title="orange" media="all" />
    <link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/blue.css" title="blue" media="all" />
    <link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/pink.css" title="pink" media="all" />
    <link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/green.css" title="green" media="all" />
    <link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/purple.css" title="purple" media="all" />
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/images/favicon-icon/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/images/favicon-icon/apple-touch-icon-114-precomposed.html">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/images/favicon-icon/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="assets/images/favicon-icon/apple-touch-icon-57-precomposed.png">
    <link rel="shortcut icon" href="assets/images/favicon-icon/favicon.png">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900" rel="stylesheet"> 

    <style>
        .errorWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #fff;
            border-left: 4px solid #dd3d36;
            -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
            box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
        }
        .succWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #fff;
            border-left: 4px solid #5cb85c;
            -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
            box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
        }
        .message-box {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 50vh;
            background-color: #f5f5f5;
        }
        .message-content {
            padding: 20px;
            border: 2px solid red;
            border-radius: 10px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h5 {
            margin: 0;
            color: red;
            text-align: center;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .angle_arrow {
            margin-left: 10px;
        }
        .page-header {
            background: #007bff;
            padding: 50px 0;
            color: #fff;
            text-align: center;
        }
        .user_profile_info {
            margin: 30px auto;
            padding: 30px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 800px;
        }
        .profile_wrap h5 {
            font-size: 24px;
            margin-bottom: 20px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
            color: #007bff;
            text-align: center;
        }
        .form-group label {
            font-weight: bold;
        }
        .form-control {
            border-radius: 5px;
            border: 1px solid #ddd;
            padding: 10px;
        }
        .table {
            margin-top: 20px;
        }
        .table th {
            background: #f5f5f5;
            color: #333;
        }
        .table td {
            padding: 10px;
        }
        .table td i {
            color: green;
        }
        .table td i.fa-close {
            color: red;
        }
        .owl-carousel .item {
            padding: 10px;
        }
        .owl-carousel .item img {
            border-radius: 5px;
        }
        @media (max-width: 768px) {
            .user_profile_info {
                padding: 20px;
            }
            .profile_wrap h5 {
                font-size: 20px;
            }
        }
        .profile_page {
            background-image:url(assets/images/profile-page-header-img.jpg);
        }
    </style>
</head>
<body>
    <section class="page-header profile_page">
        <div class="container">
            <div class="page-header_wrap">
                <div class="page-heading">
                    <h1>Ride Share Vehicle Details</h1>
                </div>
            </div>
        </div>
        <!-- Dark Overlay -->
        <div class="dark-overlay"></div>
    </section>
    <!-- /Page Header --> 

    <section id="listing_img_slider" class="container">
        <div><img src="admin/img/Veh_share_img/<?php echo htmlentities($result->Vimage1);?>" class="img-responsive" alt="image" width="900" height="560"></div>
        <div><img src="admin/img/Veh_share_img/<?php echo htmlentities($result->Vimage2);?>" class="img-responsive" alt="image" width="900" height="560"></div>
        <div><img src="admin/img/Veh_share_img/<?php echo htmlentities($result->Vimage3);?>" class="img-responsive" alt="image" width="900" height="560"></div>
        <div><img src="admin/img/Veh_share_img/<?php echo htmlentities($result->Vimage4);?>" class="img-responsive"  alt="image" width="900" height="560"></div>
        <?php if($result->Vimage5=="") {} else { ?>
        <div><img src="admin/img/Veh_share_img/<?php echo htmlentities($result->Vimage5);?>" class="img-responsive" alt="image" width="900" height="560"></div>
        <?php } ?>
    </section>
                  
    <section class="user_profile_info container">
        <div class="listing_more_info">
            <div class="listing_detail_wrap"> 
                <!-- Nav tabs -->
                <ul class="nav nav-tabs gray-bg" role="tablist">
                    <li role="presentation" class="active"><a href="#vehicle-overview" aria-controls="vehicle-overview" role="tab" data-toggle="tab">Vehicle Overview</a></li>
                    <li role="presentation"><a href="#accessories" aria-controls="accessories" role="tab" data-toggle="tab">Vehicle Accessories</a></li>
                     <li role="presentation"><a href="#review" aria-controls="accessories" role="tab" data-toggle="tab">Reviews</a></li>
                </ul>
                
                <!-- Tab panes -->
                <div class="tab-content"> 
                    <!-- vehicle-overview -->
                    <div role="tabpanel" class="tab-pane active" id="vehicle-overview">
                        <div class="form-group">
                            <label class="control-label">Vehicle Number</label>
                            <input class="form-control white_bg" name="fullname" value="<?php echo htmlentities($result->VehicleNumber);?>" id="fullname" type="text"  required readonly>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Vehicle Name</label>
                            <input class="form-control white_bg" value="<?php echo htmlentities($result->VehiclesTitle);?>" name="emailid" id="email" type="email" required readonly>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Vehicle Brand</label>
                            <input class="form-control white_bg" name="mobilenumber" value="<?php echo htmlentities($result->VehiclesBrand);?>" id="phone-number" type="text" required readonly>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Fuel Type</label>
                            <input class="form-control white_bg" value="<?php echo htmlentities($result->FuelType);?>" name="dob" placeholder="dd/mm/yyyy" id="birth-date" type="text" readonly>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Seating Capacity</label>
                            <input class="form-control white_bg" value="<?php echo htmlentities($result->SeatingCapacity);?>" name="dob" placeholder="dd/mm/yyyy" id="birth-date" type="text" readonly>
                        </div>
                    </div>
                    
                    <!-- Accessories -->
                    <div role="tabpanel" class="tab-pane" id="accessories"> 
                        <!-- Accessories -->
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th colspan="2">Accessories</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Air Conditioner</td>
                                    <?php if($result->AirConditioner==1) { ?>
                                    <td><i class="fa fa-check" aria-hidden="true"></i></td>
                                    <?php } else { ?> 
                                    <td><i class="fa fa-close" aria-hidden="true"></i></td>
                                    <?php } ?> 
                                </tr>

                                <tr>
                                    <td>AntiLock Braking System</td>
                                    <?php if($result->AntiLockBrakingSystem==1) { ?>
                                    <td><i class="fa fa-check" aria-hidden="true"></i></td>
                                    <?php } else { ?>
                                    <td><i class="fa fa-close" aria-hidden="true"></i></td>
                                    <?php } ?>
                                </tr>

                                <tr>
                                    <td>Power Steering</td>
                                    <?php if($result->PowerSteering==1) { ?>
                                    <td><i class="fa fa-check" aria-hidden="true"></i></td>
                                    <?php } else { ?>
                                    <td><i class="fa fa-close" aria-hidden="true"></i></td>
                                    <?php } ?>
                                </tr>

                                <tr>
                                    <td>Power Windows</td>
                                    <?php if($result->PowerWindows==1) { ?>
                                    <td><i class="fa fa-check" aria-hidden="true"></i></td>
                                    <?php } else { ?>
                                    <td><i class="fa fa-close" aria-hidden="true"></i></td>
                                    <?php } ?>
                                </tr>
                               
                                <tr>
                                    <td>CD Player</td>
                                    <?php if($result->CDPlayer==1) { ?>
                                    <td><i class="fa fa-check" aria-hidden="true"></i></td>
                                    <?php } else { ?>
                                    <td><i class="fa fa-close" aria-hidden="true"></i></td>
                                    <?php } ?>
                                </tr>

                                <tr>
                                    <td>Leather Seats</td>
                                    <?php if($result->LeatherSeats==1) { ?>
                                    <td><i class="fa fa-check" aria-hidden="true"></i></td>
                                    <?php } else { ?>
                                    <td><i class="fa fa-close" aria-hidden="true"></i></td>
                                    <?php } ?>
                                </tr>

                                <tr>
                                    <td>Central Locking</td>
                                    <?php if($result->CentralLocking==1) { ?>
                                    <td><i class="fa fa-check" aria-hidden="true"></i></td>
                                    <?php } else { ?>
                                    <td><i class="fa fa-close" aria-hidden="true"></i></td>
                                    <?php } ?>
                                </tr>

                                <tr>
                                    <td>Power Door Locks</td>
                                    <?php if($result->PowerDoorLocks==1) { ?>
                                    <td><i class="fa fa-check" aria-hidden="true"></i></td>
                                    <?php } else { ?>
                                    <td><i class="fa fa-close" aria-hidden="true"></i></td>
                                    <?php } ?>
                                </tr>
                                <tr>
                                    <td>Brake Assist</td>
                                    <?php if($result->BrakeAssist==1) { ?>
                                    <td><i class="fa fa-check" aria-hidden="true"></i></td>
                                    <?php } else { ?>
                                    <td><i class="fa fa-close" aria-hidden="true"></i></td>
                                    <?php } ?>
                                </tr>

                                <tr>
                                    <td>Driver Airbag</td>
                                    <?php if($result->DriverAirbag==1) { ?>
                                    <td><i class="fa fa-check" aria-hidden="true"></i></td>
                                    <?php } else { ?>
                                    <td><i class="fa fa-close" aria-hidden="true"></i></td>
                                    <?php } ?>
                                </tr>
                                
                                <tr>
                                    <td>Passenger Airbag</td>
                                    <?php if($result->PassengerAirbag==1) { ?>
                                    <td><i class="fa fa-check" aria-hidden="true"></i></td>
                                    <?php } else { ?>
                                    <td><i class="fa fa-close" aria-hidden="true"></i></td>
                                    <?php } ?>
                                </tr>

                                <tr>
                                    <td>Crash Sensor</td>
                                    <?php if($result->CrashSensor==1) { ?>
                                    <td><i class="fa fa-check" aria-hidden="true"></i></td>
                                    <?php } else { ?>
                                    <td><i class="fa fa-close" aria-hidden="true"></i></td>
                                    <?php } ?>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                     <!-- Reviews -->
                    
                                <?php
                                // Check that $result and $result->postedby are set and valid

                                $driver = $result->PostedBy;

                                // Fetch ratings and reviews from the database
                                $ratingQuery = "SELECT * FROM tblrating WHERE DriverName = :driver AND rating_type = :rating_type";
                                $ratingStmt = $dbh->prepare($ratingQuery);
                                $ratingStmt->bindParam(':driver', $driver, PDO::PARAM_STR);
                                $ratingStmt->bindValue(':rating_type', 'driver', PDO::PARAM_STR);
                                $ratingStmt->execute();

                                // Check for query errors
                                if ($ratingStmt->errorCode() != '00000') {
                                    $errorInfo = $ratingStmt->errorInfo();
                                    echo '<p>Error: ' . htmlentities($errorInfo[2]) . '</p>';
                                } else {
                                    $ratings = $ratingStmt->fetchAll(PDO::FETCH_ASSOC);
                                }
                                ?>
<div role="tabpanel" class="tab-pane active" id="review">
                                <div class="details-container">
                                    <h2>Ratings and Reviews</h2>
                                    <?php if (isset($ratings) && count($ratings) > 0): ?>
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>Posted By</th>
                                                    <th>Rating</th>
                                                    <th>Review</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($ratings as $rating): ?>
                                                    <tr>
                                                        <td><?php echo htmlentities($rating['postedby']); ?></td>
                                                        <td><?php echo htmlentities($rating['Rating']); ?> ⭐</td>
                                                        <td><?php echo htmlentities($rating['review']); ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    <?php else: ?>
                                        <p>No ratings and reviews found for this driver.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
   </div>
            <br>
            <br>
            <div class="form-group text-center">
               <form id="backForm" action="Take_ride.php" method="POST">
    <div class="form-group text-center">
        <button type="submit" class="btn">
            Back
            <span class="angle_arrow">
                <i class="fa fa-angle-right" aria-hidden="true"></i>
            </span>
        </button>
    </div>
</form>
            </div>
        </div>
    </section>
<?php }} else { ?>
    <div class="message-box">
        <div class="message-content">
            <h5>You Didn't Post Any Vehicle For Ride Share.</h5>
        </div>
    </div>
<?php } ?>

<!-- Scripts --> 
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script> 
<script src="assets/js/interface.js"></script> 
<!-- Switcher -->
<script src="assets/switcher/js/switcher.js"></script>
<!-- Bootstrap Slider JS --> 
<script src="assets/js/bootstrap-slider.min.js"></script> 
<!-- Slider JS --> 
<script src="assets/js/slick.min.js"></script> 
<script src="assets/js/owl.carousel.min.js"></script>
<script>
    $(document).ready(function(){
        $("#listing_img_slider").owlCarousel({
            items: 1,
            loop: true,
            nav: true,
            dots: false,
            autoplay: true,
            autoplayTimeout: 5000,
            autoplayHoverPause: true
        });
    });
</script>
</body>
</html>
