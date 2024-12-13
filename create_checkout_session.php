<?php
require 'vendor/autoload.php';

use Stripe\StripeClient;

// Your Stripe secret key
\Stripe\Stripe::setApiKey('sk_test_51PaGz6Rt9Hrs8PLRYh5I61pYbK8972ospIyKtM6Z0Fx57CWBPh9BIhc9I3KiAglpDVmmzrX83cQk0n7NljGhXACI00lyjm126O');  // Replace with your actual test secret key

// Retrieve the amount from the query string
if (isset($_GET['amount']) && isset($_GET['booking_number'])) {
    $amount = $_GET['amount'];
    $bookingnum = $_GET['booking_number'];  // Retrieve booking number
    $amountInCents = $amount * 100;  // Convert to cents

    // Check if the amount is at least 100 LKR
    if ($amountInCents < 10000) {  // 10000 cents is 100 LKR
        die('The amount must be at least 100 LKR.');
    }

    // Create a Checkout Session
    try {
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'lkr',  // Set currency to Sri Lankan Rupees
                    'product_data' => [
                        
                        'name' => 'Total Rental Amount To Pay',
                    ],
                    'unit_amount' => $amountInCents,  // Amount in cents
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => 'http://localhost/GoPool/sucess.php?session_id={CHECKOUT_SESSION_ID}&booking_number=' . $bookingnum,  // Include session ID and booking number in success URL
            'cancel_url' => 'http://localhost/GoPool/cancel.php',
        ]);

        // Redirect to Checkout
        header("Location: " . $session->url);
        exit();
    } catch (Exception $e) {
        echo 'Error creating Checkout Session: ' . $e->getMessage();
    }
} else {
    die('Amount or booking number parameter missing!');
}
?>
