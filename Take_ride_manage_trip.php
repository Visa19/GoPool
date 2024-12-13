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

if (isset($_GET['ride_request_id'])) {
    $rideRequestId = $_GET['ride_request_id'];

    // Fetch ride request details from the database using PDO
    $query = "SELECT * FROM ride_requests WHERE ride_request_id = :ride_request_id";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':ride_request_id', $rideRequestId, PDO::PARAM_INT);
    $stmt->execute();
    $rideDetails = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$rideDetails) {
        echo "Ride request not found.";
        exit;
    }

    // Fetch payment status and other details
    $paymentStatus = $rideDetails['payment_status'];
    $bookingNumber = htmlentities($rideDetails['ride_request_id']);
    $Total = htmlentities($rideDetails['cost']);
    $driver = htmlentities($rideDetails['driver']);
    $seats = htmlentities($rideDetails['number_of_seats']);
    $grandTotal = $seats * $Total;

    // Fetch trip status from tblrides
    $tripQuery = "SELECT trip_status FROM tblrides WHERE ride_id = :ride_id";
    $tripStmt = $dbh->prepare($tripQuery);
    $tripStmt->bindParam(':ride_id', $rideDetails['ride_id'], PDO::PARAM_INT);
    $tripStmt->execute();
    $tripStatus = $tripStmt->fetchColumn();

} else {
    echo "No ride request ID provided.";
    exit;
}
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GoPool | Manage Trip</title>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css">
    <!-- Custom Style -->
    <link rel="stylesheet" href="assets/css/style.css" type="text/css">
    <link href="assets/css/font-awesome.min.css" rel="stylesheet">
    <style>
        .details-container {
            width: 50%;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .details-container h2 {
            text-align: center;
        }
        .details-container table {
            width: 100%;
            margin-bottom: 20px;
        }
        .details-container table, .details-container th, .details-container td {
            border: 1px solid #ddd;
            border-collapse: collapse;
            padding: 8px;
        }
        .details-container th {
            text-align: left;
            background-color: #f2f2f2;
        }
        .details-container .buttons {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .details-container .buttons button,
        .details-container .buttons a {
            padding: 10px 20px;
            margin: 5px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            color: #fff;
        }
        .payment-button {
            background-color:blue;
        }
        .location-button {
            background-color: #008CBA;
        }
        .success-message {
            color: green;
            font-weight: bold;
        }
        .pay-container, .invoice-container {
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }
        .pay-container a, .invoice-container a {
            margin-left: 10px;
        }
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

<div class="details-container">
    <h2>Ride Request Details</h2>
    <table>
        <tr>
            <th>Ride Booking Number</th>
            <td><?php echo htmlentities($rideDetails['ride_request_id']); ?></td>
        </tr>
        <tr>
            <th>Passenger Name</th>
            <td><?php echo htmlentities($rideDetails['passenger_name']); ?></td>
        </tr>
        <tr>
            <th>Pickup Point</th>
            <td><?php echo htmlentities($rideDetails['pickup_point']); ?></td>
        </tr>
        <tr>
            <th>Cost Per Ride</th>
            <td>LKR <?php echo htmlentities($rideDetails['cost']); ?></td>
        </tr>
        <tr>
            <th>Total To Pay</th>
            <td>LKR <?php echo htmlentities($grandTotal); ?>.00</td>
        </tr>
        <tr>
            <th>Date</th>
            <td><?php echo htmlentities($rideDetails['date_of_travel']); ?></td>
        </tr>
        <tr>
            <th>Time</th>
            <td><?php echo htmlentities($rideDetails['time_of_travel']); ?></td>
        </tr>
    </table>
    <div class="buttons">
        <?php if ($tripStatus == 'not_started'): ?>
            <button class="location-button" disabled>Driver didn't start the trip yet</button>
        <?php elseif ($tripStatus == 'in_progress'): ?>
            <button class="location-button" onclick="window.location.href='driver_tracking.php?ride_id=<?php echo urlencode($rideDetails['ride_id']); ?>'">Driver Live Location</button>
        <?php elseif ($tripStatus == 'completed'): ?>
            <a href="#give_rate" class="btn btn-xs btn-primary uppercase" data-toggle="modal" data-driver="<?php echo urlencode($rideDetails['driver']); ?>" style="background-color: blue;">Trip Ended. Give Rating for Trip</a>
        <?php endif; ?>
        <br><br>
        <div class="pay-container">
            <?php if ($paymentStatus == 'pending'): ?>
                <a href="rideshare_payment.php?amount=<?php echo urlencode($grandTotal); ?>&booking_number=<?php echo urlencode($bookingNumber); ?>&ride_id=<?php echo $rideDetails['ride_id']; ?>&driver_name=<?php echo urlencode($driver); ?>" class="btn outline btn-xs active-btn payment-button" style="background-color: whitesmoke;">Pay Now</a>
            <?php elseif ($paymentStatus == 'Completed'): ?>
                <div class="success-message">Payment Successful</div> <br>
                <a href="download_invoice_ride.php?ride_request_id=<?php echo urlencode($bookingNumber); ?>" class="btn outline btn-xs active-btn" style="background-color: lightblue;">Download Invoice</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Rating Modal -->
<div class="modal fade" id="give_rate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Give Rating For Your Driver <?php echo htmlentities($rideDetails['driver']); ?>.</h5>
        <br>
        <br>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="rating_form" method="POST" action="rideshare_rate.php">
          <div class="form-group">
            <label for="rating">Your Ratings For Ride:</label>
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
            <label for="review">Write Your Review:</label>
            <textarea class="form-control" id="review" name="review" rows="4" placeholder="Write your review here..."></textarea>
          </div>
            <input type="hidden" name="driver" value="<?php echo htmlspecialchars($rideDetails['driver']); ?>">
          <input type="hidden" name="ride_request_id" value="<?php echo urlencode($rideDetails['ride_request_id']); ?>">
          
          <button type="submit" class="btn btn-primary">Submit Rating</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Scripts -->
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/interface.js"></script>

<script>
$(document).ready(function(){
    $('#give_rate').on('show.bs.modal', function (e) {
        var driver = $(e.relatedTarget).data('driver');
        $('#rating_form').find('input[name="driver"]').val(driver);
    });
});
</script>
<?php
// Your existing PHP code for fetching ride request details goes here

  // Fetch the FullName of the logged-in user
    $useremail = $_SESSION['login'];
    $sql = "SELECT FullName FROM tblusers WHERE EmailId = :useremail";
    $query = $dbh->prepare($sql);
    $query->bindParam(':useremail', $useremail, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);
    if ($result) {
        $postedBy = $result->FullName;
    }

    // Fetch ratings and reviews from the database with additional condition for rating_type
    $ratingQuery = "SELECT * FROM tblrating WHERE postedby = :postedby AND DriverName = :driver_name AND rating_type = :rating_type";
    $ratingStmt = $dbh->prepare($ratingQuery);
    $ratingStmt->bindParam(':postedby', $postedBy, PDO::PARAM_STR);
    $ratingStmt->bindParam(':driver_name', $driver, PDO::PARAM_STR);  // Fixed driver variable
    $ratingStmt->bindValue(':rating_type', 'driver', PDO::PARAM_STR);  // Added condition for rating_type
    $ratingStmt->execute();
    $ratings = $ratingStmt->fetchAll(PDO::FETCH_ASSOC);


?>

<!-- Existing HTML and PHP for ride request details -->

<!-- Ratings and Reviews Section -->
<div class="details-container">
    <h2>Ratings and Reviews</h2>
    <?php if (count($ratings) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Driver Name</th>
                    <th>Rating</th>
                    <th>Review</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ratings as $rating): ?>
                    <tr>
                        <td><?php echo htmlentities($rating['DriverName']); ?></td>
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

</body>
</html>
