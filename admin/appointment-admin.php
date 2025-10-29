<?php
// Database connection
require_once "../config/config.php";

// Branch filter logic
$branchFilter = "";
if (isset($_GET['branch']) && $_GET['branch'] != "All") {
    $branch = mysqli_real_escape_string($conn, $_GET['branch']);
    $branchFilter = "WHERE branch = '$branch'";
}

// Query appointments ordered by date
$sql = "SELECT fname, reason, date, contact, branch FROM appointment $branchFilter ORDER BY date ASC";
$result = mysqli_query($conn, $sql);
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
      background-color: #ff7900;
      color: white;
      text-align: center;
      padding: 20px 0;
      font-size: 24px;
      font-weight: bold;
      letter-spacing: 1px;
    }

    .container {
      margin: 40px auto;
      width: 90%;
      max-width: 1000px;
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
    }

    th, td {
      border: 1px solid #ddd;
      text-align: left;
      padding: 12px;
    }

    th {
      background-color: #ff7900;
      color: white;
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
  </style>
</head>
<body>

  <header>Admin Dashboard - Appointments</header>

  <div class="container">
    <h2>All Booked Appointments</h2>

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
                  <th>Full Name</th>
                  <th>Event Type</th>
                  <th>Date</th>
                  <th>Contact</th>
                  <th>Branch</th>
                </tr>";

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['fname']) . "</td>
                    <td>" . htmlspecialchars($row['reason']) . "</td>
                    <td>" . htmlspecialchars($row['date']) . "</td>
                    <td>" . htmlspecialchars($row['contact']) . "</td>
                    <td>" . htmlspecialchars($row['branch']) . "</td>
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
