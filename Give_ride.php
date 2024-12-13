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

$useremail = $_SESSION['login'];
$sql = "SELECT * FROM tblusers WHERE EmailId = :useremail";
$query = $dbh->prepare($sql);
$query->bindParam(':useremail', $useremail, PDO::PARAM_STR);
$query->execute();
$result = $query->fetch(PDO::FETCH_OBJ);
$postedby = $result ? $result->FullName : '';

// Fetch vehicle data
$sql = "SELECT * FROM tblrideshare_vehicles WHERE PostedBy = :postedby";
$query = $dbh->prepare($sql);
$query->bindParam(':postedby', $postedby, PDO::PARAM_STR);
$query->execute();
$vehicles = $query->fetchAll(PDO::FETCH_OBJ);

// Handle form submission
$message = '';  // Initialize message variable
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $start_place = $_POST['start'];
    $end_place = $_POST['end'];
    $capacity = $_POST['capacity'];
    $messageContent = $_POST['message'];  // Changed variable name to avoid conflict
    $date_of_travel = $_POST['date'];
    $time_of_travel = $_POST['time'];
    $duration = $_POST['duration'];
    $distance = $_POST['distance'];
    $vehicle = $_POST['vehicle'];
    $cost = $_POST['cost'];

    // Validate input data
    if (empty($start_place) || empty($end_place) || empty($capacity) || empty($date_of_travel) || empty($time_of_travel) || empty($vehicle)) {
        $message = "<h6 class='text-danger' style='color: red'; font-size: 16px;>Please fill all required fields.</h6>";
    } else {
        // Insert ride details into the tblrides table
        $sql = "INSERT INTO tblrides (start_place, end_place, postedby, capacity, message, date_of_travel, time_of_travel, duration, distance,price_per_person) 
                VALUES (:start_place, :end_place, :postedby, :capacity, :message, :date_of_travel, :time_of_travel, :duration, :distance, :cost)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':start_place', $start_place, PDO::PARAM_STR);
        $query->bindParam(':end_place', $end_place, PDO::PARAM_STR);
        $query->bindParam(':postedby', $postedby, PDO::PARAM_STR);
        $query->bindParam(':capacity', $capacity, PDO::PARAM_INT);
        $query->bindParam(':message', $messageContent, PDO::PARAM_STR);  // Use $messageContent instead of $message
        $query->bindParam(':date_of_travel', $date_of_travel, PDO::PARAM_STR);
        $query->bindParam(':time_of_travel', $time_of_travel, PDO::PARAM_STR);
        $query->bindParam(':duration', $duration, PDO::PARAM_STR);
        $query->bindParam(':distance', $distance, PDO::PARAM_STR);
        $query->bindParam(':cost', $cost, PDO::PARAM_STR);
        $query->execute();
        $lastInsertId = $dbh->lastInsertId();
        if ($lastInsertId) {
             header('Location: Trips.php');
             exit();
        } else {
            $message = "<h6 class='text-danger' style='color: red'; font-size: 20px;'>Ride Failed To Post.</h6>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Give A Ride</title>
  <!-- Replace YOUR_API_KEY with your actual Google Maps API key -->
   <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBjouAs7Oot1Awblj7d_9TOf6dUrsBo2ow&libraries=places" async defer></script>
  <style>
    body {
      font-family: Arial, sans-serif;
      display: flex;
      height: 100vh;
      margin: 0;
      background-color: #f0f0f0;
    }
    .sidebar {
      width: 350px;
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
    input, select, textarea {
      width: 100%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 5px;
      margin-bottom: 10px;
      box-sizing: border-box;
    }
    input:focus, select:focus, textarea:focus {
      border-color: #007bff;
      outline: none;
      box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    }
    textarea {
      resize: vertical;
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
      color: #fff;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
      margin-top: 10px;
    }
    .btn:hover {
      background-color: #0056b3;
    }
  </style>
  <script>
    let map;

    function initMap() {
      map = new google.maps.Map(document.getElementById('map'), {
        zoom: 12,
        center: { lat: 6.9271, lng: 79.9585 }  // Centered on Colombo, Sri Lanka
      });

      var directionsService = new google.maps.DirectionsService();
      var directionsRenderer = new google.maps.DirectionsRenderer();
      directionsRenderer.setMap(map);

      var autocompleteStart = new google.maps.places.Autocomplete(document.getElementById('start'));
      var autocompleteEnd = new google.maps.places.Autocomplete(document.getElementById('end'));

      autocompleteStart.addListener('place_changed', function() {
        var startPlace = autocompleteStart.getPlace();
        if (startPlace.geometry) {
          calculateAndDisplayRoute(directionsService, directionsRenderer);
        }
      });

      autocompleteEnd.addListener('place_changed', function() {
        var endPlace = autocompleteEnd.getPlace();
        if (endPlace.geometry) {
          calculateAndDisplayRoute(directionsService, directionsRenderer);
        }
      });
    }

    function calculateAndDisplayRoute(directionsService, directionsRenderer) {
      directionsService.route(
        {
          origin: document.getElementById('start').value,
          destination: document.getElementById('end').value,
          travelMode: google.maps.TravelMode.DRIVING
        },
        function(response, status) {
          if (status === 'OK') {
            directionsRenderer.setDirections(response);

            // Extract duration and distance
            var duration = response.routes[0].legs[0].duration.text;
            var distance = response.routes[0].legs[0].distance.text;

            // Display duration and distance in fields
            document.getElementById('duration').value = duration;
            document.getElementById('distance').value = distance;
          } else {
            console.error('Directions request failed due to ' + status);
          }
        }
      );
    }
  </script>
</head>
<body onload="initMap()">
  <div class="sidebar">
    <h1>Give Ride</h1>
    <?php echo $message; ?>
    <form method="POST" action="">
      <label for="start">Start Place:</label>
      <input id="start" name="start" type="text" placeholder="Enter start location" required>
      <label for="end">End Place:</label>
      <input id="end" name="end" type="text" placeholder="Enter end location" required>
      <div class="results">
        <label for="duration">Duration:</label>
        <input id="duration" name="duration" type="text" readonly>
        <label for="distance">Distance:</label>
        <input id="distance" name="distance" type="text" readonly>
      </div>
      <div class="form-group">
        <label for="vehicle">Select Vehicle:</label>
        <select id="vehicle" name="vehicle" required>
          
          <?php foreach ($vehicles as $vehicle) { ?>
            <option value="<?php echo htmlentities($vehicle->VehiclesTitle); ?>"><?php echo htmlentities($vehicle->VehiclesTitle); ?></option>
          <?php } ?>
        </select>
      </div>
      <div class="form-group">
        <label for="capacity">Seating Capacity:</label>
        <input id="capacity" name="capacity" type="number" min="1" required>
      </div>
       <div class="form-group">
        <label for="capacity">Cost Per Person in LKR:</label>
        <input id="cost" name="cost" type="text" placeholder="10% of cost per person to GoPool..." required>
      </div>
      <div class="form-group">
        <label for="message">Message:</label>
        <textarea id="message" name="message" rows="4" placeholder="Enter your message"></textarea>
      </div>
      <div class="form-group">
        <label for="date">Date of Travel:</label>
        <input id="date" name="date" type="date" required>
      </div>
      <div class="form-group">
        <label for="time">Time of Travel:</label>
        <input id="time" name="time" type="time" required>
      </div>
      <button class="btn" type="submit" name="submit">Post Ride</button>
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
  </div>
  <div id="map" class="map-container"></div>
</body>
</html>
