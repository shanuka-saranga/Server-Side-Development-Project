<?php
// includes/navbar.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$current_page = basename($_SERVER['SCRIPT_NAME']);
$is_logged_in = !empty($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../Public/assets/css/allnav&footer.css">
    <link rel="stylesheet" href="Public/assests/css/navbar.css">
    <style>

    </style>
</head>

<body>

    <nav class="navbar-container">
        <!-- Logo -->
        <a href="../index.php" class="navbar-logo">
            <img src="../Public/assets/images/LOGO.png" alt="Logo">
            Festora
        </a>

        <!-- Menu -->
        <ul class="navbar-menu">
            <li><a href="../index.php" class="<?= $current_page === 'index.php' ? 'active' : '' ?>">Home</a></li>
            <li><a href="../event/events.html">Events</a></li>
            <li><a href="../Public/Contactus.php">Contact us</a></li>
            <li><a href="../Public/aboutus.php">About us</a></li>

            <!-- Booking with Hover Dropdown -->
            <li class="dropdown">
                <a href="../Public/Booking.php"
                    class="<?= in_array($current_page, ['Booking.php', 'appointment.php']) ? 'active' : '' ?>">
                    Booking
                </a>
                <ul class="submenu">
                    <li><a href="../Public/appointment.php"
                            class="<?= $current_page === 'appointment.php' ? 'active' : '' ?>">Appointment</a></li>
                    <!-- Add more sub-items here if needed -->
                </ul>
            </li>

            <li><a href="../services/Services.html">Our Service</a></li>
            <li><a href="../services/Services.html">Reviews</a></li>
        </ul>
        <!-- End Booking -->


        <!-- RIGHT CORNER: LOGIN / PROFILE -->
        <div class="navbar-auth">

            <!-- NOT LOGGED IN: Show Login + Sign Up -->
            <div class="auth-buttons" style="display:<?= $is_logged_in ? 'none' : 'flex' ?>;">
                <a href="Public/login.php" class="btn-auth">Login</a>
                <a href="Public/login.php" class="btn-auth signup">Sign Up</a>
            </div>

            <!-- LOGGED IN: Show Profile + Logout -->
            <div class="user-section" style="display:<?= $is_logged_in ? 'flex' : 'none' ?>;">
                <div class="user-profile">
                    <!-- Optional: Add avatar -->
                    <img src="../Public/assets/images/default-avatar.png" alt="User">
                    <span>Hi, <?= htmlspecialchars($_SESSION['user_name'] ?? '') ?></span>
                </div>
                <a href="Public/profile.php" class="btn-auth">Profile</a>
                <a href="Public/logout.php" class="btn-auth signup">Logout</a>
            </div>

        </div>
    </nav>