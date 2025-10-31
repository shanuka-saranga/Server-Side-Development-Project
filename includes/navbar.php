<?php
// includes/navbar.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$current_page = basename($_SERVER['SCRIPT_NAME']);
$is_logged_in = !empty($_SESSION['user_id']);
$is_admin_logged_in = !empty($_SESSION['admin_id']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../Public/assets/css/allnav&footer.css">
    <link rel="stylesheet" href="Public/assests/css/navbar.css">
</head>

<body>

    <nav class="navbar-container">
        <!-- Logo -->
        <a href="../index.php" class="navbar-logo">
            <img src="assests/index/LOGO.png" alt="Logo">
            Festora
        </a>

        <!-- Menu -->
        <ul class="navbar-menu">
            <li><a href="../index.php" class="<?= $current_page === 'index.php' ? 'active' : '' ?>">Home</a></li>
            <li><a href="Public/events.php">Events</a></li>
            <li><a href="Public/Contactus.php">Contact Us</a></li>
            <li><a href="../Public/aboutus.php">About Us</a></li>

            <!-- Booking Dropdown -->
            <li class="dropdown">
                <a href="Public/Booking.php"
                    class="<?= in_array($current_page, ['Booking.php', 'appointment.php']) ? 'active' : '' ?>">
                    Booking
                </a>
                <ul class="submenu">
                    <li><a href="Public/appointment.php"
                            class="<?= $current_page === 'appointment.php' ? 'active' : '' ?>">Appointment</a></li>

                    <li><a href="Public/Payment.php"
                            class="<?= $current_page === 'Payment.php' ? 'active' : '' ?>">Payment</a></li>
                </ul>

            </li>

            <li><a href="Public/Reveiw.php">Reviews</a></li>
        </ul>

        <!-- RIGHT: AUTH SECTION -->
        <div class="navbar-auth">

            <!-- NOT LOGGED IN -->
            <?php if (!$is_logged_in && !$is_admin_logged_in): ?>
                <div class="auth-buttons">
                    <a href="Public/login.php" class="btn-auth">User Login</a>
                    <a href="admin/admin_login.php" class="btn-auth signup">Admin Login</a>
                </div>
            <?php endif; ?>

            <!-- USER LOGGED IN -->
            <?php if ($is_logged_in): ?>
                <div class="navbar-auth">
                    <div class="user-profile">
                        <img src="../Public/assets/images/default-avatar.png" alt="User">
                        <span>Hi, <?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?></span>
                    </div>
                    <a href="Public/profile.php" class="btn-auth">Profile</a>
                    <a href="Public/logout.php" class="btn-auth signup">Logout</a>
                </div>
            <?php endif; ?>

            <!-- ADMIN LOGGED IN -->
            <?php if ($is_admin_logged_in): ?>
                <div class="navbar-auth">
                    <div class="user-profile">
                        <img src="../Public/assets/images/admin-avatar.png" alt="Admin">
                        <span>Admin</span>
                    </div>
                    <a href="admin/dashboard.php" class="btn-auth">Dashboard</a>
                    <a href="Public/logout.php" class="btn-auth signup">Logout</a>
                </div>
            <?php endif; ?>

        </div>
    </nav>

</body>

</html>