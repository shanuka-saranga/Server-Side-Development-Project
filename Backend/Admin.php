<?php
// Get the current view. Default to 'dashboard'.
$view = $_GET['view'] ?? 'dashboard';

// --- HANDLE USER DELETE ACTION ---
// This code MUST run before any HTML is sent
if ($view == 'users' && isset($_GET['delete_id']) && !empty($_GET['delete_id'])) {
    
    // IMPORTANT: Make sure this path is correct. 
    // Based on your error, it should be '../db_config.php'
    require_once 'database.php'; 
    
    $user_id_to_delete = $_GET['delete_id'];
    
    // Prepare a DELETE statement
    $sql_delete = "DELETE FROM user WHERE user_id = ?";
    
    if ($stmt = $conn->prepare($sql_delete)) {
        // Bind variables
        $stmt->bind_param("i", $user_id_to_delete);
        
        // Execute the statement
        $stmt->execute();
        
        // Close statement and connection
        $stmt->close();
        $conn->close();
        
        // Redirect to the same page to refresh the list and clear the URL
        header("Location: admin.php?view=users");
        exit(); // Stop script execution
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FESTORA Admin Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { width: 90%; margin: 20px auto; }
        header { background: #333; color: #fff; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        header h1 { margin: 0; }
        
        .content { 
            background: #fff; 
            padding: 20px; 
            border-radius: 0 0 8px 8px; /* Rounded bottom corners */
            box-shadow: 0 0 10px rgba(0,0,0,0.1); 
        }
        .content h2 { margin-top: 0; }
        .content p { font-size: 1.1em; }

        /* Dashboard Buttons */
        .dashboard-buttons {
            display: flex; flex-wrap: wrap; gap: 20px;
            margin-top: 25px; padding-top: 20px; border-top: 1px solid #eee;
        }
        .dash-btn {
            flex-basis: 200px; flex-grow: 1; height: 120px;
            display: flex; align-items: center; justify-content: center;
            text-decoration: none; color: white; font-size: 1.2em;
            font-weight: bold; text-align: center; border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        .dash-btn:hover {
            transform: translateY(-5px); box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }
        .btn-users { background-color: #42A5F5; }
        .btn-events { background-color: #66BB6A; }
        .btn-organizers { background-color: #FFA726; }
        .btn-bookings { background-color: #EF5350; }
        .btn-payments { background-color: #AB47BC; }
        .btn-reviews { background-color: #FF7043; }
        .btn-appointments { background-color: #78909C; }
        .btn-add-event { background-color: #26A69A; }

        /* Logout Button */
        .logout-container {
            margin-top: 30px; padding-top: 20px;
            border-top: 1px solid #eee; text-align: right;
        }
        .btn-danger {
            display: inline-block; padding: 12px 20px; font-size: 16px;
            font-weight: bold; color: #fff; background-color: #f44336;
            border: none; border-radius: 5px; text-decoration: none; cursor: pointer;
        }
        .btn-danger:hover { background-color: #da190b; }

        /* --- STYLES FOR USER TABLE --- */
        table {
            width: 100%; border-collapse: collapse;
            margin: 20px 0; box-shadow: 0 0 10px rgba(0,0,0,0.1);
            background: #fff;
        }
        th, td {
            padding: 12px 15px; border: 1px solid #ddd; text-align: left; vertical-align: middle;
        }
        th {
            background-color: #4CAF50; /* User table color */
            color: white;
        }
        tr:nth-child(even) { background-color: #f2f2f2; }
        tr:hover { background-color: #ddd; }
        .back-link {
            display: block; margin-bottom: 20px; text-decoration: none;
            font-weight: bold; color: #333;
        }
        .back-link:hover { color: #4CAF50; }
        
        /* --- NEW DELETE BUTTON STYLE --- */
        .btn-delete {
            display: inline-block; color: #fff; background-color: #f44336;
            padding: 5px 10px; text-decoration: none;
            border-radius: 4px; font-size: 0.9em; font-weight: bold;
        }
        .btn-delete:hover { background-color: #da190b; }

    </style>
</head>
<body>

    <div class="container">
        <header>
            <h1>FESTORA Admin Dashboard</h1>
        </header>

        <div class="content">

            <?php
            // PHP logic to switch between views
            
            if ($view == 'users'):
                // --- THIS IS THE UPDATED USER MANAGEMENT VIEW ---
                
                // 1. Include config and connect
                // Make sure this path is correct!
                require_once 'database.php'; 

                // 2. SQL query
                $sql = "SELECT user_id, name, email, phone, role, created_at FROM user";
                $result = $conn->query($sql);
            ?>

                <h1>User Management</h1>
                <a href="admin.php" class="back-link">&larr; Back to Admin Dashboard</a>

                <table>
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th>Date Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result && $result->num_rows > 0) {
                            // Output data of each row
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['user_id']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['role']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                                
                                // --- ADDED ACTIONS CELL ---
                                echo "<td>";
                                echo "<a href='admin.php?view=users&delete_id=" . htmlspecialchars($row['user_id']) . "' ";
                                echo "class='btn-delete' ";
                                echo "onclick=\"return confirm('Are you sure you want to delete this user?');\">Delete</a>";
                                echo "</td>";
                                // --- END OF ACTIONS CELL ---

                                echo "</tr>";
                            }
                        } else {
                            // --- UPDATED COLSPAN TO 7 ---
                            echo "<tr><td colspan='7' style='text-align:center;'>No users found</td></tr>";
                        }
                        // 3. Close connection
                        $conn->close();
                        ?>
                    </tbody>
                </table>

            <?php
            else:
                // --- THIS IS THE DEFAULT DASHBOARD CONTENT ---
            ?>
            
                <h2>Welcome, Admin!</h2>
                <p>Select a task below to get started.</p>

                <div class="dashboard-buttons">
                    <a href="admin.php?view=users" class="dash-btn btn-users">Manage Users</a>
                    
                    <a href="manage_events.php" class="dash-btn btn-events">Manage Events</a>
                    <a href="manage_organizers.php" class="dash-btn btn-organizers">Manage Organizers</a>
                    <a href="view_bookings.php" class="dash-btn btn-bookings">View Bookings</a>
                    <a href="view_payments.php" class="dash-btn btn-payments">View Payments</a>
                    <a href="view_reviews.php" class="dash-btn btn-reviews">View Reviews</a>
                    <a href="view_appointments.php" class="dash-btn btn-appointments">View Appointments</a>
                    <a href="add_event.php" class="dash-btn btn-add-event">Add New Event</a>
                </div>

                <div class="logout-container">
                    <a href="logout.php" class="btn-danger">Log Out</a>
                </div>

            <?php
            endif; 
            // End of view switcher
            ?>

        </div>
    </div>

</body>
</html>