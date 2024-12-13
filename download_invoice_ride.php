<?php
session_start();
error_reporting(0);
require_once './classes/DbConnector.php';
require_once 'fpdf.php';  // Ensure this path points to your FPDF library

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

    // Fetch ride request details from the database
    $query = "SELECT * FROM ride_requests WHERE ride_request_id = :ride_request_id";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':ride_request_id', $rideRequestId, PDO::PARAM_INT);
    $stmt->execute();
    $rideDetails = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$rideDetails) {
        echo "Ride request not found.";
        exit;
    }

    // Create instance of FPDF
    $pdf = new FPDF();
    $pdf->AddPage();

    // Add company logo
    $pdf->Image('assets/images/gopool_logo.png', 10, 10, 30);

    // Invoice Title
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 20, 'RIDE-SHARE PAYMENT INVOICE', 0, 1, 'C');
    $pdf->Ln(10);

    // Company Info
   $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(100, 10, 'GoPool', 0, 0);
        $pdf->Cell(90, 10, 'Booking Number: ' . $bookingNumber, 0, 1, 'R');
        $pdf->Cell(100, 10, 'Somasundram Road, Anaikoddai, Jaffna', 0, 0);
        $pdf->Cell(90, 10, 'Date: ' . date('Y-m-d'), 0, 1, 'R');
        $pdf->Cell(100, 10, 'vssinfo@gmail.com', 0, 0);
        $pdf->Cell(90, 10, 'Phone: 0212255675', 0, 1, 'R');
        $pdf->Ln(10);

    // Invoice Details Section
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'Invoice Details', 0, 1, 'L');
    $pdf->SetFont('Arial', '', 12);

    // Add Table for Invoice Details
    $pdf->SetFillColor(200, 200, 200);
    $pdf->Cell(90, 10, 'Description', 1, 0, 'C', true);
    $pdf->Cell(0, 10, 'Details', 1, 1, 'C', true);

    // Table Data
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(90, 10, 'Customer Name', 1, 0);
    $pdf->Cell(0, 10, htmlentities($rideDetails['passenger_name']), 1, 1, 'L');

    $pdf->Cell(90, 10, 'Driver Name', 1, 0);
    $pdf->Cell(0, 10, htmlentities($rideDetails['driver']), 1, 1, 'L');

    $pdf->Cell(90, 10, 'Start Point', 1, 0);
    $pdf->Cell(0, 10, htmlentities($rideDetails['start_place']), 1, 1, 'L');

    $pdf->Cell(90, 10, 'End Point', 1, 0);
    $pdf->Cell(0, 10, htmlentities($rideDetails['end_place']), 1, 1, 'L');

    $pdf->Cell(90, 10, 'Pickup Point', 1, 0);
    $pdf->Cell(0, 10, htmlentities($rideDetails['pickup_point']), 1, 1, 'L');

    $pdf->Cell(90, 10, 'Cost Per Ride', 1, 0);
    $pdf->Cell(0, 10, 'LKR ' . number_format(htmlentities($rideDetails['cost']), 2), 1, 1, 'L');

    $pdf->Cell(90, 10, 'Date of Travel', 1, 0);
    $pdf->Cell(0, 10, htmlentities($rideDetails['date_of_travel']), 1, 1, 'L');

    $pdf->Cell(90, 10, 'Time of Travel', 1, 0);
    $pdf->Cell(0, 10, htmlentities($rideDetails['time_of_travel']), 1, 1, 'L');

    $pdf->Cell(90, 10, 'Payment Status', 1, 0);
    $pdf->Cell(0, 10, 'Completed', 1, 1, 'L');

    $pdf->Ln(10);

    // Footer Section
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->Cell(0, 10, '***This is a computer-generated invoice and does not require a signature.***', 0, 1, 'C');

    // Output the PDF
    $pdf->Output('D', 'Rideshare_invoice_' . $rideRequestId . '.pdf');  // 'D' means download, change to 'I' for inline display
    exit;
} else {
    echo "No ride request ID provided.";
    exit;
}
?>
