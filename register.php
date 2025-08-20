<?php
// DB connection
$conn = new mysqli("localhost", "root", "", "bus_ticket_booking");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Getting data from form
$email = $_POST['email'];
$username = $_POST['username'];
$full_name = $_POST['full_name'];
$password = $_POST['password'];
$gender = $_POST['gender'];
$user_type = $_POST['user_type'];
$address = $_POST['address'];
$mobile = $_POST['mobile'];


// Prepare SQL with backticks for columns with spaces
$stmt = $conn->prepare("INSERT INTO users (email, username, full_name, password, gender, user_type, address, mobile) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssss", $email, $username, $full_name, $password, $gender, $user_type, $address, $mobile);

// Execute
if ($stmt->execute()) {
   header("Location: dashboard.html"); // Change to dashboard.php if needed
    exit();
} else {
    echo "❌ Error: " . $stmt->error;
}

// Close
$stmt->close();
$conn->close();
?>
