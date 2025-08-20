<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1; // TEMPORARY for testing
}

$user_id = $_SESSION['user_id'];

$host = "localhost";
$username = "root";
$password = "";
$database = "bus_ticket_booking";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die(json_encode([])); // Connection failed, return an empty array
}

// Fetch notifications
$sql = "SELECT * FROM ticket_notifications WHERE user_id = ? ORDER BY update_time DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$notifications = [];
while ($row = $result->fetch_assoc()) {
    $notifications[] = $row; // Add each notification to the array
}

$stmt->close();
$conn->close();

// Return notifications as JSON
echo json_encode($notifications);
?>
