<?php
include 'db_connection.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch all rooms
$sql = "SELECT * FROM rooms";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rooms</title>
    <link rel="stylesheet" href="test/user.css">
</head>
<body>
    <style>
        /* Card Container */
.card-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px; /* Space between cards */
    justify-content: center; /* Center cards horizontally */
    padding: 20px; /* Padding around the container */
}

/* Individual Card */
.card {
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    overflow: hidden;
    width: 300px; /* Fixed width for each card */
    margin: 10px; /* Margin around each card */
    transition: transform 0.3s, box-shadow 0.3s; /* Smooth transition for hover effects */
}

/* Card Image */
.card-img {
    width: 100%;
    height: 200px;
    object-fit: cover; /* Maintain aspect ratio */
}

/* Card Body */
.card-body {
    padding: 15px;
}

/* Card Title */
.card-title {
    font-size: 1.5em;
    color: #333;
    margin: 10px 0;
    font-weight: bold;
}

/* Card Description */
.card-description {
    font-size: 1em;
    color: #666;
    margin-bottom: 10px;
}

/* Card Price */
.card-price {
    font-size: 1.2em;
    color: #007bff;
    margin: 10px 0;
}

/* Hover Effects */
.card:hover {
    transform: scale(1.05); /* Slightly increase size */
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3); /* Enhanced shadow */
}
.button {
  background-color: lime;  
  border-radius: 5px;
  color: white;
  padding: .5em;
  text-decoration: none;
}

.button:focus,
.button:hover {
  background-color: blue;
  color: White;
}

    </style>
    <header class="bg-dark">
        <nav id="navbar">
            <div class="container">
                <h1 class="logo"><a href="index.php" class="logo">Room Booking System</a></h1>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="rooms.php" class="active">Rooms</a></li>
                        <li><a href="book_room.php">Book a Room</a></li>
                        <li><a href="booking_history.php">Booking History</a></li>
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
            <h1><span class="text-primary">Our Rooms</span></h1>
            <p class="lead">Explore the available rooms for your needs.</p>
        </div>
    </section>

    <section id="room-cards" class="bg-light">
        <div class="container">
            <h2>Available Rooms</h2>
            <div class="card-container">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="card">
                        <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" class="card-img">
                        <div class="card-body">
                            <h3 class="card-title"><?php echo htmlspecialchars($row['name']); ?></h3>
                            <p class="card-description"><?php echo htmlspecialchars($row['description']); ?></p>
                            <p class="card-price">Price: $<?php echo htmlspecialchars($row['price']); ?> per hour</p>
                            <a href="available_rooms.php" class="button js-button" role="button">Check Availability</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <footer id="main-footer">
        <p>Room Booking System &copy; 2024, All Rights Reserved.</p>
    </footer>
</body>
</html>
