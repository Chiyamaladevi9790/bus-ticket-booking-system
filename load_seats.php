<?php
$mysqli = new mysqli("localhost", "root", "", "bus_ticket_booking");

if ($mysqli->connect_error) {
    die("DB connection failed: " . $mysqli->connect_error);
}

// Fixed table name
$result = $mysqli->query("SELECT seat_number FROM seat_bookings WHERE status = 'booked'");

$seats = [];
while ($row = $result->fetch_assoc()) {
    $seats[] = $row['seat_number'];
}

echo json_encode($seats);
?>
