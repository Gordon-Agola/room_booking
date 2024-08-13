<?php
include 'db_connection.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch booking history for the logged-in user
$sql = "SELECT b.*, r.name as room_name, r.price FROM bookings b
        JOIN rooms r ON b.room_id = r.id
        WHERE b.user_id = '$user_id'
        ORDER BY b.start_time DESC";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking History</title>
    <link rel="stylesheet" href="test/user.css">
</head>
<body>
    <style>
        table {
        
  border-collapse: collapse;
  width: 70vw;
}
 th {
  background: #ccc;
}

th, td {
  border: 1px solid #ccc;
  padding: 8px;
}

tr:nth-child(even) {
  background: #efefef;
}

tr:hover {
  background: #d1d1d1;
}
    </style>
    <header class="bg-dark">
        <nav id="navbar">
            <div class="container">
                <h1 class="logo"><a href="index.php" class="logo">Room Booking System</a></h1>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="rooms.php">Rooms</a></li>
                        <li><a href="available_rooms.php">Book a Room</a></li>
                        <li><a href="booking_history.php" class="active">Booking History</a></li>
                        <li><a href="logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a href="login.php">Login</a></li>
                        <li><a href="register.php">Register</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>

    <section id="showcase" style="background: url('https://images.pexels.com/photos/1571460/pexels-photo-1571460.jpeg?auto=compress&cs=tinysrgb&dpr=3&h=750&w=1260') no-repeat center center/cover; height: 600px;">
        <div class="showcase-content">
            <h1><span class="text-primary">Booking History</span></h1>
            <p class="lead">Review your past room bookings here.</p>
        </div>
    </section>

    <section id="home-info1" class="bg-light">
        <div class="info-content">
            <div class="container">
                <h2 >My Booking History</h2>
                <?php if ($result->num_rows > 0): ?>
                    <table class="booking-history">
                        <thead>
                            <tr>
                                <th>Room</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Price</th>
                                <th>Additional Options</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['room_name']); ?></td>
                                    <td><?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($row['start_time']))); ?></td>
                                    <td><?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($row['end_time']))); ?></td>
                                    <td><?php echo htmlspecialchars($row['price']); ?> USD</td>
                                    <td><?php echo htmlspecialchars($row['additional_options']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>You have no booking history.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <footer id="main-footer">
        <p>Room Booking System &copy; 2024, All Rights Reserved.</p>
    </footer>
</body>
</html>
