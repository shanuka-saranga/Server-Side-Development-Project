<?php
session_start();
include '../config/config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Prepare and execute query
    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($admin = $result->fetch_assoc()) {
        // Check if password is hashed (starts with $2y$ for bcrypt)
        if (substr($admin['password'], 0, 4) === '$2y$') {
            if (password_verify($password, $admin['password'])) {
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_name'] = $admin['username'];
                header("Location: dashboard.php");
                exit;
            }
        } else {
            // Plain text password check (not recommended in production)
            if ($password === $admin['password']) {
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_name'] = $admin['username'];
                header("Location: dashboard.php");
                exit;
            }
        }
    }

    $error = "Invalid username or password.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Festora</title>
    <style>
        body {
            font-family: "Poppins", sans-serif;
            background: linear-gradient(120deg, #1e3c72, #2a5298);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #fff;
        }

        .login-box {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 20px;
            width: 350px;
            text-align: center;
            color: #333;
        }

        .login-box h2 {
            margin-bottom: 20px;
            color: #222;
        }

        .login-box input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 10px;
            border: 1px solid #ccc;
            outline: none;
            font-size: 14px;
        }

        .login-box button {
            width: 100%;
            padding: 12px;
            background: #1e3c72;
            border: none;
            border-radius: 10px;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        .login-box button:hover {
            background: #142850;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .back-home {
            display: block;
            margin-top: 15px;
            font-size: 14px;
            color: #1e3c72;
            text-decoration: none;
        }

        .back-home:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <form class="login-box" method="POST">
        <h2>Admin Login</h2>
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <input type="text" name="username" placeholder="Admin Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
        <a href="../Public/index.php" class="back-home">← Back to Home</a>
    </form>
</body>

</html>