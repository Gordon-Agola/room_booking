<?php
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    // Validate password
    $password_pattern = "/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/";
    if (!preg_match($password_pattern, $password)) {
        $error = "Password must be at least 8 characters long, contain at least one capital letter, one number, and one special character.";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Check if username is already in use
        $sql_check_username = "SELECT * FROM users WHERE username = '$username'";
        $result_username = $conn->query($sql_check_username);

        if ($result_username->num_rows > 0) {
            $error = "This username is already taken. Please choose a different one.";
        } else {
            // Check if email is already in use
            $sql_check_email = "SELECT * FROM users WHERE email = '$email'";
            $result_email = $conn->query($sql_check_email);

            if ($result_email->num_rows > 0) {
                $error = "This email is already associated with another account.";
            } else {
                // Insert the new user into the database
                $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$hashed_password', '$email')";

                if ($conn->query($sql) === TRUE) {
                    header("Location: login.php");
                    exit();
                } else {
                    $error = "Error: " . $sql . "<br>" . $conn->error;
                }
            }
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="test/user.css">
    <style>
        .toggle-password {
            cursor: pointer;
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
        }
        .password-container {
            position: relative;
            width: 100%;
        }
        .Registration {
            margin: auto;
            width: 550px;
            margin-top: 30px;
            margin-bottom: 30px;
            box-sizing: border-box;
            border: 1px solid rgba(0,0,0.1);
            box-shadow: 0 5px 10px rgba(0,0,0.2);
        }
        input[type=text], input[type=tel], input[type=email], input[type=password], input[type=date], select {
            width: 400px;
            border: none;
            padding: 12px 20px;
            text-align: left;
            margin: 5px 0px;
            display: inline-block;
            border: 1px solid blue;
            border-radius: 5px;
        }
        input[type=Submit] {
            width: 300px;
            padding: 14px 20px;
            background-color: #4CAF50;
            border: none;
            cursor: pointer;
            margin-top: 20px;
            color: #000;
            font-weight: bold;
            font-variant: small-caps;
            border-radius: 1em;
        }
        input[type=Submit]:hover {
            opacity: 0.8;
        }
        div label {
            font-weight: bolder;
            padding: 3px;
            margin: 0px;
            text-align: left;
        }
        select:focus, input[type=tel]:focus, input[type=text]:focus, input[type=email]:focus, input[type=password]:focus, input[type=date]:focus {
            border: 2px solid blue;
        }
    </style>
</head>
<body>

    <header class="bg-dark">
        <nav id="navbar">
            <div class="container">
                <h1 class="logo"><a href="index.php">Room Booking System</a></h1>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="rooms.php">Rooms</a></li>
                    <li><a href="register.php" class="active">Register</a></li>
                    <li><a href="login.php">Login</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <section id="showcase" style="background: url('https://images.pexels.com/photos/1571460/pexels-photo-1571460.jpeg?auto=compress&cs=tinysrgb&dpr=3&h=750&w=1260') no-repeat center center/cover; height: 600px;">
        <div class="showcase-content">
            <h1><span class="text-primary">Register</span></h1>
            <p class="lead">Join us to book rooms for training, studying, or teaching.</p>
        </div>
    </section>

    <section id="registration" class="bg-light">
        <div class="info-content">
            <div class="container">
                <br /><br />
                <h2>Register</h2>
                <form method="POST" action="register.php">
                    <div>
                        <label for="username">Username</label><br />
                        <input type="text" name="username" id="username" required>
                    </div>
                    <div class="password-container">
                        <label for="password">Password</label><br />
                        <input type="password" name="password" id="password" required>
                        <span class="toggle-password" onclick="togglePassword()">👁️</span>
                    </div>
                    <div>
                        <label for="email">Email</label><br />
                        <input type="email" name="email" id="email" required>
                    </div><br />
                    <input type="submit" value="Register" class="btn">
                    <br /><br /><br />
                </form>
                <?php if (isset($error)): ?>
                    <p class="error"><?php echo htmlspecialchars($error); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <footer id="main-footer">
        <p>Room Booking System &copy; 2024, All Rights Reserved.</p>
    </footer>

    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.querySelector('.toggle-password');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.textContent = '🙈';
            } else {
                passwordField.type = 'password';
                toggleIcon.textContent = '👁️';
            }
        }
    </script>
</body>
</html>
6