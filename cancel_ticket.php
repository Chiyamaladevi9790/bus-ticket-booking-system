<?php
$mysqli = new mysqli("localhost", "root", "", "bus_ticket_booking");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$seat = $_POST['seat'] ?? '';
$user_id = $_POST['user_id'] ?? '';

if ($seat === '' || $user_id === '') {
    echo "❌ Invalid input. Please provide both seat number and user ID.";
    exit;
}

// Get booking info
$query = "SELECT * FROM seat_bookings WHERE seat_number = '$seat' AND user_id = '$user_id' AND status = 'booked'";
$result = $mysqli->query($query);

if ($result->num_rows === 0) {
    echo "❌ No active booking found for Seat $seat and User ID $user_id.";
    exit;
}

$row = $result->fetch_assoc();
$bookingDate = new DateTime($row['booking_date']);
$now = new DateTime();
$interval = $bookingDate->diff($now)->days;

// Cancel the booking
$cancel = $mysqli->query("UPDATE seat_bookings SET status = 'cancelled' WHERE seat_number = '$seat' AND user_id = '$user_id'");

if (!$cancel) {
    echo "❌ Cancellation failed. Please try again.";
    exit;
}

// Refund condition: within 3 days
if ($interval <= 3) {
    echo "✅ Seat $seat canceled successfully. You are eligible for a refund.";
    // You may trigger a refund mechanism here
} else {
    echo "✅ Seat $seat canceled successfully. No refund as the cancellation is beyond 3 days.";
}
?>
