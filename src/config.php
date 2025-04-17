<?php
$host = "localhost";
$user = "root";  // Your MySQL username
$password = "";  // Your MySQL password
$database = "healthcare_website";

// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
