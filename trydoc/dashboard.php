<?php
session_start();
include 'config.php';

// Check if user is logged in
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = '';

// Fetch user data
$sql = "SELECT * FROM users WHERE id = $user_id";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

// Update profile if form submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $height = mysqli_real_escape_string($conn, $_POST['height']);
    $weight = mysqli_real_escape_string($conn, $_POST['weight']);
    
    $update_sql = "UPDATE users SET 
                  name = '$name', 
                  email = '$email', 
                  contact = '$contact', 
                  dob = '$dob', 
                  height = '$height', 
                  weight = '$weight' 
                  WHERE id = $user_id";
    
    if ($conn->query($update_sql) === TRUE) {
        $message = "Profile updated successfully!";
        // Refresh user data
        $result = $conn->query($sql);
        $user = $result->fetch_assoc();
    } else {
        $message = "Error updating profile: " . $conn->error;
    }
}

// Delete account if requested
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_account'])) {
    $delete_sql = "DELETE FROM users WHERE id = $user_id";
    
    if ($conn->query($delete_sql) === TRUE) {
        // Destroy session and redirect
        session_destroy();
        header("Location: index.php?account_deleted=true");
        exit();
    } else {
        $message = "Error deleting account: " . $conn->error;
    }
}

// Fetch user's appointments
$appointments_sql = "SELECT a.*, d.name as doctor_name, d.specialty 
                    FROM appointments a 
                    JOIN doctors d ON a.doctor_id = d.id 
                    WHERE a.user_id = $user_id 
                    ORDER BY a.appointment_date DESC, a.appointment_time DESC";
$appointments = $conn->query($appointments_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Dashboard - HealthCare Web</title>
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
                <li><a href="dashboard.php">My Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>
                <li><a href="aboutus.html">About Us</a></li>
            </ul>
        </nav>
    </header>

    <div class="dashboard-container">
        <h2>Welcome, <?php echo htmlspecialchars($user['name']); ?>!</h2>
        <?php if($message) echo "<p class='message'>$message</p>"; ?>
        
        <div class="dashboard-sections">
            <div class="profile-section">
                <h3>My Profile</h3>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="contact">Contact</label>
                        <input type="text" id="contact" name="contact" value="<?php echo htmlspecialchars($user['contact']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="dob">Date of Birth</label>
                        <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($user['dob']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="height">Height (cm)</label>
                        <input type="number" step="0.01" id="height" name="height" value="<?php echo htmlspecialchars($user['height']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="weight">Weight (kg)</label>
                        <input type="number" step="0.01" id="weight" name="weight" value="<?php echo htmlspecialchars($user['weight']); ?>">
                    </div>
                    
                    <button type="submit" name="update_profile" class="btn">Update Profile</button>
                </form>
                
                <div class="danger-zone">
                    <h4>Danger Zone</h4>
                    <form method="POST" action="" onsubmit="return confirm('Are you sure you want to delete your account? This cannot be undone.');">
                        <button type="submit" name="delete_account" class="btn-danger">Delete Account</button>
                    </form>
                </div>
            </div>
            
            <div class="appointments-section">
                <h3>My Appointments</h3>
                <?php if($appointments->num_rows > 0): ?>
                    <table class="appointments-table">
                        <thead>
                            <tr>
                                <th>Doctor</th>
                                <th>Specialty</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($appointment = $appointments->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($appointment['doctor_name']); ?></td>
                                    <td><?php echo htmlspecialchars($appointment['specialty']); ?></td>
                                    <td><?php echo htmlspecialchars($appointment['appointment_date']); ?></td>
                                    <td><?php echo htmlspecialchars($appointment['appointment_time']); ?></td>
                                    <td><?php echo htmlspecialchars(ucfirst($appointment['status'])); ?></td>
                                    <td>
                                        <?php if($appointment['status'] == 'pending' || $appointment['status'] == 'confirmed'): ?>
                                            <a href="cancel_appointment.php?id=<?php echo $appointment['id']; ?>" class="btn-small" onclick="return confirm('Are you sure you want to cancel this appointment?');">Cancel</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>You don't have any appointments yet.</p>
                <?php endif; ?>
                
                <a href="doctors.php" class="btn">Book New Appointment</a>
            </div>
        </div>
    </div>


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
