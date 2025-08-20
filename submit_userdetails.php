<?php
session_start();
if (!isset($_POST['user_id'])) {
    die("Invalid request");
}

$host = "localhost";
$username = "root";
$password = "";
$database = "bus_ticket_booking";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

// Get form data
$user_id = $_POST['user_id'];
$ticket_id = $_POST['ticket_id'];
$bus_id = $_POST['bus_id'];
$pickup_location = $_POST['pickup_location'];
$estimated_arrival = $_POST['estimated_arrival'];
$distance_km = $_POST['distance_km'];
$status = $_POST['status'];

$sql = "INSERT INTO ticket_notifications (user_id, ticket_id, bus_id, pickup_location, estimated_arrival, distance_km, status)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iiissds", $user_id, $ticket_id, $bus_id, $pickup_location, $estimated_arrival, $distance_km, $status);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "✅ Notification saved!";
} else {
    echo "❌ Failed to save notification.";
}

$stmt->close();
$conn->close();
?>
