<?php
include 'db_connection.php';
session_start();

if ($_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Handle room addition
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $capacity = $_POST['capacity'];
    $price = $_POST['price'];
    $image = $_FILES['image']['name'];

    // Upload image
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

    $sql = "INSERT INTO rooms (name, description, capacity, price, image) VALUES ('$name', '$description', '$capacity', '$price', '$image')";

    if ($conn->query($sql) === TRUE) {
        echo "<p class='success-message'>Room added successfully.</p>";
    } else {
        echo "<p class='error-message'>Error: " . $conn->error . "</p>";
    }
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
    <title>Manage Rooms</title>
    <link rel="stylesheet" href="style.css">
    <style>
        form {
            margin: 10% auto 0 auto;
            padding: 30px;
            width: 400px;
            height: auto;
            overflow: hidden;
            background: white;
            border-radius: 10px;
        }

        form label {
            font-size: 14px;
            color: darkgray;
            cursor: pointer;
        }

        form label,
        form input {
            float: left;
            clear: both;
        }

        form input {
            margin: 15px 0;
            padding: 15px 10px;
            width: 100%;
            outline: none;
            border: 1px solid #bbb;
            border-radius: 20px;
            display: inline-block;
            box-sizing: border-box;
            transition: 0.2s ease all;
            color: black;
        }

        form input[type=text]:focus,
        form input[type=number]:focus {
            border-color: cornflowerblue;
        }

        input[type=submit] {
            padding: 15px 50px;
            width: auto;
            background: #1abc9c;
            border: none;
            color: white;
            cursor: pointer;
            display: inline-block;
            float: right;
            clear: right;
            transition: 0.2s ease all;
        }

        input[type=submit]:hover {
            opacity: 0.8;
        }

        input[type=submit]:active {
            opacity: 0.4;
        }
        
        .image-preview {
            max-width: 100px;
            max-height: 100px;
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
                <li><a href="manage_rooms.php" class="active"><span class="fas fa-users"></span><span>Manage Rooms</span></a></li>
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
                <h2 class="heading" id="dashboard">Manage Rooms</h2>
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
                    <h1>Manage Rooms</h1>
                    <form method="POST" action="manage_rooms.php" enctype="multipart/form-data" class="room-form">
                        <label for="name">Room Name:</label>
                        <input type="text" id="name" name="name" required><br>
                        <label for="description">Description:</label>
                        <input type="text" id="description" name="description" required><br>
                        <label for="capacity">Capacity:</label>
                        <input type="number" id="capacity" name="capacity" required><br>
                        <label for="price">Price:</label>
                        <input type="number" id="price" name="price" step="0.01" required><br>
                        <label for="image">Image:</label>
                        <input type="file" id="image" name="image" accept="image/*" required><br>
                        <input type="submit" value="Add Room">
                    </form>
                    <h2>Room List</h2>
                    <div class="table-responsive">
                        <table width="100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Capacity</th>
                                    <th>Price</th>
                                    <th>Image</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                            <td>{$row['id']}</td>
                                            <td>{$row['name']}</td>
                                            <td>{$row['description']}</td>
                                            <td class='centered'>{$row['capacity']}</td>
                                            <td class='centered'>{$row['price']}</td>
                                            <td><img src='uploads/{$row['image']}' class='image-preview' alt='Room Image'></td>
                                          </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
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
