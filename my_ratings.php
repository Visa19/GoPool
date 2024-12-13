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

$driverName = $_SESSION['fname'];

// Fetch the ratings and reviews for the driver
$sql = "SELECT Rating, review, postedby FROM tblrating WHERE DriverName = :driverName";
$query = $dbh->prepare($sql);
$query->execute([':driverName' => $driverName]);
$ratings = $query->fetchAll(PDO::FETCH_OBJ);

// Calculate average rating and the number of people who rated
$sql_avg = "SELECT AVG(Rating) as averageRating, COUNT(Rating) as ratingCount FROM tblrating WHERE DriverName = :driverName";
$query_avg = $dbh->prepare($sql_avg);
$query_avg->execute([':driverName' => $driverName]);
$result_avg = $query_avg->fetch(PDO::FETCH_OBJ);
$averageRating = $result_avg->averageRating;
$ratingCount = $result_avg->ratingCount;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Ratings and Reviews</title>
    <title> GoPool | My Ratings</title>
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
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/images/favicon-icon/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/images/favicon-icon/apple-touch-icon-114-precomposed.html">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/images/favicon-icon/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="assets/images/favicon-icon/apple-touch-icon-57-precomposed.png">
<link rel="shortcut icon" href="assets/images/favicon-icon/favicon.png">
<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900" rel="stylesheet"> 
  <style>
     
       
        h1 {
            color: #333;
        }
        .rating-summary {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .rating-summary h2 {
            color: #333;
            margin: 0;
        }
        .reviews {
            list-style-type: none;
            padding: 0;
        }
        .review-item {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 10px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
        .review-item strong {
            color: #555;
        }
         .star-rating {
            color: gold;
            font-size: 24px; /* Increased star size */
        }
    </style>
</head>
<body>
    <!--Header-->
<?php include('includes/header.php');?>
<!-- /Header --> 
<!--Page Header-->
<section class="page-header profile_page">
  <div class="container">
    <div class="page-header_wrap">
      <div class="page-heading">
        <h1>My Ratings & Reviews</h1>
      </div>
     
    </div>
  </div>
  <!-- Dark Overlay-->
  <div class="dark-overlay"></div>
</section>
<!-- /Page Header--> 
    <div class="container">
       
        <br>
        <br>
        <div class="rating-summary">
            <h4>Average Rating: <?php echo number_format($averageRating, 2); ?>⭐</h4>
            <h4>Number of People Rated: <?php echo $ratingCount; ?></h4>
        </div>

        <h4>More Details:</h4>
        <ul class="reviews">
            <?php foreach ($ratings as $rating): ?>
                <li class="review-item">
                    <strong>Rating:</strong>  <span class="star-rating">
                          
                            <?php for ($i = 0; $i < $rating->Rating; $i++): ?>
                                &#9733;
                            <?php endfor; ?>
                            <?php for ($i = $rating->Rating; $i < 5; $i++): ?>
                                &#9734;
                            <?php endfor; ?>
                        </span><br>
                    <strong>Review:</strong> <?php echo $rating->review; ?><br>
                    <strong>Posted By:</strong> <?php echo $rating->postedby; ?><br>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<br>
<br>
<!--Footer -->
<?php include('includes/footer.php');?>
<!-- /Footer--> 
</body>
</html>
