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

require('fpdf.php');

if(isset($_GET['booking_number'])) {
    $bookingNumber = $_GET['booking_number'];
    
    $sql = "SELECT tblvehicles.VehiclesTitle, tblvehicles.id as vid, tblbrands.BrandName, tblbooking.FromDate, tblbooking.ToDate, 
            tblbooking.message, tblvehicles.PricePerDay, DATEDIFF(tblbooking.ToDate,tblbooking.FromDate) as totaldays,
            tblpayments.transaction_id, tblpayments.payer_name 
            FROM tblbooking 
            JOIN tblvehicles ON tblbooking.VehicleId=tblvehicles.id 
            JOIN tblbrands ON tblbrands.id=tblvehicles.VehiclesBrand 
            JOIN tblpayments ON tblbooking.BookingNumber=tblpayments.service_number
            WHERE tblbooking.BookingNumber=:bookingNumber";
    $query = $dbh -> prepare($sql);
    $query->bindParam(':bookingNumber', $bookingNumber, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);

    if($result) {
        $grandTotal = $result->totaldays * $result->PricePerDay;
        
        // Create PDF
        $pdf = new FPDF();
        $pdf->AddPage();
        
        // Add company logo
        $pdf->Image('assets/images/gopool_logo.png',10,10,30);
        
        // Invoice Title
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 20, 'RENTAL PAYMENT INVOICE', 0, 1, 'C');
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

        // Booking Details Title
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'Booking Details', 0, 1);

        // Booking Details Table
        $pdf->SetFont('Arial', '', 12);
        
        $pdf->Cell(50, 10, 'Customer Name', 1);
       $pdf->Cell(140, 10, $result->payer_name, 1);
        $pdf->Ln();
        $pdf->Cell(50, 10, 'Car Name', 1);
        $pdf->Cell(140, 10, $result->VehiclesTitle, 1);
        $pdf->Ln();
        $pdf->Cell(50, 10, 'From', 1);
        $pdf->Cell(140, 10, $result->FromDate, 1);
        $pdf->Ln();
        $pdf->Cell(50, 10, 'To', 1);
        $pdf->Cell(140, 10, $result->ToDate, 1);
        $pdf->Ln();
        $pdf->Cell(50, 10, 'Total Days', 1);
        $pdf->Cell(140, 10, $result->totaldays, 1);
        $pdf->Ln();
        $pdf->Cell(50, 10, 'Price Per Day', 1);
        $pdf->Cell(140, 10, 'LKR ' . number_format($result->PricePerDay, 2), 1);
        $pdf->Ln();
        $pdf->Cell(50, 10, 'Grand Total', 1);
        $pdf->Cell(140, 10, 'LKR ' . number_format($grandTotal, 2), 1);
        $pdf->Ln(20);

        // Payment Details Title
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'Payment Details', 0, 1);

        // Payment Details Table
        $pdf->SetFont('Arial', '', 12);

        $pdf->Cell(50, 10, 'Transaction ID', 1);
        $pdf->Cell(140, 10, $result->transaction_id, 1);
        $pdf->Ln();
        $pdf->Cell(50, 10, 'Payment Method', 1);
        $pdf->Cell(140, 10, 'Online Payment', 1);
        $pdf->Ln();
        $pdf->Cell(50, 10, 'Payment Status', 1);
        $pdf->Cell(140, 10, 'Successful', 1);
        $pdf->Ln(20);

        // Computer Generated Note
        $pdf->SetFont('Arial', 'I', 10);
        $pdf->Cell(0, 10, '***This is a computer-generated invoice and does not require a signature.***', 0, 1, 'C');

        // Output the PDF
        $pdf->Output('D', 'Invoice_Rental.pdf');
    } else {
        echo "No booking found with this booking number.";
    }
} else {
    echo "No booking number provided.";
}
?>
