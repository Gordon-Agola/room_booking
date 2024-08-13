<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Booking System</title>
    <link rel="stylesheet" href="test/user.css">
</head>
<body>
    <header class="bg-dark">
        <nav id="navbar">
            <div class="container">
                <h1 class="logo"><a href="index.php" class="logo">Room Booking System</a></h1>
                <ul>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="rooms.php">Rooms</a></li>
                        <li><a href="available_rooms.php">Book a Room</a></li>
                        <li><a href="booking_history.php">Booking History</a></li>
                        <li><a href="logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a href="index.php" class="active">Home</a></li>
                        <li><a href="login.php">Login</a></li>
                        <li><a href="register.php">Register</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>

    <section id="showcase">
        <div class="showcase-content">
            <h1><span class="text-primary">Welcome</span> to the Room Booking System</h1>
            <p class="lead">Easily find and book rooms for training, studying, and teaching.</p>
        </div>
    </section>

    <section id="home-info" class="bg-dark">
        <div class="info-image"></div>
        <div class="info-content">
            <?php if (isset($_SESSION['user_id'])): ?>
                <p>Welcome, <?php echo htmlspecialchars($_SESSION['role']); ?>!</p>
                <div class="actions">
                    
                    <a href="logout.php" class="btn">Logout</a>
                </div>
            <?php else: ?>
                <p>Please <a href="login.php" class="btn">Login</a> or <a href="register.php" class="btn">Register</a> to access more features.</p>
            <?php endif; ?>
        </div>
    </section>

    <section id="features">
        <div class="location">
            <i class="fas fa-hotel fa-3x"></i>
            <h3>Prime Location</h3>
            <p>Conveniently located in the heart of the city, our rooms are easily accessible and close to key attractions. Perfect for any event or meeting!</p>
        </div>
        <div class="meals bg-primary">
            <i class="fas fa-utensils fa-3x"></i>
            <h3>Complimentary Refreshments</h3>
            <p>Enjoy a selection of free snacks and beverages during your stay. Stay refreshed and energized with our on-the-house offerings.</p>
        </div>
        <div class="fitness">
            <i class="fas fa-chalkboard-teacher fa-3x"></i>
            <h3>Versatile Training Rooms</h3>
            <p>Our rooms are designed to cater to various training, studying, and teaching needs. Equipped with modern amenities to support productive sessions.</p>
        </div>
    </section>

    <footer id="main-footer">
        <p>Room Booking System &copy; 2024, All Rights Reserved.</p>
    </footer>
</body>
</html>
