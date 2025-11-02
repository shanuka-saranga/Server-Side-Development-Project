<?php
require_once '../config/config.php';

// Delete record if requested
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $conn->query("DELETE FROM team_selections WHERE organizer_id = $delete_id");
    header("Location: organizers_admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel - Team Selections</title>
    <link rel="stylesheet" href="../CommonCSS/allnav&footer.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f5f7;
            color: #333;
        }

        h2 {
            text-align: center;
            margin: 30px 0;
            color: #ff6b00;
        }

        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            padding: 12px 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: linear-gradient(90deg, #ff9c33, #ff6b00);
            color: #fff;
        }

        tr:hover {
            background-color: #f8f8f8;
        }

        .action-btn {
            padding: 6px 12px;
            margin: 2px;
            border: none;
            border-radius: 6px;
            color: white;
            cursor: pointer;
            font-weight: 500;
        }

        .delete-btn {
            background: #dc3545;
        }

        .delete-btn:hover {
            background: #c82333;
        }

        .back-btn {
            display: block;
            width: 200px;
            margin: 30px auto;
            text-align: center;
            background: #333;
            color: white;
            padding: 12px;
            border-radius: 8px;
            text-decoration: none;
            transition: 0.3s;
        }

        .back-btn:hover {
            background: #555;
        }

        .no-data {
            text-align: center;
            color: gray;
            padding: 40px;
        }

        .container {
            margin-bottom: 40px;
        }
    </style>
</head>
<body>

<?php include_once '../includes/navbar.php'; ?>

<h2>Admin Panel – Team Selections</h2>

<div class="container">
<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Coordinator</th>
        <th>Creative Directors</th>
        <th>Technical Leads</th>
        <th>Actions</th>
    </tr>

    <?php
    $result = $conn->query("SELECT * FROM team_selections");

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['organizer_id']}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['email']}</td>
                    <td>{$row['phone']}</td>
                    <td>{$row['coordinator']}</td>
                    <td>{$row['creative']}</td>
                    <td>{$row['technical']}</td>
                    <td>
                        <button class='action-btn delete-btn' onclick=\"if(confirm('Delete this record?')) window.location.href='organizers_admin.php?delete={$row['organizer_id']}'\">Delete</button>
                    </td>
                 </tr>";
        }
    } else {
        echo "<tr><td colspan='8' class='no-data'>No team selections found.</td></tr>";
    }

    $conn->close();
    ?>
</table>
</div>

<a href="../Organizers/Organizers.php" class="back-btn">← Back to Team Page</a>

<?php include_once '../includes/footer.php'; ?>
</body>
</html>