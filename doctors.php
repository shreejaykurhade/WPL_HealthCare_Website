<?php
include 'config.php';

// Fetch unique specialties
$specialtyQuery = "SELECT DISTINCT specialty FROM doctors";
$specialtyResult = $conn->query($specialtyQuery);

// Fetch doctors based on selection
$selectedSpecialty = isset($_GET['specialty']) ? $_GET['specialty'] : '';
$doctorQuery = "SELECT * FROM doctors";
if ($selectedSpecialty) {
    $doctorQuery .= " WHERE specialty='$selectedSpecialty'";
}
$doctorResult = $conn->query($doctorQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctors Point</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<!-- Navigation Bar -->
<header>
    <nav>
        <div class="logo">
            <a href="home.html">
                <img src="logo.png" alt="Healthcare Logo">
            </a>
        </div>
        <ul class="nav-links">
            <li><a href="home.php">Home</a></li>
            <li><a href="articles.php">Healthcare Library</a></li>
            <li><a href="doctors.php">Appointments</a></li>
            <li><a href="login.php">Login</a></li>
            <li><a href="contact.php">Contact Us</a></li>
        </ul>
    </nav>
</header>

<!-- Page Title -->
<h2>Doctors Directory</h2>

<!-- Dropdown for Specialties -->
<form method="GET">
    <label for="specialty">Filter by Specialty:</label>
    <select name="specialty" id="specialty" onchange="this.form.submit()">
        <option value="">All</option>
        <?php while ($row = $specialtyResult->fetch_assoc()): ?>
            <option value="<?= $row['specialty'] ?>" <?= $selectedSpecialty == $row['specialty'] ? 'selected' : '' ?>>
                <?= $row['specialty'] ?>
            </option>
        <?php endwhile; ?>
    </select>
</form>

<!-- Display Doctors -->
<div class="doctor-container">
    <?php while ($doctor = $doctorResult->fetch_assoc()): ?>
        <div class="doctor-card">
            <img src="<?= $doctor['photolink'] ?>" alt="<?= $doctor['name'] ?>">
            <h3><?= $doctor['name'] ?></h3>
            <p><strong>Specialty:</strong> <?= $doctor['specialty'] ?></p>
            <p><strong>Timings:</strong> <?= htmlspecialchars($doctor['timings']) ?></p>
        </div>
    <?php endwhile; ?>
</div>

<!-- Footer -->
<footer class="footer">
            <div class="footer-container">
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="about.html">About Us</a></li>
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
                    <p>Subscribe to stay updated with healthcare news and articles.</p>
                    <input type="email" placeholder="Enter your email">
                    <button>Subscribe</button>
                </div>
            </div>
            <p>&copy; 2025 HealthCare Web. All rights reserved.</p>
</footer>




</body>
</html>
