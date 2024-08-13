<?php
include 'db_connection.php';
session_start();

if ($_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch non-admin users
$sql = "SELECT id, username, email, role FROM users WHERE role != 'admin'";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users</title>
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
                <li><a href="manage_users.php" class="active"><span class="fas fa-clipboard-list"></span><span>Manage Users</span></a></li>
                <li><a href="manage_bookings.php"><span class="fas fa-shopping-bag"></span><span>Manage Bookings</span></a></li>
                <li><a href="manage_time_slots.php"><span class="fas fa-receipt"></span><span>Manage Time Slots</span></a></li>
                <li><a href="manage_calendar.php"><span class="fas fa-calendar-alt"></span><span>Manage Calendar</span></a></li>
                <li><a href="logout.php"><span class="fa fa-user-circle"></span><span>Log Out</span></a></li>
            </ul>
        </div>
    </div>
    <div class="main-wrapper">
        <div class="main-content">
            <header>
                <h2 class="heading" id="dashboard">Manage Users</h2>
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
                    <h1>Manage Users</h1>
                    <h2>User List</h2>
                    <div class="table-responsive">
                        <table width="100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                            <td>{$row['id']}</td>
                                            <td>{$row['username']}</td>
                                            <td>{$row['email']}</td>
                                            <td>{$row['role']}</td>
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
                                <h3 class="heading">Recent Bookings</h3>
                                <button>See all <span class="fas fa-arrow-right"></span></button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table width="100%">
                                        <thead>
                                            <tr>
                                                <td>Room ID</td>
                                                <td>Customer</td>
                                                <td>Period</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if ($result_recent_bookings->num_rows > 0) {
                                                while ($row = $result_recent_bookings->fetch_assoc()) {
                                                    echo "<tr>
                                                            <td>{$row['room_id']}</td>
                                                            <td>{$row['customer']}</td>
                                                            <td>{$row['start_time']} - {$row['end_time']}</td>
                                                          </tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='3'>No recent bookings found.</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
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
