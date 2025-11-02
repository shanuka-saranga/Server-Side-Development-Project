<?php
require_once '../config/config.php';

// Handle delete
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $conn->query("DELETE FROM admin WHERE id = $delete_id");
    header("Location: manage_admins.php?deleted=1");
    exit();
}

// Handle add new admin
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_admin'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $exists = $conn->query("SELECT id FROM admin WHERE username='$username'");
    if ($exists && $exists->num_rows > 0) {
        $error = "Username already exists!";
    } else {
        $conn->query("INSERT INTO admin (username, password) VALUES ('$username', '$password')");
        header("Location: manage_admins.php?added=1");
        exit();
    }
}

// Handle edit (update admin password)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_admin'])) {
    $id = intval($_POST['id']);
    $username = $conn->real_escape_string($_POST['username']);
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

    if ($password) {
        $conn->query("UPDATE admin SET username='$username', password='$password' WHERE id=$id");
    } else {
        $conn->query("UPDATE admin SET username='$username' WHERE id=$id");
    }
    header("Location: manage_admins.php?updated=1");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Admin Users</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f5f7;
            color: #333;
        }

        h2 {
            text-align: center;
            color: #ff6b00;
            margin: 30px 0;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        th,
        td {
            padding: 12px 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: linear-gradient(90deg, #ff9c33, #ff6b00);
            color: #fff;
        }

        .action-btn {
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            color: white;
            cursor: pointer;
        }

        .delete-btn {
            background: #dc3545;
        }

        .delete-btn:hover {
            background: #c82333;
        }

        .edit-btn {
            background: #007bff;
        }

        .edit-btn:hover {
            background: #0056b3;
        }

        form {
            width: 60%;
            margin: 30px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        button {
            background: #ff6b00;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background: #e65c00;
        }

        a.back {
            display: block;
            width: 200px;
            margin: 30px auto;
            text-align: center;
            background: #333;
            color: white;
            padding: 12px;
            border-radius: 8px;
            text-decoration: none;
        }

        a.back:hover {
            background: #555;
        }
    </style>
</head>

<body>
    <?php include_once '../includes/navbar.php'; ?>

    <h2>Manage Admin Users</h2>

    <!-- Add New Admin -->
    <form method="POST">
        <h3>Add New Admin</h3>
        <?php if (isset($error))
            echo "<p style='color:red;text-align:center;'>$error</p>"; ?>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="add_admin">Add Admin</button>
    </form>

    <!-- Existing Admins -->
    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Created</th>
            <th>Actions</th>
        </tr>
        <?php
        $admins = $conn->query("SELECT * FROM admin ORDER BY id ASC");
        if ($admins && $admins->num_rows > 0) {
            while ($row = $admins->fetch_assoc()) {
                echo "
                <tr>
                    <td>{$row['id']}</td>
                    <td>{$row['username']}</td>
                    <td>{$row['created_at']}</td>
                    <td>
                        <button class='action-btn edit-btn' onclick=\"showEditForm({$row['id']}, '{$row['username']}')\">Edit</button>
                        <button class='action-btn delete-btn' onclick=\"if(confirm('Delete this admin?')) window.location.href='manage_admins.php?delete={$row['id']}'\">Delete</button>
                    </td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No admins found.</td></tr>";
        }
        ?>
    </table>

    <!-- Hidden Edit Form -->
    <div id="editForm" style="display:none;">
        <form method="POST" style="width:50%;margin:30px auto;">
            <h3>Edit Admin</h3>
            <input type="hidden" name="id" id="edit_id">
            <input type="text" name="username" id="edit_username" required>
            <input type="password" name="password" placeholder="New Password (leave blank to keep same)">
            <button type="submit" name="edit_admin">Update Admin</button>
            <button type="button" onclick="document.getElementById('editForm').style.display='none'">Cancel</button>
        </form>
    </div>

    <a href="dashboard.php" class="back">← Back to Dashboard</a>

    <script>
        function showEditForm(id, username) {
            document.getElementById('editForm').style.display = 'block';
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_username').value = username;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    </script>

    <?php include_once '../includes/footer.php'; ?>
</body>

</html>