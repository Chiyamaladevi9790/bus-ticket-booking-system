<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'bus_ticket_booking'; // Replace with your DB name

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

// Collect form data
$departure = $_POST['departure'] ?? '';
$destination = $_POST['destination'] ?? '';
$journeyDate = $_POST['journeyDate'] ?? '';

// Validate input
if ($departure && $destination && $journeyDate) {
    $stmt = $conn->prepare("INSERT INTO dashboard (departure, destination, journey_date) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $departure, $destination, $journeyDate);

    if ($stmt->execute()) {
    // Build the URL with query parameters
    $queryString = http_build_query([
        'departure' => $departure,
        'destination' => $destination,
        'date' => $journeyDate
    ]);

    header("Location: map.html?" . $queryString);
    exit();
}
 else {
        echo "❌ Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "⚠️ Please fill all fields.";
}

$conn->close();
?>
