<?php
require_once '../config/config.php';

if (!isset($_GET['id'])) {
    die("Organizer ID not specified.");
}

$id = intval($_GET['id']);

// Fetch the record to edit
$result = $conn->query("SELECT * FROM team_selections WHERE organizer_id = $id");
if (!$result || $result->num_rows === 0) {
    die("Record not found.");
}

$row = $result->fetch_assoc();

// Handle form submission (update record)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $coordinator = $conn->real_escape_string($_POST['coordinator']);
    $creative = $conn->real_escape_string($_POST['creative']);
    $technical = $conn->real_escape_string($_POST['technical']);

    $update = $conn->query("
        UPDATE team_selections 
        SET name='$name', 
            email='$email', 
            phone='$phone', 
            coordinator='$coordinator', 
            creative='$creative', 
            technical='$technical'
        WHERE organizer_id=$id
    ");

    if ($update) {
        header("Location: organizers_admin.php?updated=1");
        exit();
    } else {
        echo "<p style='color:red;text-align:center;'>Error updating record: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Organizer</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f5f7;
            color: #333;
        }

        h2 {
            text-align: center;
            color: #ff6b00;
            margin-top: 40px;
        }

        form {
            width: 50%;
            margin: 40px auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: 600;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        button {
            margin-top: 25px;
            padding: 10px 20px;
            background: #ff6b00;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 15px;
        }

        button:hover {
            background: #e65c00;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #333;
            font-weight: 500;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <?php include_once '../includes/navbar.php'; ?>

    <h2>Edit Organizer</h2>

    <form method="POST">
        <label>Name:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($row['name']) ?>" required>

        <label>Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($row['email']) ?>" required>

        <label>Phone:</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($row['phone']) ?>">

        <label>Coordinator:</label>
        <input type="text" name="coordinator" value="<?= htmlspecialchars($row['coordinator']) ?>">

        <label>Creative Directors:</label>
        <input type="text" name="creative" value="<?= htmlspecialchars($row['creative']) ?>">

        <label>Technical Leads:</label>
        <input type="text" name="technical" value="<?= htmlspecialchars($row['technical']) ?>">

        <button type="submit">Update Record</button>
        <a href="organizers_admin.php" class="back-link">← Back to Admin Panel</a>
    </form>

    <?php include_once '../includes/footer.php'; ?>
</body>

</html>