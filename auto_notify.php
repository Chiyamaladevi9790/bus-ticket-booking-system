<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "bus_ticket_booking";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Script is running...<br>";

// Fetch tickets where bus will arrive in the next 30 minutes
$sql = "SELECT * FROM ticket_notifications 
        WHERE TIMESTAMPDIFF(MINUTE, NOW(), estimated_arrival) BETWEEN 0 AND 30";
$result = $conn->query($sql);

if ($result === false) {
    echo "Error in SQL query: " . $conn->error . "<br>";
} else {
    $num_rows = $result->num_rows;
    echo "Rows fetched: " . $num_rows . "<br>";
}

while ($row = $result->fetch_assoc()) {
    $user_id = $row['user_id'];
    $ticket_id = $row['ticket_id'];
    $bus_id = $row['bus_id'];
    $pickup_location = $row['pickup_location'];
    $estimated_arrival = $row['estimated_arrival'];
    $distance_km = $row['distance_km'];

    $minutes_left = round((strtotime($estimated_arrival) - time()) / 60);

    if ($minutes_left <= 30 && $minutes_left > 15) {
        $status = "Bus is 30 minutes away";
    } elseif ($minutes_left <= 15 && $minutes_left > 5) {
        $status = "Bus is 15 minutes away";
    } elseif ($minutes_left <= 5 && $minutes_left >= 0) {
        $status = "Bus is arriving soon";
    } else {
        continue; // skip if no new status
    }

    // Insert new notification
    $stmt = $conn->prepare("INSERT INTO ticket_notifications (user_id, ticket_id, bus_id, pickup_location, estimated_arrival, distance_km, status)
                            VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiissds", $user_id, $ticket_id, $bus_id, $pickup_location, $estimated_arrival, $distance_km, $status);
    $stmt->execute();
    echo "Inserted notification for ticket #$ticket_id<br>";
}

$conn->close();
?>
