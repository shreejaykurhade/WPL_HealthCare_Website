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
            <img src="logo.png" alt="Healthcare Logo">
        </div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="experts.php">Healthcare Experts</a></li>
            <li><a href="appointments.php">Appointments</a></li>
            <li><a href="articles.php">Articles</a></li>
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

<!-- Blank Footer -->
<footer></footer>

</body>
</html>
