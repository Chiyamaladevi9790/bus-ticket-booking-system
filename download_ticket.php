<?php
require('fpdf.php'); // Include FPDF library

// Establish database connection
$mysqli = new mysqli("localhost", "root", "", "bus_ticket_booking");

// Check the connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Get the booking ID from the query parameter
$booking_id = $_GET['booking_id'] ?? null;

// Check if a valid booking ID is provided
if ($booking_id) {
    // Prepare the SQL statement to fetch booking details
    $stmt = $mysqli->prepare("SELECT * FROM bookings WHERE id = ?");
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if any booking details were found
    if ($result->num_rows > 0) {
        $booking = $result->fetch_assoc();

        // Create the PDF document
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, "Your Bus Ticket", 0, 1, 'C');
        $pdf->Ln(10);
        $pdf->SetFont('Arial', '', 12);

        // Add booking details to the PDF
        $pdf->Cell(0, 10, "Bus Name: " . $booking['bus_name'], 0, 1);
        $pdf->Cell(0, 10, "Departure: " . $booking['departure'], 0, 1);
        $pdf->Cell(0, 10, "Destination: " . $booking['destination'], 0, 1);
        $pdf->Cell(0, 10, "Departure Time: " . $booking['departure_time'], 0, 1);
        $pdf->Cell(0, 10, "Arrival Time: " . $booking['arrival_time'], 0, 1);
        $pdf->Cell(0, 10, "Travel Date: " . $booking['travel_date'], 0, 1);
        $pdf->Cell(0, 10, "Selected Seats: " . $booking['selected_seats'], 0, 1);
        $pdf->Cell(0, 10, "Total Amount: " . $booking['total_amount'], 0, 1);
        $pdf->Ln(10);

        // Add a "Happy Journey" message
        $pdf->SetFont('Arial', 'I', 12);
        $pdf->Cell(0, 10, "\"Happy Journey with Us!\"", 0, 1, 'C');

        // Output the PDF for download
        $pdf->Output('D', 'ticket_' . $booking['id'] . '.pdf');
    } else {
        // If no booking found with the provided ID
        echo "❌ No booking found with that ID.";
    }

    $stmt->close();
} else {
    // If no booking ID is provided
    echo "❌ Invalid request.";
}

// Close the database connection
$mysqli->close();
?>
