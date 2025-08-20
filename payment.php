<?php
$mysqli = new mysqli("localhost", "root", "", "bus_ticket_booking");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $bus_name        = $_POST['bus_name'] ?? null;
    $departure       = $_POST['departure'] ?? null;
    $destination     = $_POST['destination'] ?? null;
    $departure_time  = $_POST['departure_time'] ?? null;
    $arrival_time    = $_POST['arrival_time'] ?? null;
    $travel_date     = $_POST['travel_date'] ?? null;
    $selected_seats  = $_POST['selected_seats'] ?? null;
    $price_per_seat  = $_POST['price_per_seat'] ?? 0;
    $total_amount    = $_POST['total_amount'] ?? 0;
    $payment_method  = $_POST['payment_method'] ?? null;
    $upi_id          = $_POST['upi_id'] ?? null;

    $payment_status = 'pending';

    if (!$bus_name || !$departure || !$destination || !$departure_time || !$arrival_time || !$travel_date || !$selected_seats || !$payment_method) {
        die("❌ Missing required fields.");
    }

    $stmt = $mysqli->prepare("INSERT INTO bookings (
        bus_name, departure, destination, departure_time, arrival_time, travel_date,
        selected_seats, price_per_seat, total_amount, payment_method, upi_id, payment_status
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    if ($stmt) {
        $stmt->bind_param("sssssssddsss",
            $bus_name, $departure, $destination, $departure_time, $arrival_time, $travel_date,
            $selected_seats, $price_per_seat, $total_amount, $payment_method, $upi_id, $payment_status
        );

        if ($stmt->execute()) {
    $query = http_build_query([
        'busName'     => $bus_name,
        'departure'   => $departure,
        'destination' => $destination,
        'time'        => $departure_time,
        'reachTime'   => $arrival_time,
        'date'        => $travel_date,
        'price'       => $price_per_seat,
        'seats'       => $selected_seats
    ]);
    header("Location: payment.html?$query");
    exit;
} else {
    echo "❌ DB error: " . $stmt->error;
}


        $stmt->close();
    } else {
        echo "❌ Failed to prepare statement.";
    }
} else {
    echo "❌ Invalid request.";
}

$mysqli->close();
?>
