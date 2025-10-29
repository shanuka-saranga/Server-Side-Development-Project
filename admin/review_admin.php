<?php
// Backend/display_reviews.php - Display reviews in table format with delete
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
require_once '../config.php';

// Handle delete action
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    $delete_sql = "DELETE FROM review WHERE review_id = $delete_id";
    $delete_result = mysqli_query($conn, $delete_sql);
    
    if ($delete_result) {
        $delete_message = "<div class='alert success'>Review deleted successfully!</div>";
    } else {
        $delete_message = "<div class='alert error'>Error deleting review: " . mysqli_error($conn) . "</div>";
    }
}

// Retrieve reviews from database - CORRECTED COLUMN NAME
$sql = "SELECT review_id, name, rating, comment, event_name, created_at, recommend 
        FROM review 
        ORDER BY created_at DESC 
        LIMIT 50";
$result = mysqli_query($conn, $sql);

// Using mysqli_num_rows() as shown in PDF (page 16-17)
$NumRows = mysqli_num_rows($result);

// Check if query was successful
if ($result === FALSE) {
    echo "<div class='alert error'>Database error: " . mysqli_error($conn) . "</div>";
    $NumRows = 0;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>Reviews Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        
        .alert {
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
        }
        
        .alert.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background-color: #343a40;
            color: white;
            font-weight: bold;
        }
        
        tr:hover {
            background-color: #f5f5f5;
        }
        
        .rating-stars {
            color: #FFD700;
            font-size: 14px;
        }
        
        .comment-cell {
            max-width: 300px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .comment-cell:hover {
            white-space: normal;
            overflow: visible;
            background-color: #f8f9fa;
        }
        
        .btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 12px;
            display: inline-block;
        }
        
        .btn-delete {
            background-color: #dc3545;
            color: white;
        }
        
        .btn-delete:hover {
            background-color: #c82333;
        }
        
        .no-reviews {
            text-align: center;
            padding: 40px;
            color: #666;
            font-size: 18px;
        }
        
        .stats {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .header-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        .btn-refresh {
            background-color: #28a745;
            color: white;
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 5px;
        }
        
        .btn-refresh:hover {
            background-color: #218838;
        }
        
        .debug-info {
            background: #e9ecef;
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
            font-family: monospace;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-actions">
            <h1>📋 Customer Reviews</h1>
            <a href="?refresh" class="btn-refresh">🔄 Refresh</a>
        </div>
        
        <?php if (isset($delete_message)) echo $delete_message; ?>
        
        <!-- Debug information (you can remove this in production) -->
        <?php if (isset($_GET['debug'])): ?>
        <div class="debug-info">
            <strong>Debug Info:</strong><br>
            Query: <?php echo htmlspecialchars($sql); ?><br>
            Rows Found: <?php echo $NumRows; ?><br>
            Error: <?php echo mysqli_error($conn); ?>
        </div>
        <?php endif; ?>
        
        <div class="stats">
            <strong>Total Reviews Displayed:</strong> <?php echo $NumRows; ?> 
            <?php if ($NumRows >= 50): ?>
                | <em>(Showing latest 50 reviews)</em>
            <?php endif; ?>
        </div>
        
        <?php if ($result && $NumRows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Rating</th>
                        <th>Comment</th>
                        <th>Event</th>
                        <th>Recommend</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><strong>#<?php echo $row['review_id']; ?></strong></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td>
                                <span class="rating-stars">
                                    <?php
                                    // Display stars based on rating
                                    for ($i = 1; $i <= 5; $i++) {
                                        echo $i <= $row['rating'] ? '★' : '☆';
                                    }
                                    ?>
                                </span>
                                <br>
                                <small>(<?php echo $row['rating']; ?>/5)</small>
                            </td>
                            <td class="comment-cell" title="<?php echo htmlspecialchars($row['comment']); ?>">
                                <?php echo htmlspecialchars($row['comment']); ?>
                            </td>
                            <td>
                                <?php if (!empty($row['event_name'])): ?>
                                    <?php echo htmlspecialchars($row['event_name']); ?>
                                <?php else: ?>
                                    <span style="color: #6c757d;">N/A</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($row['recommend'] === 'yes'): ?>
                                    <span style="color: green; font-weight: bold;">✓ Yes</span>
                                <?php else: ?>
                                    <span style="color: red; font-weight: bold;">✗ No</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo date('M j, Y', strtotime($row['created_at'])); ?></td>
                            <td>
                                <a href="?delete_id=<?php echo $row['review_id']; ?>" 
                                   class="btn btn-delete" 
                                   onclick="return confirm('Are you sure you want to delete review from <?php echo htmlspecialchars($row['name']); ?>?')">
                                    🗑️ Delete
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            
            <div style="text-align: center; margin-top: 20px; color: #666;">
                <p><strong><?php echo $NumRows; ?></strong> review(s) displayed</p>
            </div>
            
        <?php else: ?>
            <div class="no-reviews">
                <p>📭 No reviews found in the database.</p>
                <p>Customer reviews will appear here once they are submitted through the review form.</p>
                <?php if ($result === FALSE): ?>
                    <p style="color: red; margin-top: 10px;">
                        <strong>Database Error:</strong> Please check if the 'review' table exists and has the correct structure.
                    </p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <div style="text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd;">
            <p>
                <a href="../Reviews/review.php" style="color: #007bff; text-decoration: none;">← Back to Review Form</a> |
                <a href="?debug" style="color: #6c757d; text-decoration: none;">Debug Info</a>
            </p>
        </div>
    </div>

    <script>
        // Add confirmation for all delete links
        document.addEventListener('DOMContentLoaded', function() {
            const deleteLinks = document.querySelectorAll('.btn-delete');
            deleteLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    if (!confirm('Are you sure you want to delete this review?\nThis action cannot be undone.')) {
                        e.preventDefault();
                    }
                });
            });
        });
        
        // Auto-hide success messages after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.display = 'none';
            });
        }, 5000);
        
        // Refresh page when refresh button is clicked
        document.querySelector('.btn-refresh').addEventListener('click', function(e) {
            e.preventDefault();
            location.reload();
        });
    </script>
</body>
</html>
<?php
// Close database connection as shown in PDF (page 5)
mysqli_close($conn);
?>