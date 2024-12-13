<?php
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;

// Start the session
session_start();

// Check if the `session_id`, `booking_number`, and `driver_name` parameters are set
if (isset($_GET['session_id']) && isset($_GET['booking_number']) && isset($_GET['driver_name'])) {
    $sessionId = $_GET['session_id'];
    $bookingNumber = $_GET['booking_number'];
    $driverName = $_GET['driver_name'];
    $ride_id = $_GET['ride_id'];

    // Initialize Stripe
    Stripe::setApiKey('sk_test_51PaGz6Rt9Hrs8PLRYh5I61pYbK8972ospIyKtM6Z0Fx57CWBPh9BIhc9I3KiAglpDVmmzrX83cQk0n7NljGhXACI00lyjm126O');  // Replace with your actual test secret key

    try {
        // Retrieve the Checkout Session
        $session = Session::retrieve($sessionId);
        $amountReceived = $session->amount_total / 100;  // Convert from cents to LKR
        $transactionId = $session->payment_intent;

        // Get the client's email from the session
        $useremail = $_SESSION['login'];

        // Database connection
        $mysqli = new mysqli('localhost', 'root', '', 'carrental');  // Replace with your database credentials
        if ($mysqli->connect_error) {
            die('Database connection failed: ' . $mysqli->connect_error);
        }

        // Fetch payer's full name
        $stmt = $mysqli->prepare("SELECT FullName FROM tblusers WHERE EmailId=?");
        if ($stmt === false) {
            die('Prepare failed: ' . $mysqli->error);
        }
        $stmt->bind_param('s', $useremail);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($payer_name);
        $stmt->fetch();
        $stmt->close();

        // Fetch driver's Stripe account ID and email
        $stmt = $mysqli->prepare("SELECT StripeId, EmailId FROM tblusers WHERE FullName=?");
        if ($stmt === false) {
            die('Prepare failed: ' . $mysqli->error);
        }
        $stmt->bind_param('s', $driverName);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($driverStripeAccountId, $driverEmail);
        $stmt->fetch();
        $stmt->close();

        // Update the payment status in the database
        $status = 'Completed';
        $sql = "UPDATE ride_requests SET payment_status=? WHERE ride_request_id=?";
        $stmt = $mysqli->prepare($sql);
        if ($stmt === false) {
            die('Prepare failed: ' . $mysqli->error);
        }
        $stmt->bind_param('ss', $status, $bookingNumber);
        if (!$stmt->execute()) {
            die('Execute failed: ' . $stmt->error);
        }
        $stmt->close();

        // Insert payment details into tblpayments
        $service_type = 'Ride';  // Adjust based on your needs (e.g., 'Car Rental' or 'Ride')
        $service_id = $bookingNumber;  // Assuming the booking number is used as service ID for ride
        $currency = 'LKR';
        $payment_method = 'Card';
        $payment_status = 'Completed';
        $stmt = $mysqli->prepare("INSERT INTO tblpayments (payer_name, service_type, service_number, amount, currency, payment_method, transaction_id, payment_status,ride_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt === false) {
            die('Prepare failed: ' . $mysqli->error);
        }
        $stmt->bind_param('sssssssss', $payer_name, $service_type, $service_id, $amountReceived, $currency, $payment_method, $transactionId, $payment_status, $ride_id);
        if (!$stmt->execute()) {
            die('Execute failed: ' . $stmt->error);
        }
        $stmt->close();
        $mysqli->close();

        // Calculate 90% of the amount to be sent to the driver
        $amountToSend = $amountReceived * 0.90;

        // Notify the driver without making an actual payout
        // Email settings for the client
        $smtpHost = 'smtp.gmail.com';  // Replace with your SMTP server
        $smtpUsername = 'logenthiranvisagan@gmail.com';  // Replace with your email
        $smtpPassword = 'mqaw nxds xonk okqk';  // Replace with your generated app password
        $smtpPort = 587;  // Common port for TLS; use 465 for SSL and 25 for non-encrypted

        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = $smtpHost;
            $mail->SMTPAuth   = true;
            $mail->Username   = $smtpUsername;
            $mail->Password   = $smtpPassword;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // or PHPMailer::ENCRYPTION_SMTPS for SSL
            $mail->Port       = $smtpPort;

            // Email to the client
            $mail->setFrom($smtpUsername, 'GoPool');
            $mail->addAddress($useremail);  // Send email to the client

            // Content for the client
            $mail->isHTML(true);
            $mail->Subject = 'Payment Confirmation for Your Ride';
            $mail->Body    = '
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Payment Confirmation</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        background-color: #f4f4f4;
                        color: #333;
                        padding: 20px;
                        margin: 0;
                    }
                    .container {
                        max-width: 600px;
                        margin: 0 auto;
                        background-color: #ffffff;
                        border-radius: 5px;
                        box-shadow: 0 0 10px rgba(0,0,0,0.1);
                        padding: 20px;
                    }
                    h1 {
                        color: #009688;
                        text-align: center;
                        margin-bottom: 20px;
                    }
                    p {
                        font-size: 16px;
                        line-height: 1.5;
                        margin-bottom: 10px;
                    }
                    .footer {
                        text-align: left;
                        margin-top: 20px;
                        font-size: 14px;
                        color: #777;
                    }
                    .highlight {
                        color: #009688;
                        font-weight: bold;
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <h1>Payment Confirmation for Ride Share Service</h1>
                    <p>Dear ' . $payer_name . ',</p>
                    <p>Thank you for your payment for the ride share service.</p>
                    <p><span class="highlight">Booking Number:</span> ' . $bookingNumber . '</p>
                    <p><span class="highlight">Amount Paid:</span> ' . $amountReceived . ' LKR</p>
                    <p><span class="highlight">Transaction ID:</span> ' . $transactionId . '</p>
                    <p>If you have any questions or need further assistance, feel free to contact us. Also you can contact your driver through chat in our website. </p>
                    <p>Thank you for choosing GoPool!</p>
                    <div class="footer">
                        <p>Best regards,</p>
                        <p>The GoPool Team</p>
                    </div>
                </div>
            </body>
            </html>
            ';
            $mail->send();
//            echo 'Payment confirmation email sent successfully.';

            // Send email to the driver
            $mail->clearAddresses();  // Clear previous recipient addresses
            $mail->addAddress($driverEmail);  // Send email to the driver

            $mail->Subject = 'Payment Received for Your Ride Service';
            $mail->Body    = '
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Payment Received</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        background-color: #f4f4f4;
                        color: #333;
                        padding: 20px;
                        margin: 0;
                    }
                    .container {
                        max-width: 600px;
                        margin: 0 auto;
                        background-color: #ffffff;
                        border-radius: 5px;
                        box-shadow: 0 0 10px rgba(0,0,0,0.1);
                        padding: 20px;
                    }
                    h1 {
                        color: #009688;
                        text-align: center;
                        margin-bottom: 20px;
                    }
                    p {
                        font-size: 16px;
                        line-height: 1.5;
                        margin-bottom: 10px;
                    }
                    .footer {
                        text-align: left;
                        margin-top: 20px;
                        font-size: 14px;
                        color: #777;
                    }
                    .highlight {
                        color: #009688;
                        font-weight: bold;
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <h1>Payment Received From Your Passenger</h1>
                    <p>Dear ' . $driverName . ',</p>
                    <p>We have received a payment for the ride share service you have provided.</p>
                    <p><span class="highlight">Booking number:</span> ' . $bookingNumber . '</p>
                    <p><span class="highlight">Amount to be received :</span> ' . $amountToSend . ' LKR</p
                    <p><span class="highlight">Paid passenger name :</span> ' . $payer_name . '</p>
                    <p>As we informed earlier we will be deducting 10% from the payment received per passenger for your ride.We will sent your payment to your attached bank account as soon as possible.</p>
                    <div class="footer">
                    <p>If you have any questions or need further assistance, feel free to contact us.</p>
                    <p>Thank you for choosing GoPool!</p>
                        <p>Best regards,</p>
                        <p>The GoPool Team</p>
                    </div>
                </div>
            </body>
            </html>
            ';
            $mail->send();
            echo 'Payment notification email sent successfully to the driver.';

            // Redirect to Take_ride_manage_trip.php with ride_request_id as parameter
            echo '<script>
                window.location.href = "Take_ride_manage_trip.php?ride_request_id=' . $bookingNumber . '";
            </script>';

        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
        }
    } catch (ApiErrorException $e) {
        echo 'Error retrieving the session: ' . $e->getMessage();
    }
} else {
    echo 'Invalid request.';
}
?>
