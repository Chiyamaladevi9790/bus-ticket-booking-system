<?php
$mysqli = new mysqli("localhost", "root", "", "bus_ticket_booking");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['seats']) && isset($_POST['user_id'])) {
    $seats = explode(",", $_POST['seats']);
    $user_id = intval($_POST['user_id']);
    $status = "booked";
    $now = date("Y-m-d H:i:s");

    foreach ($seats as $seat) {
        $seat = intval(trim($seat));

        // Corrected variable from $conn to $mysqli
        $check = $mysqli->prepare("SELECT * FROM seat_bookings WHERE seat_number = ?");
        $check->bind_param("i", $seat);
        $check->execute();
        $check_result = $check->get_result();

        if ($check_result->num_rows == 0) {
            // Also corrected table name from booked_seats to seat_bookings
            $stmt = $mysqli->prepare("INSERT INTO seat_bookings (user_id, seat_number, status, booked_at) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiss", $user_id, $seat, $status, $now);
            $stmt->execute();
        }
    }

    echo "success";
} else {
    echo "Invalid request";
}
?>
