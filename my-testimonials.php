<?php
session_start();
error_reporting(0);
include('./classes/DbConnector.php');
include('classes/Testimonial.php');
use classes\DbConnector;
use classes\Testimonial;
try {
    $dbConnector = new DbConnector();
    $dbh = $dbConnector->getConnection();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}

if(strlen($_SESSION['login']) == 0) { 
    header('location:index.php');
} else {
    $testimonialObj = new Testimonial($dbh); // Instantiate Testimonial class

    $useremail = $_SESSION['login'];
    $userDetails = $testimonialObj->getUserDetails($useremail); // Get user details

    if (!empty($userDetails)) {
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <title>GoPool | My Testimonials </title>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css">
    <!-- Custom Style -->
    <link rel="stylesheet" href="assets/css/style.css" type="text/css">
    <!-- OWL Carousel slider -->
    <link rel="stylesheet" href="assets/css/owl.carousel.css" type="text/css">
    <link rel="stylesheet" href="assets/css/owl.transitions.css" type="text/css">
    <!-- Slick-slider -->
    <link href="assets/css/slick.css" rel="stylesheet">
    <!-- Bootstrap-slider -->
    <link href="assets/css/bootstrap-slider.min.css" rel="stylesheet">
    <!-- FontAwesome Font Style -->
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
    <!-- Google-Font -->
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900" rel="stylesheet">
</head>
<body>
<?php include('includes/colorswitcher.php'); ?>
<!-- Header -->
<?php include('includes/header.php'); ?>
<!-- Page Header -->
<section class="page-header profile_page">
    <div class="container">
        <div class="page-header_wrap">
            <div class="page-heading">
                <h1>My Testimonials</h1>
            </div>
            <ul class="coustom-breadcrumb">
                <li><a href="index.php">Home</a></li>
                <li>My Testimonials</li>
            </ul>
        </div>
    </div>
    <!-- Dark Overlay -->
    <div class="dark-overlay"></div>
</section>
<!-- /Page Header -->

<section class="user_profile inner_pages">
    <div class="container">
        <div class="user_profile_info gray-bg padding_4x4_40">
            <div class="upload_user_logo">
                <img src="assets/images/gopool_logo.png" alt="image">
            </div>

            <div class="dealer_info">
                <h5><?php echo htmlentities($userDetails->FullName); ?></h5>
                <p>
                    <?php echo htmlentities($userDetails->Address); ?><br>
                    
                </p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-3 col-sm-3">
                <?php include('includes/sidebar.php'); ?>
            </div>
            <div class="col-md-8 col-sm-8">
                <div class="profile_wrap">
                    <h5 class="uppercase underline">My Testimonials</h5>
                    <div class="my_vehicles_list">
                        <ul class="vehicle_listing">
                            <?php
                            $testimonials = $testimonialObj->getUserTestimonials($useremail);
                            if (!empty($testimonials)) {
                                foreach ($testimonials as $testimonial) {
                            ?>
                            <li>
                                <div>
                                    <p><?php echo htmlentities($testimonial->Testimonial); ?></p>
                                    <p><b>Posting Date:</b> <?php echo htmlentities($testimonial->PostingDate); ?></p>
                                </div>
                                <div class="vehicle_status">
                                    <?php if ($testimonial->status == 1) { ?>
                                        <a class="btn outline btn-xs active-btn">Active</a>
                                    <?php } else { ?>
                                        <a href="#" class="btn outline btn-xs">Waiting for approval</a>
                                    <?php } ?>
                                    <div class="clearfix"></div>
                                </div>
                            </li>
                            <?php
                                }
                            } else {
                                echo "<li>No testimonials found.</li>";
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<<!--Footer -->
<?php include('includes/footer.php');?>
<!-- /Footer--> 

<!--Back to top-->
<div id="back-top" class="back-top"> <a href="#top"><i class="fa fa-angle-up" aria-hidden="true"></i> </a> </div>

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

<?php }} ?>

