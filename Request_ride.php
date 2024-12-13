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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_ride'])) {
    $ride_msg = htmlspecialchars($_POST['dri_message'], ENT_QUOTES, 'UTF-8');
    $start_place = htmlspecialchars($_POST['start_place'], ENT_QUOTES, 'UTF-8');
    $end_place = htmlspecialchars($_POST['end_place'], ENT_QUOTES, 'UTF-8');
    $date_of_travel = htmlspecialchars($_POST['date_of_travel'], ENT_QUOTES, 'UTF-8');
    $time_of_travel = htmlspecialchars($_POST['time_of_travel'], ENT_QUOTES, 'UTF-8');
    $postedby = htmlspecialchars($_POST['postedby'], ENT_QUOTES, 'UTF-8');
    $capacity = htmlspecialchars($_POST['capacity'], ENT_QUOTES, 'UTF-8');
    $ride_id = htmlspecialchars($_POST['ride_id'], ENT_QUOTES, 'UTF-8');
    $cost = htmlspecialchars($_POST['cost_person'], ENT_QUOTES, 'UTF-8');
}

$message = null;
if (isset($_GET["status"])) {
    $status = $_GET["status"];
    if ($status == 0) {
        $message = "<h3 class='text-success' style='color: green; font-size: 20px;'>Your Ride Request Has Been Submitted Successfully.</h3>";
    } else {
        $message = "<h3 class='text-danger' style='color: red; font-size: 20px;'>Ride Request Failed.</h3>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Request Ride</title>
  <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBjouAs7Oot1Awblj7d_9TOf6dUrsBo2ow&callback=initMap"></script>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      display: flex;
      height: 100vh;
      overflow: hidden;
    }
    .container {
      display: flex;
      width: 100%;
      height: 100%;
    }
    .left-section {
      width: 50%;
      padding: 20px;
      box-sizing: border-box;
      background-color: #f5f5f5;
      border-right: 1px solid #ddd;
      overflow-y: auto;
    }
    .right-section {
      width: 50%;
      padding: 20px;
      box-sizing: border-box;
      height: 100%;
      position: relative;
    }
    .map {
      width: 100%;
      height: 100%;
    }
    .section-title {
      font-size: 1.5em;
      margin-bottom: 15px;
      color: #333;
    }
    .ride-details {
      margin-bottom: 20px;
      padding: 15px;
      background-color: #fff;
      border-radius: 5px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .input-field {
      margin-bottom: 15px;
    }
    .input-field label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
    }
    .input-field input, .input-field textarea {
      width: 100%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 5px;
    }
    .input-field textarea {
      resize: vertical;
    }
    .button {
      display: inline-block;
      padding: 10px 15px;
      background-color: #007BFF;
      color: #fff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      text-align: center;
      text-decoration: none;
    }
    .button:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="left-section">
      <div class="ride-details">
          <?php echo $message;?>
        <h2 class="section-title">Ride Details</h2>
        <p><strong>From:</strong> <?php echo htmlspecialchars($start_place, ENT_QUOTES, 'UTF-8'); ?></p>
        <p><strong>To:</strong> <?php echo htmlspecialchars($end_place, ENT_QUOTES, 'UTF-8'); ?></p>
        <p><strong>Date:</strong> <?php echo htmlspecialchars($date_of_travel, ENT_QUOTES, 'UTF-8'); ?></p>
        <p><strong>Time:</strong> <?php echo htmlspecialchars($time_of_travel, ENT_QUOTES, 'UTF-8'); ?></p>
        <p><strong>Driver:</strong> <?php echo htmlspecialchars($postedby, ENT_QUOTES, 'UTF-8'); ?></p>
        <p><strong>Available Seats:</strong> <?php echo htmlspecialchars($capacity, ENT_QUOTES, 'UTF-8'); ?></p>
         <p><strong>Cost Per Person:</strong> <?php echo htmlspecialchars($cost, ENT_QUOTES, 'UTF-8'); ?></p>
        <p><strong>Message:</strong> <?php echo htmlspecialchars($ride_msg, ENT_QUOTES, 'UTF-8'); ?></p>
      </div>
      <form method="POST" action="submit_request.php">
        <h2 class="section-title">Request For Ride</h2>
        <div class="input-field">
          <label for="pickup_point">Pick Up Point (Click On Map):</label>
          <input type="text" id="pickup_point" name="pickup_point" required readonly>
        </div>
        <div class="input-field">
          <label for="message">Message:</label>
          <textarea id="message" name="message" rows="4" required></textarea>
        </div>
        <div class="input-field">
          <label for="number_of_seats">Number of Seats:</label>
          <input type="number" id="number_of_seats" name="number_of_seats" min="1" value="1" required>
        </div>
        <input type="hidden" name="start_place" value="<?php echo htmlspecialchars($start_place, ENT_QUOTES, 'UTF-8'); ?>">
        <input type="hidden" name="end_place" value="<?php echo htmlspecialchars($end_place, ENT_QUOTES, 'UTF-8'); ?>">
        <input type="hidden" name="date_of_travel" value="<?php echo htmlspecialchars($date_of_travel, ENT_QUOTES, 'UTF-8'); ?>">
        <input type="hidden" name="time_of_travel" value="<?php echo htmlspecialchars($time_of_travel, ENT_QUOTES, 'UTF-8'); ?>">
        <input type="hidden" name="postedby" value="<?php echo htmlspecialchars($postedby, ENT_QUOTES, 'UTF-8'); ?>">
        <input type="hidden" name="ride_id" value="<?php echo htmlspecialchars($ride_id, ENT_QUOTES, 'UTF-8'); ?>">
         <input type="hidden" name="cost_person" value="<?php echo htmlspecialchars($cost, ENT_QUOTES, 'UTF-8'); ?>">
        <button type="submit" name="request_ride" class="button">Submit Request</button>
      </form>
    </div>
    <div class="right-section">
      <div id="map" class="map"></div>
    </div>
  </div>

 <script>
  // Define the initMap function
  function initMap() {
    const startPlace = '<?php echo htmlspecialchars($start_place, ENT_QUOTES, 'UTF-8'); ?>';
    const endPlace = '<?php echo htmlspecialchars($end_place, ENT_QUOTES, 'UTF-8'); ?>';

    if (!startPlace || !endPlace) {
      console.error('Start Place or End Place is missing.');
      return;
    }

    const geocoder = new google.maps.Geocoder();
    const map = new google.maps.Map(document.getElementById('map'), {
      zoom: 12,
      center: { lat: 0, lng: 0 }, // Default center, will be updated later
    });

    const directionsService = new google.maps.DirectionsService();
    const directionsRenderer = new google.maps.DirectionsRenderer();
    directionsRenderer.setMap(map);

    let pickupMarker = null;

    // Geocode start place
    geocoder.geocode({ address: startPlace }, (startResults, status) => {
      if (status === 'OK') {
        const startLocation = startResults[0].geometry.location;

        // Geocode end place
        geocoder.geocode({ address: endPlace }, (endResults, status) => {
          if (status === 'OK') {
            const endLocation = endResults[0].geometry.location;

            // Set the map center to the start location
            map.setCenter(startLocation);

            // Set up directions request
            directionsService.route({
              origin: startLocation,
              destination: endLocation,
              travelMode: google.maps.TravelMode.DRIVING,
            }, (result, status) => {
              if (status === 'OK') {
                directionsRenderer.setDirections(result);
              } else {
                console.error('Directions request failed due to ' + status);
              }
            });

            // Add a click listener to the map for pickup point selection
            map.addListener('click', (event) => {
              const clickLatLng = event.latLng;

              // Remove existing pickup marker if any
              if (pickupMarker) {
                pickupMarker.setMap(null);
              }

              // Add a new pickup marker
              pickupMarker = new google.maps.Marker({
                position: clickLatLng,
                map: map,
                title: 'Pick Up Point',
              });

              // Reverse geocode the clicked location
              geocoder.geocode({ location: clickLatLng }, (results, status) => {
                if (status === 'OK') {
                  if (results[0]) {
                    const pickupAddress = results[0].formatted_address;

                    // Set the pickup point value in the form
                    document.getElementById('pickup_point').value = pickupAddress;

                    // Show info window with details
                    const infoWindow = new google.maps.InfoWindow({
                      content: `<p>Pickup Point: ${pickupAddress}</p><p>New Pickup Point Can Be Selected By Clicking On Map.</p>`,
                    });

                    infoWindow.open(map, pickupMarker);
                  } else {
                    console.error('No results found');
                  }
                } else {
                  console.error('Geocoder failed due to: ' + status);
                }
              });
            });

          } else {
            console.error('Geocode was not successful for the end place due to: ' + status);
          }
        });

      } else {
        console.error('Geocode was not successful for the start place due to: ' + status);
      }
    });
  }
</script>

</body>
</html>
