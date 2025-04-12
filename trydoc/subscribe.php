<?php
include 'config.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    // Check if email already exists
    $check_query = "SELECT * FROM newsletter WHERE email='$email'";
    $result = $conn->query($check_query);
    
    if ($result->num_rows > 0) {
        $message = "This email is already subscribed to our newsletter.";
    } else {
        $sql = "INSERT INTO newsletter (email) VALUES ('$email')";
        
        if ($conn->query($sql) === TRUE) {
            $message = "Thank you for subscribing to our newsletter!";
        } else {
            $message = "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Determine the return URL
$return_url = 'index.php';
if(isset($_SERVER['HTTP_REFERER'])) {
    // Extract the page name from the referer URL
    $referer = $_SERVER['HTTP_REFERER'];
    $path_parts = parse_url($referer, PHP_URL_PATH);
    if($path_parts) {
        $path_segments = explode('/', $path_parts);
        $last_segment = end($path_segments);
        if(!empty($last_segment)) {
            $return_url = $last_segment;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Newsletter Subscription - HealthCare Web</title>
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
        <h2>Newsletter Subscription</h2>
        <div class="message-box">
            <p><?php echo $message; ?></p>
            <p>Stay tuned for the latest updates on health tips and services.</p>
            <a href="<?php echo $return_url; ?>" class="btn">Go Back</a>
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
