<?php
require 'vendor/autoload.php';

// Set your secret key
\Stripe\Stripe::setApiKey('sk_test_51PaGz6Rt9Hrs8PLRYh5I61pYbK8972ospIyKtM6Z0Fx57CWBPh9BIhc9I3KiAglpDVmmzrX83cQk0n7NljGhXACI00lyjm126O');

// Retrieve the token ID
$token = $_POST['stripeToken'];
$email = $_POST['email'];

try {
    // Create a Customer
    $customer = \Stripe\Customer::create([
        'email' => $email,
        'source' => $token,
    ]);

    // Charge the Customer
    $charge = \Stripe\Charge::create([
        'amount' => 5000,  // Amount in cents
        'currency' => 'usd',
        'description' => 'Example charge',
        'customer' => $customer->id,
    ]);

    echo '<h1>Successfully charged $50.00!</h1>';
} catch (\Stripe\Exception\CardException $e) {
    // Display error message
    echo '<h1>Card Error: ' . $e->getError()->message . '</h1>';
} catch (\Exception $e) {
    // Display general error message
    echo '<h1>Something went wrong: ' . $e->getMessage() . '</h1>';
}
?>
