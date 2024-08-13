<?php
include 'db_connection.php';
session_start();
if ($_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Handle form submission to add or update time slots
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $room_id = $_POST['room_id'];
    $date = $_POST['date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $status = $_POST['status'];

    $sql = "INSERT INTO time_slots (room_id, date, start_time, end_time, status) 
            VALUES ('$room_id', '$date', '$start_time', '$end_time', '$status') 
            ON DUPLICATE KEY UPDATE status='$status'";

    if ($conn->query($sql) === TRUE) {
        echo "<p class='success'>Time slot updated successfully.</p>";
    } else {
        echo "<p class='error'>Error: " . $conn->error . "</p>";
    }
}

// Fetch available rooms for dropdown
$rooms_sql = "SELECT * FROM rooms";
$rooms_result = $conn->query($rooms_sql);

if (!$rooms_result) {
    echo "<p class='error'>Error fetching rooms: " . $conn->error . "</p>";
}

// Fetch existing time slots for display or editing
$slots_sql = "SELECT * FROM time_slots";
$slots_result = $conn->query($slots_sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Time Slots</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        h1, h2 {
            text-align: center;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        form {
            margin-bottom: 20px;
            background:#fff;
            padding: 20px;
        }
        form input, form select {
            padding: 10px;
            margin: 5px 0;
            border-radius: 4px;
            border: 1px solid #ddd;
            width: 100%;
            box-sizing: border-box;
        }
        form input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        form input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .success {
            color: green;
            text-align: center;
        }
        .error {
            color: red;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        th {
            background-color: #007bff;
            color: white;
        }
        
    </style>
</head>
<body>
    <input type="checkbox" id="nav-toggle">
    <div class="sidebar">
        <div class="sidebar-brand">
            <h2><span><i class="fab fa-accusoft"></i></span><span id="kleenpulse">ROOM BOOKING</span></h2>
        </div>
        <div class="sidebar-menu">
            <ul>
                <li><a href="admin_dashboard.php"><span class="fas fa-cubes"></span><span>Dashboard</span></a></li>
                <li><a href="manage_rooms.php"><span class="fas fa-users"></span><span>Manage Rooms</span></a></li>
                <li><a href="manage_users.php"><span class="fas fa-clipboard-list"></span><span>Manage Users</span></a></li>
                <li><a href="manage_bookings.php"><span class="fas fa-shopping-bag"></span><span>Manage Bookings</span></a></li>
                <li><a href="manage_time_slots.php" class="active"><span class="fas fa-receipt"></span><span>Manage Time Slots</span></a></li>
                <li><a href="manage_calendar.php"><span class="fas fa-calendar-alt"></span><span>Manage Calendar</span></a></li>
                <li><a href="logout.php"><span class="fa fa-user-circle"></span><span>Log Out</span></a></li>
            </ul>
        </div>
    </div>
    <div class="main-wrapper">
        <div class="main-content">
            <header>
                <h2 class="heading" id="dashboard">Manage Time Slots</h2>
                <label for="nav-toggle"><span class="fas fa-bars"></span></label>
                <div class="search">
                    <div class="search-rotate"><div class="icon"></div></div>
                    <div class="input">
                        <input type="text" placeholder="search" id="mysearch" autocomplete="off" onkeydown="display(this)">
                    </div>
                </div>
                <div class="user-wrapper">
                    <img src="logo.png" alt="">
                    <div>
                        <h4>ROOM</h4>
                        <b>Bookings</b></small>
                    </div>
                </div>
            </header>
            <main>
                <div class="container">
                    <form method="POST" action="manage_time_slots.php" style="color:black;">
                        <h1> Create Slots </h1>
                        <label for="room_id">Room:</label>
                        <select name="room_id" id="room_id" required>
                            <?php
                            while ($room = $rooms_result->fetch_assoc()) {
                                echo "<option value='{$room['id']}'>{$room['name']}</option>";
                            }
                            ?>
                        </select><br>
                        <label for="date">Date:</label>
                        <input type="date" id="date" name="date" required><br>
                        <label for="start_time">Start Time:</label>
                        <input type="time" id="start_time" name="start_time" required><br>
                        <label for="end_time">End Time:</label>
                        <input type="time" id="end_time" name="end_time" required><br>
                        <label for="status">Status:</label>
                        <select name="status" id="status">
                            <option value="available">Available</option>
                            <option value="booked">Booked</option>
                        </select><br>
                        <input type="submit" value="Save Time Slot">
                    </form>
                    <h2>Existing Time Slots</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Room ID</th>
                                <th>Date</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($row = $slots_result->fetch_assoc()) {
                                echo "<tr>
                                        <td>{$row['room_id']}</td>
                                        <td>{$row['date']}</td>
                                        <td>{$row['start_time']}</td>
                                        <td>{$row['end_time']}</td>
                                        <td>{$row['status']}</td>
                                      </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </main>
            <div class="footer">
                <div class="word">
                <p>Room Booking <span id="hrt"><i class="far fa-heart"></i></span> | Agency - 2024 <span id="full-year"></span></p>
                </div>
            </div>
        </div>
    </div>
    
    <script src="script.js"></script>
</body>
</html>
