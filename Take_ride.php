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

$rides = [];
$searchMessage = '';

$useremail = $_SESSION['login'];
$sql = "SELECT * FROM tblusers WHERE EmailId = :useremail";
$query = $dbh->prepare($sql);
$query->bindParam(':useremail', $useremail, PDO::PARAM_STR);
$query->execute();
$results = $query->fetchAll(PDO::FETCH_OBJ);
$cnt = 1;
if ($query->rowCount() > 0) {
    $postedby = $results[0]->FullName;  // Fetching the user's FullName
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
    $from_place = $_POST['from'];
    $to_place = $_POST['to'];
    $date_of_travel = $_POST['date'] ?? null;
    $postedby = $_SESSION['fname'];  // Ensure postedby is set correctly

    // Prepare SQL query based on whether date is provided or not
    if ($date_of_travel) {
        $sql = "SELECT * FROM tblrides WHERE start_place LIKE :from_place AND end_place LIKE :to_place AND date_of_travel = :date_of_travel AND trip_status = 'not_started' AND postedby <> :postedby";
        $query = $dbh->prepare($sql);
        $query->bindValue(':date_of_travel', $date_of_travel, PDO::PARAM_STR);
    } else {
        $sql = "SELECT * FROM tblrides WHERE start_place LIKE :from_place AND end_place LIKE :to_place AND trip_status = 'not_started' AND postedby <> :postedby";
        $query = $dbh->prepare($sql);
    }
    $query->bindValue(':from_place', '%' . $from_place . '%', PDO::PARAM_STR);
    $query->bindValue(':to_place', '%' . $to_place . '%', PDO::PARAM_STR);
    $query->bindValue(':postedby', $postedby, PDO::PARAM_STR);  // Added binding for postedby
    $query->execute();
    $rides = $query->fetchAll(PDO::FETCH_OBJ);

    if (empty($rides)) {
        $searchMessage = "<h6 class='text-danger' style='color: red; font-size: 16px;'>No matching rides found.</h6>";
    }
}




?>
<!DOCTYPE html>
<html lang="en">
  
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Search Rides</title>

  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBjouAs7Oot1Awblj7d_9TOf6dUrsBo2ow&libraries=places" async defer></script>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      display: flex;
      height: 100vh;
      margin: 0;
      background-color: #f0f0f0;
    }
    .sidebar {
      width: 700px;
      padding: 20px;
      background-color: #fff;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
      overflow-y: auto;
    }
    .map-container {
      flex: 1;
      height: 100%;
    }
    h1 {
      font-size: 24px;
      margin-bottom: 20px;
      color: #333;
    }
    label {
      display: block;
      margin-bottom: 10px;
      font-weight: bold;
      color: #555;
    }
    input, select {
      width: 100%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 5px;
      margin-bottom: 10px;
      box-sizing: border-box;
    }
    input:focus, select:focus {
      border-color: #007bff;
      outline: none;
      box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    }
    .results {
      margin-top: 10px;
      padding: 10px;
      background-color: #f9f9f9;
      border: 1px solid #ddd;
      border-radius: 5px;
    }
    .form-group {
      margin-bottom: 15px;
    }
    .btn {
      display: block;
      width: 100%;
      padding: 10px;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
      margin-top: 10px;
    }
    .btn:hover {
      background-color: #0056b3;
    }
    .ride-item {
      margin-bottom: 10px;
    }
    .ride-item p {
      margin: 5px 0;
    }
    .btn-container {
      display: flex;
      gap: 10px;
      margin-top: 10px;
    }
    .btn-details, .btn-request {
      flex: 1;
    }
  </style>
  <script>
    let map;

    function initMap() {
      map = new google.maps.Map(document.getElementById('map'), {
        zoom: 12,
        center: { lat: 6.9271, lng: 79.9585 }  // Centered on Colombo, Sri Lanka
      });

      const directionsService = new google.maps.DirectionsService();
      const directionsRenderer = new google.maps.DirectionsRenderer();
      directionsRenderer.setMap(map);

      <?php if (!empty($rides)) { ?>
        <?php foreach ($rides as $ride) { ?>
          calculateAndDisplayRoute(directionsService, directionsRenderer, '<?php echo $ride->start_place; ?>', '<?php echo $ride->end_place; ?>');
        <?php } ?>
      <?php } ?>
    }

    function calculateAndDisplayRoute(directionsService, directionsRenderer, startPlace, endPlace) {
      directionsService.route(
        {
          origin: startPlace,
          destination: endPlace,
          travelMode: google.maps.TravelMode.DRIVING
        },
        function(response, status) {
          if (status === 'OK') {
            directionsRenderer.setDirections(response);
          } else {
            console.error('Directions request failed due to ' + status);
          }
        }
      );
    }

    function initAutocomplete() {
      const fromInput = document.getElementById('from');
      const toInput = document.getElementById('to');
      
      const fromAutocomplete = new google.maps.places.Autocomplete(fromInput);
      const toAutocomplete = new google.maps.places.Autocomplete(toInput);

      fromAutocomplete.addListener('place_changed', function() {
        const place = fromAutocomplete.getPlace();
        if (!place.geometry) {
          console.log("No details available for input: '" + place.name + "'");
        }
      });

      toAutocomplete.addListener('place_changed', function() {
        const place = toAutocomplete.getPlace();
        if (!place.geometry) {
          console.log("No details available for input: '" + place.name + "'");
        }
      });
    }
  </script>
</head>

<body onload="initMap(); initAutocomplete();">
  <div class="sidebar">
    <h1>Get Ride</h1>
    <?php echo $searchMessage; ?>
    <form method="POST" action="">
      <div class="form-group">
        <label for="from">From:</label>
        <input id="from" name="from" type="text" placeholder="Enter start location" required>
      </div>
      <div class="form-group">
        <label for="to">To:</label>
        <input id="to" name="to" type="text" placeholder="Enter end location" required>
      </div>
      <div class="form-group">
        <label for="date">Date:</label>
        <input id="date" name="date" type="date">
      </div>
      <button class="btn" type="submit" name="search">Search For Ride</button>
           <script>
  document.addEventListener('DOMContentLoaded', function() {
    var currentDate = new Date();
    var dateField = document.getElementById('date');
    var timeField = document.getElementById('time');

    // Set minimum date to today
    dateField.min = currentDate.toISOString().split('T')[0];

    // Function to validate date and time
    function validateDateTime() {
      var selectedDate = new Date(dateField.value);
      var selectedTime = timeField.value.split(':');
      selectedDate.setHours(selectedTime[0]);
      selectedDate.setMinutes(selectedTime[1]);

      if (selectedDate < currentDate) {
        alert('Please select a future date and time.');
        dateField.value = ''; // Clear the date field
        timeField.value = ''; // Clear the time field
        return false;
      }
      return true;
    }

    // Validate on form submit
    var form = document.querySelector('form');
    form.addEventListener('submit', function(event) {
      if (!validateDateTime()) {
        event.preventDefault(); // Prevent form submission if validation fails
      }
    });
  });
</script>
    </form>
    <div class="results">
      <h2>Matching Rides</h2>
      <?php if (!empty($rides)) { ?>
        <?php foreach ($rides as $ride) { ?>
    <?php  

                 $driver= $ride->postedby; 
                  $sql = 'SELECT AVG(Rating) as average_rating FROM tblrating WHERE DriverName= :driver';
                  $stmt = $dbh->prepare($sql);
                  $stmt->execute(['driver' => $driver]);
                  $row = $stmt->fetch();

                  $average_rating = $row['average_rating'];
                  ?>
          <div class="ride-item">
            <p><strong>From:</strong> <?php echo htmlentities($ride->start_place); ?></p>
            <p><strong>To:</strong> <?php echo htmlentities($ride->end_place); ?></p>
            <p><strong>Date:</strong> <?php echo htmlentities($ride->date_of_travel); ?></p>
            <p><strong>Time:</strong> <?php echo htmlentities($ride->time_of_travel); ?></p>
            <p><strong>Available Seats:</strong> <?php echo htmlentities($ride->capacity); ?></p>
            <p><strong>Driver:</strong> <?php echo htmlentities($ride->postedby); ?></p>
            <p><strong>Cost Per Person:</strong> <?php echo htmlentities($ride->price_per_person); ?></p>
            <p><strong>Driver Ratings: </strong><?php echo  round($average_rating, 2) ."⭐";?></p>
            <div class="btn-container">
              <form method="POST" action="Ride_Request_Info.php" target="_blank">
                <input type="hidden" name="postedby" value="<?php echo $ride->postedby; ?>">
                <button class="btn btn-details" type="submit">More Details & Reviews</button>
              </form>
              <form method="POST" action="Request_ride.php">
                <input type="hidden" name="start_place" value="<?php echo htmlentities($ride->start_place); ?>">
                <input type="hidden" name="end_place" value="<?php echo htmlentities($ride->end_place); ?>">
                <input type="hidden" name="date_of_travel" value="<?php echo htmlentities($ride->date_of_travel); ?>">
                <input type="hidden" name="time_of_travel" value="<?php echo htmlentities($ride->time_of_travel); ?>">
                <input type="hidden" name="postedby" value="<?php echo $ride->postedby; ?>">
                <input type="hidden" name="dri_message" value="<?php echo $ride->message; ?>">
                <input type="hidden" name="capacity" value="<?php echo $ride->capacity; ?>">
                <input type="hidden" name="ride_id" value="<?php echo $ride->ride_id; ?>">
                <input type="hidden" name="cost_person" value="<?php echo $ride->price_per_person; ?>">
                
                <button class="btn btn-request" type="submit" name="request_ride" <?php echo $ride->capacity <= 0 ? 'disabled' : ''; ?>>
                  <?php echo $ride->capacity <= 0 ? 'No Seats Available' : 'Request Ride'; ?>
                </button>
              </form>
            </div>
          </div>
        <?php } ?>
      <?php } else { ?>
        <p>No rides available.</p>
      <?php } ?>
    </div>
  </div>
  <div id="map" class="map-container"></div>
</body>
</html>
