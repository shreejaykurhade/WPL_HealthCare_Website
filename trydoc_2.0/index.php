<?php
session_start();
include 'config.php';

// Fetch some doctor data for homepage
$doctorQuery = "SELECT id, name, specialty, photolink FROM doctors LIMIT 4";
$doctorResult = $conn->query($doctorQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HealthCare Web - Your Health, Our Priority</title>
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
                <li><a href="index.php" class="active">Home</a></li>
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

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Your Health, Our Priority</h1>
            <p>Access quality healthcare services from the comfort of your home.</p>
            <div class="hero-buttons">
                <a href="doctors.php" class="btn">Book Appointment</a>
                <?php if(!isset($_SESSION['user_id'])): ?>
                    <a href="register.php" class="btn btn-secondary">Register Now</a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <h2>Our Services</h2>
        <div class="feature-container">
            <a href ="book_appointment.php">
            <div class="feature-card">
                <div class="feature-icon">
                    <img src="https://cdn.vectorstock.com/i/preview-1x/42/22/schedule-doctor-appointment-2d-isolated-vector-44274222.jpg" alt="Online Appointments" style="height :800px; width :800px";>
                </div>
                <h3>Online Appointments</h3>
                <p>Book appointments with specialists in just a few clicks, no waiting in line.</p>
            </div>
            </a>
            <a href="dashboard.php">
            <div class="feature-card">
                <div class="feature-icon">
                    <img src="https://soap.health/wp-content/uploads/2023/01/1.png" alt="Digital Health Records"  style="height :800px; width :800px";>
                </div>
                <h3>Digital Health Records</h3>
                <p>Access your medical history anytime, anywhere securely.</p>
            </div>
            </a>
            <a href="articles.php">
            <div class="feature-card">
                <div class="feature-icon">
                    <img src="https://content.govdelivery.com/attachments/fancy_images/USVHA/2021/01/4005196/covid-vaccine-01_original.png" alt="Medical Articles"  style="height :800px; width :800px";>
                </div>
                <h3>Medical Articles</h3>
                <p>Know more about the current medical happenings.</p>
            </div>
            </a>
        </div>
    </section>

    <!-- Doctors Preview Section -->
    <section class="doctors-preview">
        <h2>Meet Our Specialists</h2>
        <div class="doctor-preview-container">
            <?php if($doctorResult->num_rows > 0): ?>
                <?php while($doctor = $doctorResult->fetch_assoc()): ?>
                    <div class="doctor-preview-card">
                        <img src="<?php echo (!empty($doctor['photolink'])) ? $doctor['photolink'] : 'images/default-doctor.jpg'; ?>" 
                             alt="<?php echo htmlspecialchars($doctor['name']); ?>">
                        <h3><?php echo htmlspecialchars($doctor['name']); ?></h3>
                        <p><?php echo htmlspecialchars($doctor['specialty']); ?></p>
                        <a href="book_appointment.php?doctor_id=<?php echo $doctor['id']; ?>" class="btn btn-small">Book</a>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
        <div class="view-all">
            <a href="doctors.php" class="btn">View All Doctors</a>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials">
        <h2>What Our Patients Say</h2>
        <div class="testimonial-container">
            <div class="testimonial-card">
                <div class="testimonial-content">
                    <p>"The online booking system saved me so much time. I was able to see a specialist quickly without the usual waiting time."</p>
                </div>
                <div class="testimonial-author">
                    <img src="https://profile-images.xing.com/images/81001824ece916dbbbc2476eb3e7ebd3-6/priya-sharma.1024x1024.jpg" alt="Patient">
                    <div>
                        <h4>Priya Sharma</h4>
                        <p>Patient</p>
                    </div>
                </div>
            </div>
            <div class="testimonial-card">
                <div class="testimonial-content">
                    <p>"The doctors are incredibly professional and caring. I feel like my health is in good hands with HealthCare Web."</p>
                </div>
                <div class="testimonial-author">
                    <img src="https://i1.rgstatic.net/ii/profile.image/1071785705500672-1632545033376_Q512/Rahul-Verma-100.jpg" alt="Patient">
                    <div>
                        <h4>Rahul Verma</h4>
                        <p>Patient</p>
                    </div>
                </div>
            </div>
            <div class="testimonial-card">
                <div class="testimonial-content">
                    <p>"Having all my medical records in one place has made managing my healthcare so much easier. Great service!"</p>
                </div>
                <div class="testimonial-author">
                    <img src="https://static.spacecrafted.com/ef0fadbf904e4bc68db5c4c88ee7357a/i/fd5a9fcc98a0406cb24caa8cf5a63b70/1/GCuCv726gZycFxatRCb7iU/Anisha_8087.jpg" alt="Patient">
                    <div>
                        <h4>Neha Patel</h4>
                        <p>Patient</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="cta-content">
            <h2>Ready to prioritize your health?</h2>
            <p>Join thousands of patients who trust us with their healthcare needs.</p>
            <a href="register.php" class="btn btn-large">Get Started Today</a>
        </div>
    </section>

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
