<?php
session_start();
require_once '../config/config.php';

$errors = [];
$success = '';
$error = '';

// ============== SIGNUP LOGIC ==============
if (isset($_POST['signup'])) {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];

    // Validation
    if (empty($first_name))
        $errors[] = "First name is required.";
    if (empty($last_name))
        $errors[] = "Last name is required.";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL))
        $errors[] = "Valid email required.";
    if (empty($phone))
        $errors[] = "Phone number required.";
    if (empty($password))
        $errors[] = "Password required.";

    if (empty($errors)) {
        // Check if email already exists
        $check = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
        mysqli_stmt_bind_param($check, "s", $email);
        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);

        if (mysqli_stmt_num_rows($check) > 0) {
            $errors[] = "Email already registered.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert = mysqli_prepare($conn, "INSERT INTO users (first_name, last_name, email, phone, password) VALUES (?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($insert, "sssss", $first_name, $last_name, $email, $phone, $hashed_password);

            if (mysqli_stmt_execute($insert)) {
                // Auto login after signup (optional)
                $_SESSION['user_id'] = mysqli_insert_id($conn);
                $_SESSION['user_name'] = $first_name;
                header("Location: ../Public/index.php");
                exit;
            } else {
                $errors[] = "Something went wrong. Please try again.";
            }
        }
    }
}

// ============== LOGIN LOGIC ==============
if (isset($_POST['signin'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Email and password required.";
    } else {
        $stmt = mysqli_prepare($conn, "SELECT id, first_name, last_name, password FROM users WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['first_name'];
            header("Location: ../Public/index.php");
            exit;
        } else {
            $error = "Invalid email or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../Public/assests/css/login.css">
    <title>Festora - Login / Signup</title>
</head>

<body>

    <div class="container" id="container">

        <!-- ===================== SIGN UP ===================== -->
        <div class="form-container sign-up-container">
            <form method="POST">
                <h1>Create Account</h1>

                <?php if (!empty($errors)): ?>
                    <div class="msg error"><?= implode('<br>', $errors) ?></div>
                <?php elseif (!empty($success)): ?>
                    <div class="msg success"><?= $success ?></div>
                <?php endif; ?>

                <input type="text" name="first_name" placeholder="First Name"
                    value="<?= htmlspecialchars($_POST['first_name'] ?? '') ?>" required />
                <input type="text" name="last_name" placeholder="Last Name"
                    value="<?= htmlspecialchars($_POST['last_name'] ?? '') ?>" required />
                <input type="email" name="email" placeholder="Email"
                    value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required />
                <input type="text" name="phone" placeholder="Phone Number"
                    value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>" required />
                <input type="password" name="password" placeholder="Password" required />
                <button type="submit" name="signup">Sign Up</button>
            </form>
        </div>

        <!-- ===================== SIGN IN ===================== -->
        <div class="form-container sign-in-container">
            <form method="POST">
                <h1>Sign In</h1>

                <?php if (!empty($error)): ?>
                    <div class="msg error"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <input type="email" name="email" placeholder="Email" required />
                <input type="password" name="password" placeholder="Password" required />
                <button type="submit" name="signin">Sign In</button>
            </form>
        </div>

        <!-- ===================== OVERLAY ===================== -->
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>Welcome Back!</h1>
                    <p>To keep connected, please login with your personal info</p>
                    <button class="ghost" id="signIn">Sign In</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>Hello, Friend!</h1>
                    <p>Enter your details and start your journey with us</p>
                    <button class="ghost" id="signUp">Sign Up</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ===================== JS ===================== -->
    <script>
        const signUp = document.getElementById('signUp');
        const signIn = document.getElementById('signIn');
        const container = document.getElementById('container');

        signUp.addEventListener('click', () => container.classList.add('right-panel-active'));
        signIn.addEventListener('click', () => container.classList.remove('right-panel-active'));
    </script>

</body>

</html>