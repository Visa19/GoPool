<?php
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Start the session
session_start();

// Check if the `session_id` and `booking_number` parameters are set
if (isset($_GET['session_id']) && isset($_GET['booking_number'])) {
    $sessionId = $_GET['session_id'];
    $bookingNumber = $_GET['booking_number'];

    // Initialize Stripe
    \Stripe\Stripe::setApiKey('sk_test_51PaGz6Rt9Hrs8PLRYh5I61pYbK8972ospIyKtM6Z0Fx57CWBPh9BIhc9I3KiAglpDVmmzrX83cQk0n7NljGhXACI00lyjm126O');  // Replace with your actual test secret key

    try {
        // Retrieve the Checkout Session
        $session = \Stripe\Checkout\Session::retrieve($sessionId);
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

        // Update the payment status in the database
        $status = 'Completed';
        $sql = "UPDATE tblbooking SET payment_status=? WHERE BookingNumber=?";
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
        $service_type = 'Rental';  // Adjust based on your needs (e.g., 'Car Rental' or 'Ride')
        $service_id = $bookingNumber;  // Assuming the booking number is used as service ID for ride
        $currency = 'LKR';
        $payment_method = 'Card';
        $payment_status = 'Completed';
        $stmt = $mysqli->prepare("INSERT INTO tblpayments (payer_name, service_type, service_number, amount, currency, payment_method, transaction_id, payment_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt === false) {
            die('Prepare failed: ' . $mysqli->error);
        }
        $stmt->bind_param('ssssssss', $payer_name, $service_type, $service_id, $amountReceived, $currency, $payment_method, $transactionId, $payment_status);
        if (!$stmt->execute()) {
            die('Execute failed: ' . $stmt->error);
        }
        $stmt->close();
        $mysqli->close();

        // Email settings
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

            // Recipients
            $mail->setFrom($smtpUsername, 'GoPool');
            $mail->addAddress($useremail);  // Send email to the client

            // Content
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
                    .details {
                        background-color: #f9f9f9;
                        border-left: 4px solid #009688;
                        padding: 10px;
                        margin-bottom: 20px;
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <h1>Payment Confirmation</h1>
                    <p>Dear ' . htmlspecialchars($payer_name) . ',</p>
                    <p>Thank you for your payment of <span class="highlight">' . number_format($amountReceived, 2) . ' LKR</span>.</p>
                    <p>We are pleased to confirm that your payment has been successfully processed.</p>
                    <div class="details">
                        <p><strong>Transaction ID:</strong> ' . htmlspecialchars($transactionId) . '</p>
                        <p><strong>Date:</strong> ' . date('Y-m-d H:i:s') . '</p>
                        <p><strong>Booking Number:</strong> ' . htmlspecialchars($bookingNumber) . '</p>
                    </div>
                    <p>If you have any questions or need further assistance, feel free to contact us.</p>
                    <p>Thank you for choosing GoPool!</p>
                    <div class="footer">
                        <p>Best Regards,<br>Team GoPool</p>
                    </div>
                </div>
            </body>
            </html>';

            $mail->AltBody = 'Dear ' . htmlspecialchars($payer_name) . ', Thank you for your payment of ' . number_format($amountReceived, 2) . ' LKR. Transaction ID: ' . htmlspecialchars($transactionId) . ' Date: ' . date('Y-m-d H:i:s') . ' Booking Number: ' . htmlspecialchars($bookingNumber) . ' Your payment has been successfully processed. If you have any questions or need further assistance, feel free to contact us. Thank you for choosing GoPool! Regards, GoPool Team.';

            $mail->send();
            header('Location: my-booking.php');
            exit();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

    } catch (\Stripe\Exception\ApiErrorException $e) {
        echo 'Error retrieving the session: ' . $e->getMessage();
    }
} else {
    die('Session ID or booking number parameter missing!');
}
?>
