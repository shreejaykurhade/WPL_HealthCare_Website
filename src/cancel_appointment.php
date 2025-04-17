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

// Check if appointment ID is provided
if(!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$appointment_id = $_GET['id'];

// Verify the appointment belongs to the user
$checkQuery = "SELECT * FROM appointments WHERE id = $appointment_id AND user_id = $user_id";
$result = $conn->query($checkQuery);

if($result->num_rows == 0) {
    // Appointment doesn't exist or doesn't belong to the user
    header("Location: dashboard.php");
    exit();
}

// Update appointment status
$updateQuery = "UPDATE appointments SET status = 'cancelled' WHERE id = $appointment_id";

if($conn->query($updateQuery)) {
    $message = "Appointment cancelled successfully.";
} else {
    $message = "Error cancelling appointment: " . $conn->error;
}

// Redirect to dashboard with message
header("Location: dashboard.php?message=" . urlencode($message));
exit();
?>


