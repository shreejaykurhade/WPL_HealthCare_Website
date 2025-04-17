<?php
session_start();
include 'config.php';
$message = '';

// Check if user is already logged in
if(isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

// Check if registration was successful
if(isset($_GET['registered']) && $_GET['registered'] == 'true') {
    $message = "Registration successful! Please login.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    
    $sql = "SELECT id, username, password, name FROM users WHERE username = '$username'";
    $result = $conn->query($sql);
    
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Password is correct, start a new session
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['name'] = $row['name'];
            
            // Redirect to dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            $message = "Invalid password!";
        }
    } else {
        $message = "Username not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - HealthCare Web</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Navigation Bar -->
    <header>
        <nav>
            <div class="logo">
                <a href="index.php">
                <img src="logo.png" alt="Healthcare Logo" style="width:175px ; height : 70px";>
                </a>
            </div>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="articles.php">Health Library</a></li>
                <li><a href="doctors.php">Appointments</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="aboutus.html">About Us</a></li>
            </ul>
        </nav>
    </header>

    <div class="form-container">
        <h2>LOGIN</h2>
        <?php if($message) echo "<p class='message'>$message</p>"; ?>
        
        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="btn">LOGIN</button>
        </form>
        
        <p>No Account? <a href="register.php">Register here</a></p>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="aboutus.html">About Us</a></li>
                    <li><a href="faq.html">FAQ</a></li>
                    <li><a href="feedback.html">Feedback</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Contact</h3>
                <ul>
                    <li>Email: support@healthcareweb.com</li>
                    <li>Phone: +123 456 7890</li>
                    <li>Location: Multiple Hospital Locations</li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Newsletter</h3>
                <p>Subscribe to stay updated with healthcare news.</p>
                <form action="subscribe.php" method="POST">
                    <input type="email" name="email" placeholder="Your mail" required>
                    <button type="submit">Subscribe</button>
                </form>
            </div>
        </div>
        <p>&copy; 2025 HealthCare Web. All rights reserved.</p>
    </footer>
</body>
</html>
