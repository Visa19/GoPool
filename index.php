<?php 
session_start();

require_once 'classes/DbConnector.php';
require_once 'classes/VehicleDAO.php';

use classes\VehicleDAO;

error_reporting(0);

try {
    $dbConnector = new \classes\DbConnector();
    $dbh = $dbConnector->getConnection();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
<title>GoPool</title>
<!--Bootstrap -->
<link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css">
<link rel="stylesheet" href="assets/css/style.css" type="text/css">
<link rel="stylesheet" href="assets/css/owl.carousel.css" type="text/css">
<link rel="stylesheet" href="assets/css/owl.transitions.css" type="text/css">
<link href="assets/css/slick.css" rel="stylesheet">
<link href="assets/css/bootstrap-slider.min.css" rel="stylesheet">
<link href="assets/css/font-awesome.min.css" rel="stylesheet">
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

</head>
<body>

<!-- Start Switcher -->
<?php include('includes/colorswitcher.php');?>
<!-- /Switcher -->  
        
<!--Header-->
<?php include('includes/header.php');?>
<!-- /Header --> 

<!-- Banners -->
<section id="banner" class="banner-section">
  <div class="container">
    <div class="div_zindex">
      <div class="row">
        <div class="col-md-5 col-md-push-7">
          <div class="banner_content">
            <h1>&nbsp;</h1>
            <p>&nbsp; </p>
            </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- /Banners --> 

<!-- Recent Cars -->
<section class="section-padding gray-bg">
  <div class="container">
    <div class="section-header text-center">
      <h2>Find the Best <span>Car For You</span></h2>
      <p>Experience the convenience of seamless car rental with GoPool. Choose from our diverse fleet of vehicles for any occasion, and enjoy competitive rates, flexible booking, and top-notch customer service. Rent with us today and hit the road with confidence!</p>
    </div>
    <div class="row"> 
      
      <!-- Nav tabs -->
      <div class="recent-tab">
        <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" class="active"><a href="#resentnewcar" role="tab" data-toggle="tab">New Car</a></li>
        </ul>
      </div>
      <!-- Recently Listed New Cars -->
      <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="resentnewcar">
          <?php
          $vehicleDAO = new VehicleDAO($dbh);
          $vehicles = $vehicleDAO->getVehicles(); // fetch vehicles

          if (empty($vehicles)) {
              echo "No vehicles available.";
          } else {
              foreach ($vehicles as $vehicle) {
                  $vehicle_id = $vehicle['id'];

                  // SQL query to calculate the average rating
                  $sql = 'SELECT AVG(Rating) as average_rating, COUNT(*) as rating_count FROM tblrating WHERE VehicleId = :vehicle_id';
$stmt = $dbh->prepare($sql);
$stmt->execute(['vehicle_id' => $vehicle_id]);
$row = $stmt->fetch();

$average_rating = $row['average_rating'];
$rating_count = $row['rating_count'];
                  ?>
                  <div class="col-list-3">
                      <div class="recent-car-list">
                          <div class="car-info-box"> 
                              <a href="vehical-details.php?vhid=<?php echo htmlentities($vehicle['id']); ?>">
                                  <img src="admin/img/vehicleimages/<?php echo htmlentities($vehicle['Vimage1']); ?>" class="img-responsive" alt="image">
                              </a>
                              <ul>
                                  <li><i class="fa fa-car" aria-hidden="true"></i><?php echo htmlentities($vehicle['FuelType']); ?></li>
                                  <li><i class="fa fa-user" aria-hidden="true"></i><?php echo htmlentities($vehicle['SeatingCapacity']); ?> seats</li>
                                  <li><i class="fa fa-location-arrow" aria-hidden="true"></i><?php echo htmlentities($vehicle['AvailableDistrict']); ?> </li>
                                  <li>⭐<?php echo  round($average_rating, 2) ." /5";?>(<?php echo $rating_count?>)</li>
                              </ul>
                          </div>
                          <div class="car-title-m">
                              <h6><a href="vehical-details.php?vhid=<?php echo htmlentities($vehicle['id']); ?>"> <?php echo htmlentities($vehicle['VehiclesTitle']); ?></a></h6>
                              <span class="price">LKR <?php echo htmlentities($vehicle['PricePerDay']); ?> /Day</span> 
                          </div>
                          <div class="inventory_info_m">
                              <p><?php echo htmlentities(substr($vehicle['VehiclesOverview'], 0, 70)); ?></p>
                          </div>
                      </div>
                  </div>
                  <?php
              }
          }
          ?>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- /Recent Cars --> 

<!-- Fun Facts -->
<?php 
$sql2 ="SELECT id from tblbooking ";
$query2= $dbh -> prepare($sql2);
$query2->execute();
$results2=$query2->fetchAll(PDO::FETCH_OBJ);
$bookings=$query2->rowCount();
?>

<?php
$sql1 ="SELECT id from tblvehicles ";
$query1 = $dbh -> prepare($sql1);;
$query1->execute();
$results1=$query1->fetchAll(PDO::FETCH_OBJ);
$totalvehicle=$query1->rowCount();
?>
<section class="fun-facts-section">
  <div class="container div_zindex">
    <div class="row">
      <div class="col-lg-3 col-xs-6 col-sm-3">
        <div class="fun-facts-m">
          <div class="cell">
            <h2><i class="fa fa-road" aria-hidden="true"></i><?php echo htmlentities($bookings);?></h2>
            <p>Rental Bookings</p>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-xs-6 col-sm-3">
        <div class="fun-facts-m">
          <div class="cell">
            <h2><i class="fa fa-car" aria-hidden="true"></i><?php echo htmlentities($totalvehicle);?></h2>
            <p>Vehicles for Rental</p>
          </div>
        </div>
      </div>
      <?php 
      $sql ="SELECT ride_request_id from ride_requests ";
      $query = $dbh -> prepare($sql);
      $query->execute();
      $results=$query->fetchAll(PDO::FETCH_OBJ);
      $regusers=$query->rowCount();
      ?>
      <div class="col-lg-3 col-xs-6 col-sm-3">
        <div class="fun-facts-m">
          <div class="cell">
            <h2><i class="fa fa-group" aria-hidden="true"></i><?php echo htmlentities($regusers);?></h2>
            <p>Ride Shares</p>
          </div>
        </div>
      </div>
      <?php 
      $sql ="SELECT id from tblusers ";
      $query = $dbh -> prepare($sql);
      $query->execute();
      $results=$query->fetchAll(PDO::FETCH_OBJ);
      $regusers=$query->rowCount();
      ?>
      <div class="col-lg-3 col-xs-6 col-sm-3">
        <div class="fun-facts-m">
          <div class="cell">
            <h2><i class="fa fa-user" aria-hidden="true"></i><?php echo htmlentities($regusers);?></h2>
            <p>Registered Users</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Dark Overlay-->
  <div class="dark-overlay"></div>
</section>
<!-- /Fun Facts--> 
<!--Testimonial -->
<section class="section-padding testimonial-section parallex-bg">
  <div class="container div_zindex">
    <div class="section-header white-text text-center">
      <h2>Our Satisfied <span>Customers</span></h2>
    </div>
    <div class="row">
      <div id="testimonial-slider">
<?php 
$tid=1;
$sql = "SELECT tbltestimonial.Testimonial,tblusers.FullName from tbltestimonial join tblusers on tbltestimonial.UserEmail=tblusers.EmailId where tbltestimonial.status=:tid limit 4";
$query = $dbh -> prepare($sql);
$query->bindParam(':tid',$tid, PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{  ?>


        <div class="testimonial-m">
 
          <div class="testimonial-content">
            <div class="testimonial-heading">
               
              <h5><?php echo htmlentities($result->FullName);?></h5>
            <p><?php echo htmlentities($result->Testimonial);?></p>
          </div>
        </div>
        </div>
        <?php }} ?>
        
       
  
      </div>
    </div>
  </div>
  <!-- Dark Overlay-->
  <div class="dark-overlay"></div>
</section>
<!-- /Testimonial--> 


<!--Footer -->
<?php include('includes/footer.php');?>
<!-- /Footer--> 

<!--Back to top-->
<div id="back-top" class="back-top"> 
  <a href="#top"><i class="fa fa-angle-up" aria-hidden="true"></i> </a> 
</div>
<!--/Back to top--> 

<!--Login-Form -->
<?php include('includes/login.php');?>
<!--/Login-Form --> 

<!--Register-Form -->
<?php include('includes/registration.php');?>

<!--/Register-Form --> 

<!--Forgot-password-Form -->
<?php include('includes/forgotpassword.php');?>
<!--/Forgot-password-Form --> 

<!-- Scripts --> 
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script> 
<script src="assets/js/interface.js"></script> 
<!--Switcher-->
<script src="assets/switcher/js/switcher.js"></script>
<!--bootstrap-slider-JS--> 
<script src="assets/js/bootstrap-slider.min.js"></script> 
<!--Slider-JS--> 
<script src="assets/js/slick.min.js"></script> 
<script src="assets/js/owl.carousel.min.js"></script>

</body>
</html>
