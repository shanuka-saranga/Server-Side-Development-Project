<?php
// ------------------- USER MANAGEMENT PAGE -------------------

// --- INCLUDE DATABASE CONNECTION ---
require_once '../config/config.php'; // Adjust path if needed

// --- HANDLE DELETE ACTION ---
if (isset($_GET['delete_id']) && !empty($_GET['delete_id'])) {
    $user_id_to_delete = $_GET['delete_id'];

    $sql_delete = "DELETE FROM user WHERE user_id = ?";
    if ($stmt = $conn->prepare($sql_delete)) {
        $stmt->bind_param("i", $user_id_to_delete);
        $stmt->execute();
        $stmt->close();

        // Redirect to refresh table and clear URL params
        header("Location: User_retrival.php");
        exit();
    }
}

// --- FETCH ALL USERS ---
$sql = "SELECT user_id, name, email, phone, role, created_at FROM user";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users - FESTORA</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background-color: #f4f4f4; 
            margin: 0; padding: 0;
        }

        .container { 
            width: 90%; 
            margin: 30px auto; 
            background: #fff; 
            padding: 20px 30px; 
            border-radius: 10px; 
            box-shadow: 0 0 10px rgba(0,0,0,0.1); 
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 25px;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            text-decoration: none;
            font-weight: bold;
            color: #333;
        }
        .back-link:hover {
            color: #4CAF50;
        }

        /* Table styles */
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
        }

        th, td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) { background-color: #f9f9f9; }
        tr:hover { background-color: #f1f1f1; }

        /* Delete button style */
        .btn-delete {
            background-color: #f44336;
            color: white;
            padding: 6px 10px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 0.9em;
            font-weight: bold;
        }
        .btn-delete:hover {
            background-color: #da190b;
        }

        /* Responsive table */
        @media (max-width: 768px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }
            th {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }
            td {
                border: none;
                position: relative;
                padding-left: 50%;
                text-align: right;
            }
            td:before {
                position: absolute;
                left: 10px;
                width: 45%;
                padding-right: 10px;
                white-space: nowrap;
                font-weight: bold;
                text-align: left;
            }
            td:nth-of-type(1):before { content: "User ID"; }
            td:nth-of-type(2):before { content: "Name"; }
            td:nth-of-type(3):before { content: "Email"; }
            td:nth-of-type(4):before { content: "Phone"; }
            td:nth-of-type(5):before { content: "Role"; }
            td:nth-of-type(6):before { content: "Date Joined"; }
            td:nth-of-type(7):before { content: "Actions"; }
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="admin.php" class="back-link">&larr; Back to Dashboard</a>
        <h1>User Management</h1>

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
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['user_id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['role']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                        echo "<td>
                                <a href='User_retrival.php?delete_id=" . htmlspecialchars($row['user_id']) . "' 
                                   class='btn-delete'
                                   onclick=\"return confirm('Are you sure you want to delete this user?');\">
                                   Delete
                                </a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' style='text-align:center;'>No users found</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
