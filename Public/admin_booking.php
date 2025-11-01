

<?php
 require_once '../config/config.php';
 
$message = ''; 

 if (isset($_POST['delete_event'])) {
     $event_id_to_delete = intval($_POST['event_id']);  

     $sql_delete = "DELETE FROM booking WHERE booking_id = ?";

    if ($stmt = $conn->prepare($sql_delete)) {
        $stmt->bind_param("i", $event_id_to_delete);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $message = "✅ Booking ID: **$event_id_to_delete** successfully deleted!";
            } else {
                $message = "❌ Error: Booking ID **$event_id_to_delete** not found.";
            }
        } else {
            $message = "❌ Database execution error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "❌ Database prepare error: " . $conn->error;
    }
}


 
$sql_select = "SELECT 
    booking_id AS id, 
    full_name, 
    email, 
    phone, 
    event_type, 
    location, 
    guest_count, 
    event_start, 
    event_end, 
    event_description, 
    booking_date 
FROM booking 
ORDER BY booking_id DESC";

$result = $conn->query($sql_select); 

if (!$result) {
    die("Database error while fetching: " . $conn->error);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage ALL Bookings (Admin View)</title>
    <link rel="stylesheet" href="../CommonCSS/allnav&footer.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
         
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; color: #333; }
        .container { max-width: 1300px; margin: 20px auto; padding: 20px; background-color: #fff; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { border-bottom: 2px solid #3e64ff; padding-bottom: 10px; margin-bottom: 20px; color: #3e64ff; }
        .booking-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .booking-table th, .booking-table td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 14px; }
        .booking-table th { background-color: #3e64ff; color: white; }
        .booking-table tr:nth-child(even) { background-color: #f9f9f9; }
        .booking-table tr:hover { background-color: #f1f1f1; }
        .delete-section { margin-top: 30px; padding: 20px; background-color: #ffeaea; border: 1px solid #ff0000; border-radius: 5px; }
        .delete-section input[type="number"] { padding: 8px; margin-right: 10px; border: 1px solid #ccc; border-radius: 4px; }
        .delete-section button { background-color: #dc3545; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; transition: background-color 0.3s; }
        .delete-section button:hover { background-color: #c82333; }
        .message { padding: 10px; margin-bottom: 15px; border-radius: 5px; font-weight: bold; }
        .success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>

    <div class="container">
        <?php if (!empty($message)): ?>
            <div class="message <?php echo strpos($message, '✅') !== false ? 'success' : 'error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <h2>All Event Bookings Management 📊</h2>

        <?php if ($result && $result->num_rows > 0): ?>
            <table class="booking-table">
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Event Type</th>
                        <th>Location</th>
                        <th>Guests</th>
                        <th>Booking Date</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td>**<?php echo htmlspecialchars($row['id']); ?>**</td> 
                            <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                            <td><?php echo htmlspecialchars($row['event_type']); ?></td>
                            <td><?php echo htmlspecialchars($row['location']); ?></td>
                            <td><?php echo htmlspecialchars($row['guest_count']); ?></td>
                            <td><?php echo htmlspecialchars($row['booking_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['event_start']); ?></td>
                            <td><?php echo htmlspecialchars($row['event_end']); ?></td>
                            <td><?php echo htmlspecialchars(substr($row['event_description'], 0, 50)) . '...'; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <?php $result->free(); ?>
        <?php else: ?>
            <p>No bookings have been made yet.</p>
        <?php endif; ?>

        <hr>
        
        <div class="delete-section">
            <h3>Delete Any Event Booking</h3>
            <p>Enter the **Booking ID** from the table above to permanently delete the record.</p>
            <form method="post" action="">
                <label for="event_id">Booking ID:</label>
                <input type="number" id="event_id" name="event_id" placeholder="Enter Booking ID" required min="1">
                <button type="submit" name="delete_event" onclick="return confirm('Are you sure you want to delete Booking ID ' + document.getElementById('event_id').value + '? This action cannot be undone.');">
                    <i class="fa fa-trash"></i> Delete Booking
                </button>
            </form>
        </div>

    </div>

</body>
</html>

<?php 
 if (isset($conn)) {
    $conn->close();
}
 ?>