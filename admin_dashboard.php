<?php
session_start();
if ($_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

include 'db_connection.php';

// Fetch counts for dashboard cards
$sql_count_rooms = "SELECT COUNT(*) AS total_rooms FROM rooms";
$result_count_rooms = $conn->query($sql_count_rooms);
$count_rooms = $result_count_rooms->fetch_assoc()['total_rooms'];

$sql_count_users = "SELECT COUNT(*) AS total_users FROM users WHERE role != 'admin'";
$result_count_users = $conn->query($sql_count_users);
$count_users = $result_count_users->fetch_assoc()['total_users'];

$sql_count_bookings = "SELECT COUNT(*) AS total_bookings FROM bookings";
$result_count_bookings = $conn->query($sql_count_bookings);
$count_bookings = $result_count_bookings->fetch_assoc()['total_bookings'];

$sql_count_time_slots = "SELECT COUNT(*) AS total_time_slots FROM time_slots";
$result_count_time_slots = $conn->query($sql_count_time_slots);
$count_time_slots = $result_count_time_slots->fetch_assoc()['total_time_slots'];

// Fetch recent bookings
$sql_recent_bookings = "SELECT bookings.room_id, users.username AS customer, bookings.start_time, bookings.end_time
                        FROM bookings
                        JOIN users ON bookings.user_id = users.id
                        ORDER BY bookings.start_time DESC
                        LIMIT 10";
$result_recent_bookings = $conn->query($sql_recent_bookings);

// Fetch new customers (users who are not admins)
$sql_new_customers = "SELECT username, email FROM users WHERE role != 'admin' ORDER BY id DESC LIMIT 10";
$result_new_customers = $conn->query($sql_new_customers);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
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
                <li><a href="admin_dashboard.php" class="active"><span class="fas fa-cubes"></span><span>Dashboard</span></a></li>
                <li><a href="manage_rooms.php"><span class="fas fa-users"></span><span>Manage Rooms</span></a></li>
                <li><a href="manage_users.php"><span class="fas fa-clipboard-list"></span><span>Manage Users</span></a></li>
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
                <h2 class="heading" id="dashboard">Dashboard</h2>
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
                
                <div class="cards">
                    <div class="card-single">
                        <div>
                            <h1><?php echo $count_rooms; ?></h1>
                            <span>Manage Rooms</span>
                        </div>
                        <div>
                            <span class="fas fa-users"></span>
                        </div>
                    </div>
                    <div class="card-single">
                        <div>
                            <h1><?php echo $count_users; ?></h1>
                            <span>Manage Users</span>
                        </div>
                        <div>
                            <span class="fas fa-clipboard"></span>
                        </div>
                    </div>
                    <div class="card-single">
                        <div>
                            <h1><?php echo $count_bookings; ?></h1>
                            <span>Manage Bookings</span>
                        </div>
                        <div>
                            <span class="fas fa-shopping-bag"></span>
                        </div>
                    </div>
                    <div class="card-single">
                        <div>
                            <h1><?php echo $count_time_slots; ?></h1>
                            <span>Manage Time Slots</span>
                        </div>
                        <div>
                            <span class="fab fa-google-wallet"></span>
                        </div>
                    </div>
                </div>
                <div class="recent-grid">
                    <div class="projects">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="heading">Recent Bookings</h3>
                                
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
                    <div class="customers">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="heading">New Customers</h3>
                                
                            </div>
                            <div class="card-body">
                                <?php
                                if ($result_new_customers->num_rows > 0) {
                                    while ($row = $result_new_customers->fetch_assoc()) {
                                        echo "<div class='customer'>
                                                <div class='info'>
                                                    <img src='user.jpg' width='40' height='40' alt=''>
                                                    <div>
                                                        <h4>{$row['username']}</h4>
                                                        <small>{$row['email']}</small>
                                                    </div>
                                                </div>
                                                <div class='contact'>
                                                    <span class='fas fa-user-circle'></span>
                                                    <span class='fas fa-comment'></span>
                                                    <span class='fas fa-phone'></span>
                                                </div>
                                              </div>";
                                    }
                                } else {
                                    echo "<p>No new customers found.</p>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </main><br /> <br />
            
        </div>
    </div>
    
    <script src="scripts/script.js"></script>
</body>
</html>
