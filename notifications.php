<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    die("❌ Please log in to view notifications.");
}

$user_id = $_SESSION['user_id'];

$host = "localhost";
$username = "root";
$password = "";
$database = "bus_ticket_booking";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

// Fetch notifications
$sql = "SELECT * FROM ticket_notifications WHERE user_id = ? ORDER BY update_time DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Notifications</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f2f2f2;
      font-family: 'Segoe UI', sans-serif;
    }
    .container {
      max-width: 700px;
      margin-top: 40px;
    }
  </style>
</head>
<body>

<div class="container">
  <h2 class="mb-4">Your Ticket Notifications</h2>
  <?php if ($result->num_rows > 0): ?>
    <ul class="list-group">
      <?php while ($row = $result->fetch_assoc()): ?>
        <li class="list-group-item">
          <strong>Ticket #<?= $row['ticket_id'] ?> | <?= $row['status'] ?></strong><br>
          Bus ID: <?= $row['bus_id'] ?><br>
          Pickup: <?= $row['pickup_location'] ?><br>
          Arrival: <?= $row['estimated_arrival'] ?><br>
          Distance: <?= $row['distance_km'] ?> km<br>
          Updated: <?= $row['update_time'] ?>
        </li>
      <?php endwhile; ?>
    </ul>
  <?php else: ?>
    <div class="alert alert-info">No notifications yet.</div>
  <?php endif; ?>
</div>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
