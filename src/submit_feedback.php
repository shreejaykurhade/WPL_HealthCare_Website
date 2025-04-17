<?php
include 'config.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = isset($_POST['phone']) ? mysqli_real_escape_string($conn, $_POST['phone']) : '';
    $visit_date = isset($_POST['visit_date']) && !empty($_POST['visit_date']) ? 
                  mysqli_real_escape_string($conn, $_POST['visit_date']) : NULL;
    $feedback_message = mysqli_real_escape_string($conn, $_POST['message']);
    
    $sql = "INSERT INTO feedback (name, email, phone, visit_date, message) 
            VALUES ('$name', '$email', '$phone', " . ($visit_date ? "'$visit_date'" : "NULL") . ", '$feedback_message')";
    
    if ($conn->query($sql) === TRUE) {
        $message = "Thank you for your feedback!";
    } else {
        $message = "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Submitted - HealthCare Web</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Navigation Bar -->
    <header>
        <nav>
            <div class="logo">
                <a href="index.php">
                    <img src="logo.png" alt="Healthcare Logo">
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

    <div class="message-container">
        <h2 style="text-align : center;">Feedback Submission</h2>
        <div class="message-box">
            <p><?php echo $message; ?></p>
            <p>We appreciate your input and will use it to improve our services.</p>
            <a href="index.php" class="btn">Return to Home</a>
        </div>
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
