<?php
include 'db_connection.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $room_id = $_POST['room_id'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $additional_options = $_POST['additional_options'];

    // Check for overlapping bookings
    $sql_check_overlap = "SELECT * FROM time_slots 
                          WHERE room_id = '$room_id' 
                          AND (
                              (start_time < '$end_time' AND end_time > '$start_time')
                          )";
    $result_overlap = $conn->query($sql_check_overlap);

    if ($result_overlap->num_rows > 0) {
        $error = "Error: The selected time slot is already booked.";
    } else {
        // Insert booking into bookings table
        $sql_booking = "INSERT INTO bookings (user_id, room_id, start_time, end_time, additional_options) 
                        VALUES ('$user_id', '$room_id', '$start_time', '$end_time', '$additional_options')";

        if ($conn->query($sql_booking) === TRUE) {
            // Insert time slot into time_slots table with 'booked' status
            $sql_time_slot = "INSERT INTO time_slots (room_id, start_time, end_time, status) 
                              VALUES (
                                  '$room_id', 
                                  '$start_time', 
                                  '$end_time', 
                                  'booked'
                              )";

            if ($conn->query($sql_time_slot) === TRUE) {
                $success = "Room booked and time slot updated successfully.";
            } else {
                $error = "Error updating time slot: " . $conn->error;
            }
        } else {
            $error = "Error booking room: " . $conn->error;
        }
    }
}

// Fetch available rooms
$sql_rooms = "SELECT * FROM rooms";
$result_rooms = $conn->query($sql_rooms);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Room</title>
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
    </style>
    <header class="bg-dark">
        <nav id="navbar">
            <div class="container">
                <h1 class="logo"><a href="index.php" class="logo">Room Booking System</a></h1>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="rooms.php">Rooms</a></li>
                        <li><a href="available_rooms.php" class="active">Book a Room</a></li>
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
            <h1><span class="text-primary">Book a Room</span></h1>
            <p class="lead">Secure your space for training, studying, or teaching.</p>
        </div>
    </section>

    <section id="home-info1" class="bg-light">
        <div class="info-content">
            <div class="container">
                <h2>Book Room</h2>
                <form method="POST" action="book_room.php">
                    <label for="room_id">Room:</label>
                    <select name="room_id" id="room_id" required>
                        <?php while ($row = $result_rooms->fetch_assoc()): ?>
                            <option value="<?php echo htmlspecialchars($row['id']); ?>"><?php echo htmlspecialchars($row['name']); ?></option>
                        <?php endwhile; ?>
                    </select><br>
                    <label for="start_time">Start Time:</label>
                    <input type="datetime-local" name="start_time" id="start_time" required><br>
                    <label for="end_time">End Time:</label>
                    <input type="datetime-local" name="end_time" id="end_time" required><br>
                    <label for="additional_options">Additional Options:</label>
                    <input type="text" name="additional_options" id="additional_options"><br>
                    <input type="submit" value="Book Room" class="btn">
                </form>
                <?php if (isset($error)): ?>
                    <p class="error"><?php echo htmlspecialchars($error); ?></p>
                <?php elseif (isset($success)): ?>
                    <p class="success"><?php echo htmlspecialchars($success); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <footer id="main-footer">
        <p>Room Booking System &copy; 2024, All Rights Reserved.</p>
    </footer>
</body>
</html>
