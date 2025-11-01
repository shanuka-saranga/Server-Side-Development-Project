<?php
// ------------------- ADMIN DASHBOARD PAGE -------------------
// Default view (dashboard)
$view = $_GET['view'] ?? 'dashboard';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FESTORA Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            width: 90%;
            margin: 20px auto;
        }

        header {
            background: #333;
            color: #fff;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }

        header h1 {
            margin: 0;
            font-size: 28px;
        }

        .content {
            background: #fff;
            padding: 30px;
            border-radius: 0 0 8px 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .content h2 {
            margin-top: 0;
        }

        .content p {
            font-size: 1.1em;
        }

        /* Dashboard Buttons */
        .dashboard-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            justify-content: center;
        }

        .dash-btn {
            flex-basis: 200px;
            flex-grow: 1;
            height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: white;
            font-size: 1.2em;
            font-weight: bold;
            text-align: center;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .dash-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        /* Button colors */
        .btn-users {
            background-color: #42A5F5;
        }

        .btn-events {
            background-color: #66BB6A;
        }

        .btn-organizers {
            background-color: #FFA726;
        }

        .btn-bookings {
            background-color: #EF5350;
        }

        .btn-payments {
            background-color: #AB47BC;
        }

        .btn-reviews {
            background-color: #FF7043;
        }

        .btn-appointments {
            background-color: #78909C;
        }

        .btn-add-event {
            background-color: #26A69A;
        }

        /* Logout Button */
        .logout-container {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            text-align: right;
        }

        .btn-danger {
            display: inline-block;
            padding: 12px 20px;
            font-size: 16px;
            font-weight: bold;
            color: #fff;
            background-color: #f44336;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-danger:hover {
            background-color: #da190b;
        }

        /* Footer */
        footer {
            text-align: center;
            margin-top: 40px;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>

<body>

    <div class="container">
        <header>
            <h1>FESTORA Admin Dashboard</h1>
        </header>

        <div class="content">
            <h2>Welcome, Admin!</h2>
            <p>Manage your event system using the tools below.</p>

            <div class="dashboard-buttons">
                <a href="User_retrival.php" class="dash-btn btn-users">Manage Users</a>
                <a href="manage_events.php" class="dash-btn btn-events">Manage Events</a>
                <a href="manage_organizers.php" class="dash-btn btn-organizers">Manage Organizers</a>
                <a href="../Public/admin_booking.php" class="dash-btn btn-bookings">View Bookings</a>
                <a href="view_payments.php" class="dash-btn btn-payments">View Payments</a>
                <a href="review_admin.php" class="dash-btn btn-reviews">View Reviews</a>
                <a href="appointment-admin.php" class="dash-btn btn-appointments">View Appointments</a>
                <a href="add_event.php" class="dash-btn btn-add-event">Add New Event</a>
            </div>

            <div class="logout-container">
                <a href="../Public/index.php" class="btn-danger">Log Out</a>
            </div>
        </div>

        <footer>
            <p>&copy; <?php echo date("Y"); ?> FESTORA Admin Panel. All rights reserved.</p>
        </footer>
    </div>

</body>

</html>