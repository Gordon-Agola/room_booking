<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <h1>User Dashboard</h1>
    <nav>
        <a href="book_room.php">Book Room</a> |
        <a href="available_rooms.php">Available Rooms</a> |
        <a href="logout.php">Logout</a>
    </nav>
    <h2>Welcome, User!</h2>
    <p>Here you can book rooms and view available rooms.</p>
</body>
</html>
