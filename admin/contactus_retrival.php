<?php
session_start();
require_once "../config/config.php";

$message = "";
$message_type = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_id'])) {
    $id = (int)$_POST['delete_id']; 

    if (isset($_POST['email']) && isset($_POST['telephone'])) {
        $email = $_POST['email'];
        $telephone = $_POST['telephone'];

        $stmt = $conn->prepare("DELETE FROM contact WHERE email = ? AND telephone = ? LIMIT 1");
        $stmt->bind_param("ss", $email, $telephone);

        if ($stmt->execute()) {
            $message = "Message deleted successfully.";
            $message_type = "success";
        } else {
            $message = "Error deleting message: " . $conn->error;
            $message_type = "error";
        }
        $stmt->close();
    }

    // Redirect to avoid resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch all messages
$result = $conn->query("SELECT * FROM contact ORDER BY fullname ASC");
$messages = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Contact Messages</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --primary-dark: #3a56d4;
            --success: #4cc9f0;
            --danger: #f72585;
            --danger-dark: #e11d74;
            --light: #f8f9fa;
            --dark: #212529;
            --gray: #6c757d;
            --light-gray: #e9ecef;
            --border: #dee2e6;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --radius: 12px;
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
            color: var(--dark);
            line-height: 1.6;
            padding: 20px;
            min-height: 100vh;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
        }

        h1 {
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(90deg, var(--primary), var(--success));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 10px;
        }

        .subtitle {
            color: var(--gray);
            font-size: 1.1rem;
        }

        .alert {
            padding: 16px 20px;
            margin-bottom: 25px;
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: var(--shadow);
            animation: fadeIn 0.5s ease;
        }

        .alert i {
            font-size: 1.4rem;
        }

        .alert.success {
            background: #e6f7ee;
            color: #0d8a54;
            border: 1px solid #c1eacc;
        }

        .alert.error {
            background: #fdf2f5;
            color: #c53030;
            border: 1px solid #f0c0c9;
        }

        .card {
            background: white;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            margin-bottom: 30px;
        }

        .card-header {
            padding: 20px 25px;
            background: var(--light);
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-title {
            font-size: 1.4rem;
            font-weight: 600;
            color: var(--dark);
        }

        .message-count {
            background: var(--primary);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .table-container {
            overflow-x: auto;
            padding: 0 25px 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th {
            background: var(--light);
            padding: 16px 12px;
            text-align: left;
            font-weight: 600;
            color: var(--gray);
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            position: sticky;
            top: 0;
        }

        td {
            padding: 16px 12px;
            border-bottom: 1px solid var(--border);
            vertical-align: top;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover td {
            background-color: rgba(67, 97, 238, 0.03);
        }

        .message-cell {
            max-width: 300px;
            word-wrap: break-word;
        }

        .btn {
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.95rem;
        }

        .btn-delete {
            background: var(--danger);
            color: white;
        }

        .btn-delete:hover {
            background: var(--danger-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(247, 37, 133, 0.3);
        }

        .btn-delete:active {
            transform: translateY(0);
        }

        .no-messages {
            text-align: center;
            padding: 60px 20px;
            color: var(--gray);
            font-style: italic;
            font-size: 1.2rem;
        }

        .no-messages i {
            font-size: 3rem;
            margin-bottom: 20px;
            color: var(--light-gray);
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            margin-top: 20px;
            transition: var(--transition);
        }

        .back-link:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            h1 {
                font-size: 2rem;
            }
            
            .card-header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
            
            .table-container {
                padding: 0 15px 15px;
            }
            
            th, td {
                padding: 12px 8px;
            }
            
            .message-cell {
                max-width: 200px;
            }
            
            .btn {
                padding: 6px 12px;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 15px;
            }
            
            h1 {
                font-size: 1.7rem;
            }
            
            .message-cell {
                max-width: 150px;
            }
            
            th, td {
                padding: 10px 6px;
                font-size: 0.9rem;
            }
            
            .btn {
                padding: 5px 10px;
                font-size: 0.85rem;
            }
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeIn 0.5s ease;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1><i class="fas fa-envelope-open-text"></i> Contact Messages</h1>
            <p class="subtitle">Manage messages from your website visitors</p>
        </header>

        <?php if ($message): ?>
            <div class="alert <?php echo htmlspecialchars($message_type); ?> fade-in">
                <i class="fas <?php echo $message_type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <div class="card-title">Messages</div>
                <div class="message-count"><?php echo count($messages); ?> messages</div>
            </div>
            
            <?php if (empty($messages)): ?>
                <div class="no-messages">
                    <i class="fas fa-inbox"></i>
                    <p>No contact messages found.</p>
                </div>
            <?php else: ?>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Full Name</th>
                                <th>Telephone</th>
                                <th>Email</th>
                                <th>Message</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($messages as $msg): ?>
                                <tr class="fade-in">
                                    <td><?php echo htmlspecialchars($msg['fullname']); ?></td>
                                    <td><?php echo htmlspecialchars($msg['telephone']); ?></td>
                                    <td><?php echo htmlspecialchars($msg['email']); ?></td>
                                    <td class="message-cell"><?php echo htmlspecialchars($msg['message_t']); ?></td>
                                    <td>
                                        <form method="POST" onsubmit="return confirm('Are you sure you want to delete this message?');">
                                            <input type="hidden" name="email" value="<?php echo htmlspecialchars($msg['email']); ?>">
                                            <input type="hidden" name="telephone" value="<?php echo htmlspecialchars($msg['telephone']); ?>">
                                            <button type="submit" name="delete_id" value="1" class="btn btn-delete">
                                                <i class="fas fa-trash-alt"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <a href="javascript:history.back()" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Admin Dashboard
        </a>
    </div>

    <script>
        // Add subtle hover effect to table rows
        document.querySelectorAll('tbody tr').forEach(row => {
            row.addEventListener('mouseenter', () => {
                row.style.transform = 'translateX(5px)';
                row.style.transition = 'transform 0.2s ease';
            });
            
            row.addEventListener('mouseleave', () => {
                row.style.transform = 'translateX(0)';
            });
        });
    </script>
</body>
</html>