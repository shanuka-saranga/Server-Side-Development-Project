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
            padding-top: 0;
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
        .hamburger {
            display: none;
            flex-direction: column;
            cursor: pointer;
            gap: 6px;
            background: none;
            border: none;
            color: #ff6b00;
            font-size: 24px;
            padding: 8px;
        }

        .hamburger span {
            width: 25px;
            height: 3px;
            background-color: #ff6b00;
            border-radius: 2px;
            transition: all 0.3s ease;
        }

        .hamburger.active span:nth-child(1) {
            transform: rotate(45deg) translate(8px, 8px);
        }

        .hamburger.active span:nth-child(2) {
            opacity: 0;
        }

        .hamburger.active span:nth-child(3) {
            transform: rotate(-45deg) translate(7px, -7px);
        }

        /* Extra small devices (phones, less than 576px) */
        @media (max-width: 575px) {
            body {
                padding-top: 0;
            }

            .navbar-container {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                padding: 10px 12px;
                height: auto;
            }

            .navbar-logo {
                font-size: 1.2rem;
                white-space: nowrap;
            }

            .navbar-logo img {
                width: 28px;
                height: 28px;
                margin-right: 5px;
            }

            .hamburger {
                display: flex;
                order: 2;
            }

            .navbar-menu {
                position: fixed;
                top: 70px;
                left: -100%;
                flex-direction: column;
                background-color: #2d2d2d;
                width: 100%;
                text-align: center;
                transition: left 0.3s ease;
                list-style: none;
                padding: 20px 0;
                gap: 15px;
                z-index: 999;
                max-height: calc(100vh - 70px);
                overflow-y: auto;
            }

            .navbar-menu.active {
                left: 0;
            }

            .navbar-menu li {
                margin: 10px 0;
            }

            .navbar-menu a {
                display: block;
                padding: 12px 20px;
                font-size: 15px;
            }

            .navbar-auth {
                position: fixed;
                top: 70px;
                right: -100%;
                flex-direction: column;
                background-color: #2d2d2d;
                width: 100%;
                padding: 20px 0;
                text-align: center;
                transition: right 0.3s ease;
                z-index: 999;
            }

            .navbar-auth.active {
                right: 0;
            }

            .btn-auth {
                padding: 10px 20px;
                margin: 5px 0;
                width: 90%;
                margin-left: auto;
                margin-right: auto;
                font-size: 14px;
            }

            .user-profile {
                flex-direction: column;
                margin: 10px 0;
                font-size: 13px;
            }

            .user-profile img {
                width: 35px;
                height: 35px;
            }

            /* Submenu for mobile */
            .navbar-menu .submenu {
                position: static;
                box-shadow: none;
                background-color: #3a3a3a;
                max-height: 0;
                overflow: hidden;
                transition: max-height 0.3s ease;
                padding: 0;
            }

            .navbar-menu li.dropdown.active .submenu {
                max-height: 200px;
                padding: 10px 0;
            }

            .navbar-menu .submenu li a {
                padding: 10px 30px;
                font-size: 14px;
            }
        }

        /* Small devices (landscape phones, 576px to 767px) */
        @media (min-width: 576px) and (max-width: 767px) {
            body {
                padding-top: 0;
            }

            .navbar-container {
                flex-direction: row;
                padding: 12px 15px;
                justify-content: space-between;
            }

            .navbar-logo {
                font-size: 1.4rem;
            }

            .navbar-logo img {
                width: 30px;
                height: 30px;
            }

            .hamburger {
                display: flex;
                order: 2;
            }

            .navbar-menu {
                position: fixed;
                top: 75px;
                left: -100%;
                flex-direction: column;
                background-color: #2d2d2d;
                width: 100%;
                text-align: center;
                transition: left 0.3s ease;
                padding: 15px 0;
                gap: 10px;
                z-index: 999;
            }

            .navbar-menu.active {
                left: 0;
            }

            .navbar-auth {
                position: fixed;
                top: 75px;
                right: -100%;
                flex-direction: column;
                background-color: #2d2d2d;
                width: 100%;
                padding: 15px 0;
                transition: right 0.3s ease;
                z-index: 999;
            }

            .navbar-auth.active {
                right: 0;
            }

            .btn-auth {
                padding: 9px 15px;
                margin: 4px 0;
                font-size: 13px;
            }

            .navbar-menu .submenu {
                position: static;
                background-color: #3a3a3a;
                max-height: 0;
                overflow: hidden;
                transition: max-height 0.3s ease;
                padding: 0;
            }

            .navbar-menu li.dropdown.active .submenu {
                max-height: 180px;
                padding: 10px 0;
            }
        }

        /* Medium devices (tablets, 768px to 991px) */
        @media (min-width: 768px) and (max-width: 991px) {
            body {
                padding-top: 0;
            }

            .navbar-container {
                padding: 12px 25px;
            }

            .hamburger {
                display: none;
            }

            .navbar-menu {
                gap: 20px;
            }

            .navbar-menu a {
                font-size: 15px;
                padding: 8px 0;
            }

            .btn-auth {
                padding: 8px 15px;
                font-size: 13px;
                margin-right: 5px;
            }

            .user-profile {
                font-size: 13px;
            }

            .navbar-menu .submenu {
                min-width: 160px;
            }

            .navbar-menu .submenu li a {
                padding: 8px 15px;
                font-size: 14px;
            }
        }

        /* Large devices (desktops, 992px and up) */
        @media (min-width: 992px) {
            body {
                padding-top: 0;
            }

            .navbar-container {
                padding: 15px 20px;
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
            }

            .hamburger {
                display: none;
            }

            .navbar-logo {
                font-size: 24px;
            }

            .navbar-menu {
                gap: 30px;
                flex-direction: row;
            }

            .navbar-menu a {
                font-size: 16px;
            }

            .navbar-auth {
                display: flex;
                gap: 10px;
                align-items: center;
            }

            .btn-auth {
                padding: 8px 16px;
                font-size: 14px;
            }

            .navbar-menu .submenu {
                min-width: 180px;
            }

            .navbar-menu .submenu li a {
                padding: 10px 20px;
                font-size: 15px;
            }
        }
    </style>
</head>

<body>

    <nav class="navbar-container">
        <!-- Logo -->
        <a href="../index.php" class="navbar-logo">
            <img src="../assests/index/LOGO.png" alt="Logo">
            Festora
        </a>

        <!-- Hamburger Menu -->
        <button class="hamburger" id="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <!-- Menu -->
        <ul class="navbar-menu" id="navbar-menu">
            <li><a href="../Public/index.php" class="<?= $current_page === 'index.php' ? 'active' : '' ?>">Home</a></li>
            <li><a href="../Public/events.php" class="<?= $current_page === 'events.php' ? 'active' : '' ?>">Events</a>
            </li>
            <li><a href="../Public/Contactus.php"
                    class="<?= $current_page === 'Contactus.php' ? 'active' : '' ?>">ContactUs</a></li>
            <li><a href="../Public/organizers.php"
                    class="<?= $current_page === 'organizers.php' ? 'active' : '' ?>">Organizers</a>
            </li>

            <!-- Booking Dropdown -->
            <li class="dropdown" id="booking-dropdown">
                <a href="../Public/Booking.php"
                    class="<?= in_array($current_page, ['Booking.php', '../Public/appointment.php']) ? 'active' : '' ?>">
                    Booking
                </a>
                <ul class="submenu">
                    <li><a href="../Public/appointment.php"
                            class="<?= $current_page === 'appointment.php' ? 'active' : '' ?>">Appointment</a>
                    </li>

                    <li><a href="../Public/Payment.php"
                            class="<?= $current_page === 'Payment.php' ? 'active' : '' ?>">Payment</a></li>
                </ul>

            </li>

            <li><a href="Review.php" class="<?= $current_page === 'Review.php' ? 'active' : '' ?>">Reviews</a></li>
        </ul>

        <!-- RIGHT: AUTH SECTION -->
        <div class="navbar-auth" id="navbar-auth">

            <!-- NOT LOGGED IN -->
            <?php if (!$is_logged_in && !$is_admin_logged_in): ?>
                <div class="auth-buttons">
                    <a href="../Public/login.php" class="btn-auth">User Login</a>
                    <a href="../admin/admin_login.php" class="btn-auth signup">Admin Login</a>
                </div>
            <?php endif; ?>

            <!-- USER LOGGED IN -->
            <?php if ($is_logged_in): ?>
                <div class="navbar-auth">
                    <div class="user-profile">
                        <img src="../Public/assets/images/default-avatar.png" alt="User">
                        <span>Hi, <?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?></span>
                    </div>
                    <a href="profile.php" class="btn-auth">Profile</a>
                    <a href="logout.php" class="btn-auth signup">Logout</a>
                </div>
            <?php endif; ?>

            <!-- ADMIN LOGGED IN -->
            <?php if ($is_admin_logged_in): ?>
                <div class="navbar-auth">
                    <div class="user-profile">
                        <img src="../Public/assets/images/admin-avatar.png" alt="Admin">
                        <span>Admin</span>
                    </div>
                    <a href="../admin/dashboard.php" class="btn-auth">Dashboard</a>
                    <a href="../Public/logout.php" class="btn-auth signup">Logout</a>
                </div>
            <?php endif; ?>

        </div>
    </nav>

    <script>
        // Mobile Menu Toggle
        const hamburger = document.getElementById('hamburger');
        const navbarMenu = document.getElementById('navbar-menu');
        const navbarAuth = document.getElementById('navbar-auth');
        const bookingDropdown = document.getElementById('booking-dropdown');

        // Hamburger menu click
        hamburger.addEventListener('click', function () {
            hamburger.classList.toggle('active');
            navbarMenu.classList.toggle('active');
            navbarAuth.classList.toggle('active');
        });

        // Close menu when a link is clicked
        const navLinks = navbarMenu.querySelectorAll('a');
        navLinks.forEach(link => {
            link.addEventListener('click', function () {
                hamburger.classList.remove('active');
                navbarMenu.classList.remove('active');
                navbarAuth.classList.remove('active');
            });
        });

        // Mobile dropdown toggle for Booking
        if (bookingDropdown) {
            bookingDropdown.addEventListener('click', function (e) {
                if (window.innerWidth <= 767) {
                    e.preventDefault();
                    bookingDropdown.classList.toggle('active');
                }
            });
        }

        // Close dropdown when window is resized to desktop
        window.addEventListener('resize', function () {
            if (window.innerWidth > 767) {
                bookingDropdown.classList.remove('active');
                hamburger.classList.remove('active');
                navbarMenu.classList.remove('active');
                navbarAuth.classList.remove('active');
            }
        });
    </script>

</body>

</html>