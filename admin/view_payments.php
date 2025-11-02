<?php
require_once '../config/config.php';

// Delete record if requested
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $conn->query("DELETE FROM payment WHERE payment_id = $delete_id");
    header("Location: view_payments.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel - Payments</title>
    <link rel="stylesheet" href="../Public/assests/css/payment.css">

    <style>
        body {
            background: #f5f5f5;
            font-family: 'Poppins', sans-serif;
        }

        h2 {
            text-align: center;
            margin-top: 40px;
            color: #ff6b00;
        }

        table {
            width: 90%;
            margin: 40px auto;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
            border-radius: 10px;
            overflow: hidden;
        }

        th,td {
            padding: 12px 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        td {
            color:black;
        }

        th {
            background: linear-gradient(90deg, #ff9c33, #ff6b00);
            color: white;
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

        .edit-btn {
            background: #007bff;
        }

        .delete-btn {
            background: #dc3545;
        }

        .delete-btn:hover {
            background: #c82333;
        }

        .edit-btn:hover {
            background: #0056b3;
        }

        .back-btn {
            display: block;
            width: 200px;
            margin: 20px auto;
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
    </style>
</head>
<body>

<?php include_once '../includes/navbar.php'; ?>

<h2>Admin Panel – Payment Records</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Email</th>
        <th>Method</th>
        <th>Amount</th>
        <th>Date</th>
        <th>Note</th>
        <th>Package</th>
        <th>Actions</th>
    </tr>

    <?php
    $result = $conn->query("SELECT * FROM payment");

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['payment_id']}</td>
                    <td>{$row['email']}</td>
                    <td>{$row['payment_method']}</td>
                    <td>\${$row['amount']}</td>
                    <td>{$row['payment_date']}</td>
                    <td>{$row['note']}</td>
                    <td>{$row['package']}</td>
                    <td>
                        <button class='action-btn edit-btn' onclick=\"window.location.href='edit_payment.php?id={$row['payment_id']}'\">Edit</button>
                        <button class='action-btn delete-btn' onclick=\"if(confirm('Are you sure you want to delete this payment?')) window.location.href='view_payments.php?delete={$row['payment_id']}'\">Delete</button>
                    </td>
                 </tr>";
        }
    } else {
        echo "<tr><td colspan='8' class='no-data'>No payment records found</td></tr>";
    }

    $conn->close();
    ?>
</table>

<a href="dashboard.php" class="back-btn">← Back to Admin Dashboard</a>

<?php include_once '../includes/footer.php'; ?>
</body>
</html>