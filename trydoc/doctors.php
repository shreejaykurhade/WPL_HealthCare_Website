<?php
session_start();
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
    <title>Doctors Directory - HealthCare Web</title>
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
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li><a href="dashboard.php">My Dashboard</a></li>
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                <?php endif; ?>
                <li><a href="aboutus.html">About Us</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2 style="text-align : center";>Doctors Directory</h2>
        
        <!-- Dropdown for Specialties -->
        <form method="GET" class="filter-form">
            <label for="specialty">Filter by Specialty:</label>
            <select name="specialty" id="specialty" onchange="this.form.submit()">
                <option value="">All</option>
                <?php while($row = $specialtyResult->fetch_assoc()): ?>
                    <option value="<?php echo $row['specialty']; ?>" <?php echo ($selectedSpecialty == $row['specialty']) ? 'selected' : ''; ?>>
                        <?php echo $row['specialty']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </form>
        
        <!-- Display Doctors -->
        <div class="doctor-container">
            <?php if($doctorResult->num_rows > 0): ?>
                <?php while($doctor = $doctorResult->fetch_assoc()): ?>
                    <div class="doctor-card">
                        <img src="<?php echo (!empty($doctor['photolink'])) ? $doctor['photolink'] : 'images/default-doctor.jpg'; ?>" 
                             alt="<?php echo htmlspecialchars($doctor['name']); ?>">
                        <h3><?php echo htmlspecialchars($doctor['name']); ?></h3>
                        <p><strong>Specialty:</strong> <?php echo htmlspecialchars($doctor['specialty']); ?></p>
                        <p><strong>Timings:</strong> <?php echo htmlspecialchars($doctor['timings']); ?></p>
                        <?php if(isset($doctor['experience'])): ?>
                            <p><strong>Experience:</strong> <?php echo htmlspecialchars($doctor['experience']); ?> years</p>
                        <?php endif; ?>
                        <?php if(isset($doctor['qualifications'])): ?>
                            <p><strong>Qualifications:</strong> <?php echo htmlspecialchars($doctor['qualifications']); ?></p>
                        <?php endif; ?>
                        <a href="book_appointment.php?doctor_id=<?php echo $doctor['id']; ?>" class="btn">Book Appointment</a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No doctors found for the selected specialty.</p>
            <?php endif; ?>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="aboutus.html">About Us</a></li>
                    <li><a href="articles.php">Medical Articles</a></li>
                    <li><a href="book_appointment.php">Book Appointment</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Contact</h3>
                <ul>
                    <li>Email: support@healmateweb.com</li>
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
        <p>&copy; 2025 HealMate Web. All rights reserved.</p>
    </footer>
</body>
</html>
