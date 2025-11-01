<?php
// Database connection
require_once "../config/config.php";

// Delete appointment logic
if (isset($_GET['delete_id'])) {
    $delete_id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $delete_sql = "DELETE FROM appointment WHERE appointment_id = '$delete_id'";
    
    if (mysqli_query($conn, $delete_sql)) {
        $delete_message = "<script>alert('Appointment deleted successfully');</script>";
        // Redirect to avoid resubmission
        header("Location: appointment-admin.php?branch=" . ($_GET['branch'] ?? 'All'));
        exit();
    } else {
        $delete_message = "<script>alert('Error deleting appointment');</script>";
    }
}

// Branch filter logic
$branchFilter = "";
if (isset($_GET['branch']) && $_GET['branch'] != "All") {
    $branch = mysqli_real_escape_string($conn, $_GET['branch']);
    $branchFilter = "WHERE branch = '$branch'";
}

// Query all appointments with all columns
$sql = "SELECT appointment_id, user_id, fname, reason, date, contact, branch, created_at FROM appointment $branchFilter ORDER BY date ASC";
$result = mysqli_query($conn, $sql);

// Check if query was successful
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard | Appointments</title>

  <!-- Internal CSS -->
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #fff8f3;
      margin: 0;
      padding: 0;
    }

    header {
      background-color: #2d2d2d;
      color: white;
      text-align: center;
      padding: 20px 0;
      font-size: 24px;
      font-weight: bold;
      letter-spacing: 1px;
    }

    .container {
      margin: 40px auto;
      width: 95%;
      max-width: 1200px;
      background: white;
      padding: 25px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      border-radius: 10px;
    }

    h2 {
      text-align: center;
      color: #333;
      margin-bottom: 20px;
    }

    /* Filter buttons */
    .filter-buttons {
      text-align: center;
      margin-bottom: 25px;
    }

    .filter-buttons a {
      display: inline-block;
      background-color: #ff7900;
      color: white;
      text-decoration: none;
      padding: 10px 20px;
      margin: 5px;
      border-radius: 5px;
      transition: background-color 0.2s;
      font-weight: bold;
    }

    .filter-buttons a:hover {
      background-color: #e66f00;
    }

    table {
      border-collapse: collapse;
      width: 100%;
      font-size: 14px;
    }

    th, td {
      border: 1px solid #ddd;
      text-align: left;
      padding: 10px;
    }

    th {
      background-color: #ff7900;
      color: white;
      position: sticky;
      top: 0;
    }

    tr:nth-child(even) {
      background-color: #fff2e6;
    }

    tr:hover {
      background-color: #ffe5cc;
    }

    .no-data {
      text-align: center;
      color: gray;
      font-style: italic;
      padding: 20px;
    }

    .delete-btn {
      background-color: #dc3545;
      color: white;
      border: none;
      padding: 6px 12px;
      border-radius: 20px;
      cursor: pointer;
      font-size: 12px;
      text-decoration: none;
      display: inline-block;
    }

    .delete-btn:hover {
      background-color: #b12130ff;
    }

  </style>
</head>
<body>

  <header>Admin Dashboard - Appointments</header>

  <div class="container">
    <h2>All Booked Appointments</h2>

    <!-- Display delete message -->
    <?php if (isset($delete_message)) echo "<div class='message'>$delete_message</div>"; ?>

    <!-- Filter Buttons -->
    <div class="filter-buttons">
      <a href="appointment-admin.php?branch=All">All</a>
      <a href="appointment-admin.php?branch=Colombo">Colombo</a>
      <a href="appointment-admin.php?branch=Gampaha">Gampaha</a>
      <a href="appointment-admin.php?branch=Kalutara">Kalutara</a>
      <a href="appointment-admin.php?branch=Matara">Matara</a>
    </div>

    <?php
    if (mysqli_num_rows($result) > 0) {
        echo "<table>
                <tr>
                  <th>Appointment ID</th>
                  <th>Full Name</th>
                  <th>Event Type</th>
                  <th>Date</th>
                  <th>Contact</th>
                  <th>Branch</th>
                  <th>Created At</th>
                  <th>Action</th>
                </tr>";

        while ($row = mysqli_fetch_assoc($result)) {
            // Format date for better display
            $formatted_date = date('M j, Y', strtotime($row['date']));
            $formatted_created = date('M j, Y g:i A', strtotime($row['created_at']));
            
            echo "<tr>
                    <td>" . htmlspecialchars($row['appointment_id']) . "</td>
                    <td>" . htmlspecialchars($row['fname']) . "</td>
                    <td>" . htmlspecialchars($row['reason']) . "</td>
                    <td>" . htmlspecialchars($formatted_date) . "</td>
                    <td>" . htmlspecialchars($row['contact']) . "</td>
                    <td>" . htmlspecialchars($row['branch']) . "</td>
                    <td>" . htmlspecialchars($formatted_created) . "</td>
                    <td>
                      <a href='appointment-admin.php?delete_id=" . $row['appointment_id'] . "&branch=" . ($_GET['branch'] ?? 'All') . "' 
                         class='delete-btn' 
                         onclick=\"return confirm('Are you sure you want to delete this appointment?')\">
                         Delete
                      </a>
                    </td>
                  </tr>";
        }

        echo "</table>";
    } else {
        echo "<div class='no-data'>No appointments found for this branch.</div>";
    }

    mysqli_close($conn);
    ?>
  </div>

</body>
</html>