<?php
session_start();
include 'config.php';

// Check if user is logged in
if(!isset($_SESSION['user_id'])) {
    // Redirect to login with return URL
    header("Location: login.php?redirect=book_appointment.php?doctor_id=" . $_GET['doctor_id']);
    exit();
}

// Verify doctor exists
if(!isset($_GET['doctor_id'])) {
    header("Location: doctors.php");
    exit();
}

$doctor_id = $_GET['doctor_id'];
$user_id = $_SESSION['user_id'];
$message = '';
$suggested_slots = array();
$show_suggestions = false;

// Fetch doctor details
$doctorQuery = "SELECT * FROM doctors WHERE id = $doctor_id";
$doctorResult = $conn->query($doctorQuery);

if($doctorResult->num_rows == 0) {
    header("Location: doctors.php");
    exit();
}

$doctor = $doctorResult->fetch_assoc();

// Parse doctor's timing to check if suggested time is within doctor's hours
list($start_time, $end_time) = explode(' - ', $doctor['timings']);
$doctor_start_time = date('H:i:s', strtotime($start_time));
$doctor_end_time = date('H:i:s', strtotime($end_time));

// Process form submission for initial booking
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['accept_alternative']) && !isset($_POST['accept_pending'])) {
    $appointment_date = mysqli_real_escape_string($conn, $_POST['appointment_date']);
    $appointment_time = mysqli_real_escape_string($conn, $_POST['appointment_time']);
    $reason = mysqli_real_escape_string($conn, $_POST['reason']);
    
    // Simple validation
    $today = date("Y-m-d");
    if($appointment_date < $today) {
        $message = "Cannot book appointments for past dates.";
    } 
    // Check if time is within doctor's working hours
    elseif($appointment_time < $doctor_start_time || $appointment_time > $doctor_end_time) {
        $message = "Selected time is outside doctor's working hours (" . $doctor['timings'] . ").";
    } 
    else {
        // Check if slot is available
        $checkSlotQuery = "SELECT * FROM appointments 
                          WHERE doctor_id = $doctor_id 
                          AND appointment_date = '$appointment_date' 
                          AND (
                                (appointment_time <= '$appointment_time' AND ADDTIME(appointment_time, '00:15:00') > '$appointment_time') OR
                                ('$appointment_time' <= appointment_time AND ADDTIME('$appointment_time', '00:15:00') > appointment_time)
                              )
                          AND status != 'cancelled'";
        
        $slotResult = $conn->query($checkSlotQuery);
        
        if($slotResult->num_rows > 0) {
            // Slot is not available, suggest alternatives
            $message = "Your preferred time slot is not available. Please choose from suggested times below.";
            $show_suggestions = true;
            
            // Find alternative slots (3 before and 3 after in 15-minute increments)
            $timeIntervals = array(-45, -30, -15, 15, 30, 45); // in minutes
            
            foreach($timeIntervals as $interval) {
                $suggested_time = date('H:i:s', strtotime("$appointment_time $interval minutes"));
                
                // Check if suggested time is within doctor's hours
                if($suggested_time >= $doctor_start_time && $suggested_time <= $doctor_end_time) {
                    // Check if this slot is available
                    $checkSuggestedQuery = "SELECT * FROM appointments 
                                          WHERE doctor_id = $doctor_id 
                                          AND appointment_date = '$appointment_date' 
                                          AND (
                                                (appointment_time <= '$suggested_time' AND ADDTIME(appointment_time, '00:15:00') > '$suggested_time') OR
                                                ('$suggested_time' <= appointment_time AND ADDTIME('$suggested_time', '00:15:00') > appointment_time)
                                              )
                                          AND status != 'cancelled'";
                    
                    $suggestedResult = $conn->query($checkSuggestedQuery);
                    
                    if($suggestedResult->num_rows == 0) {
                        // This slot is available, add to suggestions
                        $suggested_slots[] = $suggested_time;
                    }
                }
            }
        } else {
            // Slot is available, book it as confirmed
            $insertSql = "INSERT INTO appointments (user_id, doctor_id, appointment_date, appointment_time, reason, status) 
                         VALUES ($user_id, $doctor_id, '$appointment_date', '$appointment_time', '$reason', 'confirmed')";
            
            if ($conn->query($insertSql) === TRUE) {
                $message = "Appointment confirmed for " . date('h:i A', strtotime($appointment_time)) . " on " . date('d M Y', strtotime($appointment_date));
                header("refresh:2;url=dashboard.php");
                exit();
            } else {
                $message = "Error booking appointment: " . $conn->error;
            }
        }
    }
} 
// Process form submission for accepting alternative time
elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accept_alternative'])) {
    // User accepted an alternative slot
    $appointment_date = mysqli_real_escape_string($conn, $_POST['appointment_date']);
    $appointment_time = mysqli_real_escape_string($conn, $_POST['alternative_time']);
    $reason = mysqli_real_escape_string($conn, $_POST['reason']);
    
    $insertSql = "INSERT INTO appointments (user_id, doctor_id, appointment_date, appointment_time, reason, status) 
                 VALUES ($user_id, $doctor_id, '$appointment_date', '$appointment_time', '$reason', 'confirmed')";
    
    if ($conn->query($insertSql) === TRUE) {
        $message = "Appointment confirmed for " . date('h:i A', strtotime($appointment_time)) . " on " . date('d M Y', strtotime($appointment_date));
        header("refresh:2;url=dashboard.php");
        exit();
    } else {
        $message = "Error booking appointment: " . $conn->error;
    }
}
// Process form submission for accepting pending status
elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accept_pending'])) {
    $appointment_date = mysqli_real_escape_string($conn, $_POST['appointment_date']);
    $appointment_time = mysqli_real_escape_string($conn, $_POST['appointment_time']);
    $reason = mysqli_real_escape_string($conn, $_POST['reason']);
    
    $insertSql = "INSERT INTO appointments (user_id, doctor_id, appointment_date, appointment_time, reason, status) 
                 VALUES ($user_id, $doctor_id, '$appointment_date', '$appointment_time', '$reason', 'pending')";
    
    if ($conn->query($insertSql) === TRUE) {
        $message = "Appointment request submitted as pending. We'll notify you when confirmed.";
        header("refresh:2;url=dashboard.php");
        exit();
    } else {
        $message = "Error booking appointment: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment - HealthCare Web</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .suggested-slots {
            margin: 20px 0;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 5px;
            border-left: 4px solid #4CAF50;
        }
        .suggested-slots h3 {
            margin-top: 0;
            color: #333;
        }
        .slot-options {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }
        .slot-option {
            padding: 8px 15px;
            background-color: #e0f7fa;
            border: 1px solid #b2ebf2;
            border-radius: 4px;
            cursor: pointer;
        }
        .slot-option:hover {
            background-color: #b2ebf2;
        }
        .slot-option.selected {
            background-color: #26c6da;
            color: white;
        }
        .pending-notice {
            margin-top: 15px;
            padding: 10px;
            background-color: #fff3e0;
            border-left: 4px solid #ff9800;
            font-size: 0.9em;
        }
        .time-range-notice {
            font-size: 0.9em;
            color: #666;
            margin-top: 5px;
        }
    </style>
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
                <li><a href="dashboard.php">My Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>
                <li><a href="aboutus.html">About Us</a></li>
            </ul>
        </nav>
    </header>

    <div class="form-container">
        <h2>Book Appointment with Dr. <?php echo htmlspecialchars($doctor['name']); ?></h2>
        <?php if($message) echo "<p class='message'>$message</p>"; ?>
        
        <div class="doctor-details">
            <img src="<?php echo (!empty($doctor['photolink'])) ? $doctor['photolink'] : 'images/default-doctor.jpg'; ?>" 
                 alt="<?php echo htmlspecialchars($doctor['name']); ?>" class="doctor-img">
            <div>
                <h3><?php echo htmlspecialchars($doctor['name']); ?></h3>
                <p><strong>Specialty:</strong> <?php echo htmlspecialchars($doctor['specialty']); ?></p>
                <p><strong>Timings:</strong> <?php echo htmlspecialchars($doctor['timings']); ?></p>
                <?php if(!empty($doctor['qualifications'])): ?>
                    <p><strong>Qualifications:</strong> <?php echo htmlspecialchars($doctor['qualifications']); ?></p>
                <?php endif; ?>
                <?php if(!empty($doctor['about'])): ?>
                    <p><?php echo htmlspecialchars($doctor['about']); ?></p>
                <?php endif; ?>
            </div>
        </div>
        
        <?php if($show_suggestions && !empty($suggested_slots)): ?>
        <!-- Display suggested alternative slots -->
        <div class="suggested-slots">
            <h3>Available Time Slots</h3>
            <p>Your preferred time slot is not available. Please select from these available times:</p>
            
            <form method="POST" action="">
                <input type="hidden" name="appointment_date" value="<?php echo htmlspecialchars($_POST['appointment_date']); ?>">
                <input type="hidden" name="reason" value="<?php echo htmlspecialchars($_POST['reason']); ?>">
                
                <div class="slot-options">
                    <?php foreach($suggested_slots as $index => $slot): ?>
                    <label class="slot-option">
                        <input type="radio" name="alternative_time" value="<?php echo $slot; ?>" required <?php echo ($index == 0) ? 'checked' : ''; ?>>
                        <?php echo date('h:i A', strtotime($slot)); ?>
                    </label>
                    <?php endforeach; ?>
                </div>
                
                <div style="margin-top: 15px;">
                    <button type="submit" name="accept_alternative" class="btn">Book Alternative Slot</button>
                </div>
            </form>
            
            <div class="pending-notice">
                <p>If none of these times work for you, you can:</p>
                <form method="POST" action="">
                    <input type="hidden" name="appointment_date" value="<?php echo htmlspecialchars($_POST['appointment_date']); ?>">
                    <input type="hidden" name="appointment_time" value="<?php echo htmlspecialchars($_POST['appointment_time']); ?>">
                    <input type="hidden" name="reason" value="<?php echo htmlspecialchars($_POST['reason']); ?>">
                    <button type="submit" name="accept_pending" class="btn btn-secondary">Submit Original Request as Pending</button>
                </form>
                <small>Your original request will be kept pending and confirmed if the slot becomes available.</small>
            </div>
        </div>
        <?php elseif($show_suggestions && empty($suggested_slots)): ?>
        <!-- No alternative slots available -->
        <div class="suggested-slots">
            <h3>No Alternative Slots Available</h3>
            <p>There are no alternative time slots available on this date. You can:</p>
            
            <form method="POST" action="">
                <input type="hidden" name="appointment_date" value="<?php echo htmlspecialchars($_POST['appointment_date']); ?>">
                <input type="hidden" name="appointment_time" value="<?php echo htmlspecialchars($_POST['appointment_time']); ?>">
                <input type="hidden" name="reason" value="<?php echo htmlspecialchars($_POST['reason']); ?>">
                <button type="submit" name="accept_pending" class="btn">Submit Request as Pending</button>
            </form>
            <small>Your request will be kept pending and confirmed if the slot becomes available.</small>
            
            <p style="margin-top: 15px;">Or you can select a different date:</p>
            <a href="book_appointment.php?doctor_id=<?php echo $doctor_id; ?>" class="btn btn-secondary">Choose Different Date/Time</a>
        </div>
        <?php else: ?>
        <!-- Original booking form -->
        <form method="POST" action="">
            <div class="form-group">
                <label for="appointment_date">Appointment Date</label>
                <input type="date" id="appointment_date" name="appointment_date" min="<?php echo date('Y-m-d'); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="appointment_time">Preferred Time</label>
                <input type="time" id="appointment_time" name="appointment_time" 
                       min="<?php echo $doctor_start_time; ?>" 
                       max="<?php echo $doctor_end_time; ?>" required>
                <p class="time-range-notice">
                    Please select a time between <?php echo date('h:i A', strtotime($doctor_start_time)); ?> and 
                    <?php echo date('h:i A', strtotime($doctor_end_time)); ?>
                </p>
            </div>
            
            <div class="form-group">
                <label for="reason">Reason for Visit</label>
                <textarea id="reason" name="reason" rows="3" required></textarea>
            </div>
            
            <button type="submit" class="btn">Book Appointment</button>
            <a href="doctors.php" class="btn btn-secondary">Back to Doctors</a>
        </form>
        <?php endif; ?>
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

    <script>
    // Simple script to highlight selected slot option
    document.addEventListener('DOMContentLoaded', function() {
        const slotOptions = document.querySelectorAll('.slot-option');
        slotOptions.forEach(option => {
            option.addEventListener('click', function() {
                // Remove selected class from all options
                slotOptions.forEach(opt => opt.classList.remove('selected'));
                // Add selected class to clicked option
                this.classList.add('selected');
            });
        });
    });
    </script>
</body>
</html>












