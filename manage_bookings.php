<?php
include 'db_connection.php';
session_start();

if ($_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch all bookings
$sql = "SELECT bookings.id, users.username, rooms.name AS room_name, bookings.start_time, bookings.end_time, bookings.additional_options 
        FROM bookings 
        JOIN users ON bookings.user_id = users.id 
        JOIN rooms ON bookings.room_id = rooms.id";
$result = $conn->query($sql);

if (!$result) {
    die("Error fetching bookings: " . $conn->error);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Bookings</title>
    <link rel="stylesheet" href="style.css">
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
                <li><a href="manage_bookings.php" class="active"><span class="fas fa-shopping-bag"></span><span>Manage Bookings</span></a></li>
                <li><a href="manage_time_slots.php"><span class="fas fa-receipt"></span><span>Manage Time Slots</span></a></li>
                <li><a href="manage_calendar.php"><span class="fas fa-calendar-alt"></span><span>Manage Calendar</span></a></li>
                <li><a href="logout.php"><span class="fa fa-user-circle"></span><span>Log Out</span></a></li>
            </ul>
        </div>
    </div>
    <div class="main-wrapper">
        <div class="main-content">
            <header>
                <h2 class="heading" id="dashboard">Manage Bookings</h2>
                <label for="nav-toggle"><span class="fas fa-bars"></span></label>
                <div class="search">
                    <div class="search-rotate"><div class="icon"></div></div>
                    <div class="input">
                        <input type="text" placeholder="search" id="mysearch" autocomplete="off" onkeydown="display(this)">
                    </div>
                </div>
                <div class="user-wrapper">
                    <img src="https://assets.codepen.io/3853433/internal/avatars/users/default.png?format=auto&version=1617122449&width=40&height=40" alt="">
                    <div>
                        <h4>ROOM</h4>
                        <b>Bookings</b></small>
                    </div>
                </div>
            </header>
            <main >
                <div class="container">
                    <h1>Manage Bookings</h1>
                    <h2>Booking List</h2>
                    <div class="table-responsive">
                        <table width="100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Room</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Additional Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                            <td>{$row['id']}</td>
                                            <td>{$row['username']}</td>
                                            <td>{$row['room_name']}</td>
                                            <td>{$row['start_time']}</td>
                                            <td>{$row['end_time']}</td>
                                            <td>{$row['additional_options']}</td>
                                          </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="recent-grid" style="visibility: hidden;">
                    <div class="projects">
                        <div class="card">
                            <div class="card-header">
                                
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
            </main>
            
        </div>
    </div>
   
    <script src="script.js"></script>
</body>
</html>
