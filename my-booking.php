<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['login'])==0)
{ 
  header('location:index.php');
}
else {
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <title>Car Rental Portal - My Booking</title>
    <!--Bootstrap -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css">
    <!--Custome Style -->
    <link rel="stylesheet" href="assets/css/style.css" type="text/css">
    <!--OWL Carousel slider-->
    <link rel="stylesheet" href="assets/css/owl.carousel.css" type="text/css">
    <link rel="stylesheet" href="assets/css/owl.transitions.css" type="text/css">
    <!--slick-slider -->
    <link href="assets/css/slick.css" rel="stylesheet">
    <!--bootstrap-slider -->
    <link href="assets/css/bootstrap-slider.min.css" rel="stylesheet">
    <!--FontAwesome Font Style -->
    <link href="assets/css/font-awesome.min.css" rel="stylesheet">
    <!-- SWITCHER -->
    <link rel="stylesheet" id="switcher-css" type="text/css" href="assets/switcher/css/switcher.css" media="all" />
    <link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/red.css" title="red" media="all" data-default-color="true" />
    <link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/orange.css" title="orange" media="all" />
    <link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/blue.css" title="blue" media="all" />
    <link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/pink.css" title="pink" media="all" />
    <link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/green.css" title="green" media="all" />
    <link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/purple.css" title="purple" media="all" />
    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/images/favicon-icon/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/images/favicon-icon/apple-touch-icon-114-precomposed.html">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/images/favicon-icon/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="assets/images/favicon-icon/apple-touch-icon-57-precomposed.png">
    <link rel="shortcut icon" href="assets/images/favicon-icon/favicon.png">
    <!-- Google-Font-->
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900" rel="stylesheet">
    <style>
.rating {
  display: flex;
  font-size: 40px; /* Increased the size of the stars */
  justify-content: center;
  direction: ltr; /* Ensures the stars are laid out from left to right */
}

.rating input {
  display: none;
}

.rating label {
  color: #ddd;
  cursor: pointer;
  margin: 0;
}

.rating input:checked ~ label,
.rating input:hover ~ label,
.rating input:hover ~ label ~ label {
  color: #f0ad4e;
}

.rating label:hover,
.rating label:hover ~ label {
  color: #f0ad4e;
}

.rating input:checked ~ label {
  color: #f0ad4e;
}

        </style>
</head>
<body>

<!-- Start Switcher -->
<?php include('includes/colorswitcher.php');?>
<!-- /Switcher -->  

<!--Header-->
<?php include('includes/header.php');?>
<!--Page Header-->
<!-- /Header --> 

<!--Page Header-->
<section class="page-header profile_page">
  <div class="container">
    <div class="page-header_wrap">
      <div class="page-heading">
        <h1>My Booking</h1>
      </div>
      <ul class="coustom-breadcrumb">
        <li><a href="#">Home</a></li>
        <li>My Booking</li>
      </ul>
    </div>
  </div>
  <!-- Dark Overlay-->
  <div class="dark-overlay"></div>
</section>
<!-- /Page Header--> 

<?php 
$useremail=$_SESSION['login'];
$sql = "SELECT * from tblusers where EmailId=:useremail ";
$query = $dbh -> prepare($sql);
$query -> bindParam(':useremail',$useremail, PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{ ?>
<section class="user_profile inner_pages">
  <div class="container">
    <div class="user_profile_info gray-bg padding_4x4_40">
      <div class="upload_user_logo"> <img src="assets/images/gopool_logo.png" alt="image"> </div>
      <div class="dealer_info">
        <h5><?php echo htmlentities($result->FullName);?></h5>
        <p><?php echo htmlentities($result->Address);?><br>
          <?php echo htmlentities($result->City);?>&nbsp;<?php echo htmlentities($result->Country); ?></p>
      </div>
    </div>
    <div class="row">
      <div class="col-md-3 col-sm-3">
      
      </div>
      <div class="col-md-8 col-sm-8">
        <div class="profile_wrap">
          <h5 class="uppercase underline">My Bookings </h5>
          <div class="my_vehicles_list">
            <ul class="vehicle_listing">
<?php 
$useremail=$_SESSION['login'];
$sql = "SELECT tblvehicles.Vimage1 as Vimage1, tblvehicles.VehiclesTitle, tblvehicles.id as vid, tblbrands.BrandName, tblbooking.FromDate, tblbooking.ToDate, tblbooking.message, tblbooking.Status, tblbooking.payment_status, tblvehicles.PricePerDay, DATEDIFF(tblbooking.ToDate,tblbooking.FromDate) as totaldays, tblbooking.BookingNumber FROM tblbooking JOIN tblvehicles ON tblbooking.VehicleId=tblvehicles.id JOIN tblbrands ON tblbrands.id=tblvehicles.VehiclesBrand WHERE tblbooking.userEmail=:useremail ORDER BY tblbooking.id DESC";
$query = $dbh -> prepare($sql);
$query-> bindParam(':useremail', $useremail, PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{  
?>

<li>
  <h4 style="color:red">Booking Number: <?php echo htmlentities($result->BookingNumber);?></h4>
  <div class="vehicle_img"> <a href="vehical-details.php?vhid=<?php echo htmlentities($result->vid);?>"><img src="admin/img/vehicleimages/<?php echo htmlentities($result->Vimage1);?>" alt="image"></a> </div>
  <div class="vehicle_title">
    <h6><a href="vehical-details.php?vhid=<?php echo htmlentities($result->vid);?>">  <?php echo htmlentities($result->VehiclesTitle);?></a></h6>
    <p><b>From </b> <?php echo htmlentities($result->FromDate);?> <b>To </b> <?php echo htmlentities($result->ToDate);?></p>
    <div style="float: left"><p><b>Reason of Rental:</b> <?php echo htmlentities($result->message);?> </p></div>
  </div>

  <table>
    <tr>
      <th>Car Name</th>
      <th>From Date & Time</th>
      <th>To Date & Time</th>
      <th>Total Days</th>
      <th>Rent / Day</th>
    </tr>
    <tr>
      <td><?php echo htmlentities($result->VehiclesTitle);?>
      <td><?php echo htmlentities($result->FromDate);?></td>
      <td> <?php echo htmlentities($result->ToDate);?></td>
      <td><?php echo htmlentities($tds=$result->totaldays);?></td>
      <td> <?php echo htmlentities($ppd=$result->PricePerDay);?></td>
    </tr>
    <tr>
      <th colspan="4" style="text-align:center;"> Grand Total</th>
      <th><?php echo htmlentities($tds*$ppd);?></th>
      <?php $grandtotal=$tds*$ppd ;?>
      <?php $bookingNumber= $result->BookingNumber;?>
    </tr>
  </table>
  <hr />

  <?php if($result->Status==1) {
    if ($result->payment_status == 'Pending') { ?>
      <div class="vehicle_status"> <a href="create_checkout_session.php?amount=<?php echo $grandtotal; ?>&booking_number=<?php echo $bookingNumber; ?>" class="btn outline btn-xs active-btn">Pay Now</a>
      <div class="clearfix"></div>
      </div>
    <?php } elseif ($result->payment_status == 'Completed') { ?>
      <div class="vehicle_status"> 
          <p style="color: green">Payment successful!!</p>
        <a href="generate_invoice.php?booking_number=<?php echo $bookingNumber; ?>" class="btn outline btn-xs">Download Your Invoice</a>
        <br>
       <br>
        
        <!-- Give Rating Button -->
       <a href="#give_rate" class="btn btn-xs btn-primary uppercase" data-toggle="modal">Give Rating</a>
        

      <div class="clearfix"></div>
      </div>
    <?php } 
  } else if($result->Status==2) { ?>
    <div class="vehicle_status"> <a href="#" class="btn outline btn-xs">Booking Cancelled by Admin</a>
    <div class="clearfix"></div>
    </div>
  <?php } else { ?>
    <p style="color: red">Wait for Admin Approval</p>
    <div class="vehicle_status"> <a href="cancel_booking.php?booking_number=<?php echo $bookingNumber; ?>" class="btn outline btn-xs">Cancel Booking</a>
       
    <div class="clearfix"></div>
    </div>
  <?php } ?>
</li>
<?php 
}
}
else 
{ 
?>
<h5 align="center" style="color:red">No booking yet</h5>
<?php 
} 
?>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!--/my-vehicles--> 
<?php include('includes/footer.php');?>

<!-- Rating Modal -->
<div class="modal fade" id="give_rate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Give Rating For Your Rental Vehicle</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
     <form id="rating_form" method="POST" action="give_rate.php">
  <div class="form-group">
    <label for="rating">Your Ratings:</label>
    <div class="rating">
      <input type="radio" id="star5" name="rating" value="5">
      <label for="star5" title="5 stars">&#9733;</label>
      <input type="radio" id="star4" name="rating" value="4">
      <label for="star4" title="4 stars">&#9733;</label>
      <input type="radio" id="star3" name="rating" value="3">
      <label for="star3" title="3 stars">&#9733;</label>
      <input type="radio" id="star2" name="rating" value="2">
      <label for="star2" title="2 stars">&#9733;</label>
      <input type="radio" id="star1" name="rating" value="1">
      <label for="star1" title="1 star">&#9733;</label>
    </div>
  </div>
  <div class="form-group">
    <label for="review">Your Review:</label>
    <textarea class="form-control" id="review" name="review" rows="3" placeholder="Write your review here"></textarea>
  </div>
  <input type="hidden" name="veh-number" id="veh-number" value="<?php echo htmlentities($result->vid);?>">

  <button type="submit" class="btn btn-primary">Submit Feedback</button>
</form>

      </div>
    </div>
  </div>
</div>


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

<script>


  // Add hover effect for star ratings
  $('.rating input').on('change', function() {
    $(this).siblings('label').addClass('checked');
  });

  // Add hover effect for star ratings
  $('.rating label').on('mouseover', function() {
    $(this).prevAll().addBack().addClass('hover');
  }).on('mouseout', function() {
    $(this).siblings().addBack().removeClass('hover');
  });
});

$(document).ready(function() {
  $('#give_rate').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var vehNumber = button.data('veh-number'); // Extract vehicle ID from data attribute
    var bookingNumber = button.data('booking-number'); // Extract booking number from data attribute

    // Set the retrieved vehicle ID and booking number in the hidden form fields
    $('#veh-number').val(vehNumber);
    $('#booking-number').val(bookingNumber);
  });
});

</script>

</body>
</html>
<?php } } } ?>
