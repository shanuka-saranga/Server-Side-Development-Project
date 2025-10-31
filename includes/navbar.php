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

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            padding-top: 50px;
            font-family: Arial, sans-serif;
        }

        .navbar-container {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background-color: #2d2d2d;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .navbar-logo {
            display: flex;
            align-items: center;
            color: #ff6b00;
            font-size: 24px;
            font-weight: bold;
            text-decoration: none;
        }

        .navbar-logo img {
            width: 32px;
            height: 32px;
            margin-right: 8px;
        }

        .navbar-menu {
            display: flex;
            gap: 30px;
            list-style: none;
        }

        .navbar-menu>li {
            position: relative;
            /* Needed for absolute submenu */
        }

        .navbar-menu a {
            color: white;
            text-decoration: none;
            font-size: 16px;
            padding: 8px 0;
            transition: color 0.3s;
        }

        .navbar-menu a:hover,
        .navbar-menu a.active {
            color: #ff6b00;
        }

        /* AUTH BUTTONS */
        .navbar-auth {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .btn-auth {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            text-decoration: none;
            transition: background 0.3s;
            margin-right: 5px;
        }

        .btn-auth:hover {
            background-color: #0056b3;
        }

        .btn-auth.signup {
            background-color: #28a745;
        }

        .btn-auth.signup:hover {
            background-color: #218838;
        }

        /* USER PROFILE */
        .user-profile {
            color: white;
            font-weight: bold;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-right: 10px;
        }

        .user-profile img {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #ff6b00;
        }

        /* DROPDOWN SUBMENU */
        .navbar-menu .submenu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background-color: #2d2d2d;
            min-width: 180px;
            list-style: none;
            padding: 10px 0;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            z-index: 1000;
            border-radius: 4px;
        }

        .navbar-menu>li:hover>.submenu {
            display: block;
        }

        .navbar-menu .submenu li a {
            display: block;
            color: white;
            padding: 10px 20px;
            font-size: 15px;
            text-decoration: none;
            transition: background 0.3s, color 0.3s;
        }

        .navbar-menu .submenu li a:hover,
        .navbar-menu .submenu li a.active {
            background-color: #ff6b00;
            color: white;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .navbar-container {
                flex-direction: column;
                padding: 15px;
            }

            .navbar-menu {
                margin: 15px 0;
                flex-wrap: wrap;
                justify-content: center;
                gap: 20px;
            }

            .navbar-auth {
                gap: 15px;
            }

            body {
                padding-top: 140px;
            }

            /* Mobile: show submenu on tap (optional improvement) */
            .navbar-menu .submenu {
                position: static;
                box-shadow: none;
                background: #3a3a3a;
            }

            .navbar-menu>li:hover>.submenu {
                display: none;
            }

            .navbar-menu>li:active>.submenu,
            .navbar-menu>li:focus-within>.submenu {
                display: block;
            }
        }
    </style>
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

            <li><a href="Public/Review.php">Reviews</a></li>
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