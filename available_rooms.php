<?php
include 'db_connection.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    // Fetch available rooms based on the specified period
    $sql = "SELECT r.id, r.name FROM rooms r
            LEFT JOIN time_slots ts ON r.id = ts.room_id
            AND (
                (ts.start_time < '$end_time' AND ts.end_time > '$start_time')
            )
            WHERE ts.room_id IS NULL";

    $result = $conn->query($sql);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Available Rooms</title>
    <link rel="stylesheet" href="test/user.css">
</head>
<body>
<style>
     h2 {
            color: #007bff;
            margin-bottom: 20px;
            text-align: center;
        }
        form {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            background: #f4f7f8;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        form label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }
        form select, form input[type="datetime-local"], form input[type="text"] {
            width: calc(100% - 22px);
            padding: 10px;
            margin: 5px 0 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #e8eeef;
            color: #8a97a0;
        }
        form input[type="submit"] {
            background-color: #4bc970;
            color: #fff;
            border: none;
            padding: 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            margin-top: 10px;
        }
        form input[type="submit"]:hover {
            background-color: #3ac162;
        }
        .error {
            color: #dc3545;
            margin-top: 10px;
            text-align: center;
        }
        .success {
            color: #28a745;
            margin-top: 10px;
            text-align: center;
        }
        footer {
            background: #333;
            color: #fff;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
        .room-list{
            margin-bottom:70px;
        }
        .room-list li {
  -webkit-padding-start: 0.35em;
          padding-inline-start: 0.35em;
        }
        .room-list li::marker {
            content: "💡";
        }
        .room-list li .ghost::marker {
        content: "👻";
        }
        .room-list li .idea::marker {
        content: "💡";
        }

        .selector {
        letter-spacing: -0.25em;
        margin-right: 0.25em;
        }
        .button {
  background-color: Crimson;  
  border-radius: 5px;
  color: white;
  padding: .5em;
  text-decoration: none;
}

.button:focus,
.button:hover {
  background-color: FireBrick;
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
                        <li><a href="rooms.php">Rooms</a></li>
                        <li><a href="available_rooms.php">Book a Room</a></li>
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
            <h1><span class="text-primary">Available Rooms</span></h1>
            <p class="lead">Find out which rooms are available for your desired time.</p>
        </div>
    </section>

    <section id="home-info1" class="bg-light">
        <div class="info-content">
            <div class="container">
                <h2>Check Available Rooms</h2>
                <form method="POST" action="available_rooms.php">
                    <label for="start_time">Start Time:</label>
                    <input type="datetime-local" name="start_time" id="start_time" required><br>
                    <label for="end_time">End Time:</label>
                    <input type="datetime-local" name="end_time" id="end_time" required><br>
                    <input type="submit" value="Check Availability" class="btn">
                </form>

                <?php if (isset($result)): ?>
                    <div class="room-list">
                        <?php if ($result->num_rows > 0): ?>
                            <h3>Available Rooms:</h3>
                            <ul>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <li><?php echo htmlspecialchars($row['name']); ?></li>
                                <?php endwhile; ?>
                            </ul><br/><br />
                            <a href="book_room.php" class="button js-button" role="button">Book A Room</a>
                        <?php else: ?>
                            <p class="no-rooms">No rooms available for the specified period.</p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <footer id="main-footer">
        <p>Room Booking System &copy; 2024, All Rights Reserved.</p>
    </footer>
</body>
</html>
