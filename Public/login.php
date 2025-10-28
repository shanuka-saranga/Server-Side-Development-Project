<?php
session_start();
require_once '../config/database.php';

// ============== SIGNUP LOGIC ==============
if (isset($_POST['signup'])) {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $errors = [];
    if (empty($first_name))
        $errors[] = "First name is required.";
    if (empty($last_name))
        $errors[] = "Last name is required.";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL))
        $errors[] = "Valid email required.";
    if (empty($phone))
        $errors[] = "Phone number required.";
    if (empty($_POST['password']))
        $errors[] = "Password required.";

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->rowCount() > 0) {
                $errors[] = "Email already registered.";
            } else {
                $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, phone, password) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$first_name, $last_name, $email, $phone, $password]);
                $success = "Account created! Please log in.";
            }
        } catch (Exception $e) {
            $errors[] = "Error: " . $e->getMessage();
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
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
            header("Location: ../index.php");
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

    <!-- Your beautiful CSS -->
</head>

<body>

    <div class="container" id="container" class="<?= !empty($success) ? 'right-panel-active' : '' ?>">

        <!-- ===================== SIGN UP ===================== -->
        <div class="form-container sign-up-container">
            <form method="POST">
                <h1>Create Account</h1>

                <?php if (!empty($errors)): ?>
                    <div class="msg error"><?= implode('<br>', $errors) ?></div>
                <?php elseif (isset($success)): ?>
                    <div class="msg success"><?= $success ?></div>
                <?php endif; ?>

                <input type="text" name="first_name" placeholder="First Name" value="<?= $_POST['first_name'] ?? '' ?>"
                    required />
                <input type="text" name="last_name" placeholder="Last Name" value="<?= $_POST['last_name'] ?? '' ?>"
                    required />
                <input type="email" name="email" placeholder="Email" value="<?= $_POST['email'] ?? '' ?>" required />
                <input type="text" name="phone" placeholder="Phone Number" value="<?= $_POST['phone'] ?? '' ?>"
                    required />
                <input type="password" name="password" placeholder="Password" required />
                <button type="submit" name="signup">Sign Up</button>
            </form>
        </div>

        <!-- ===================== SIGN IN ===================== -->
        <div class="form-container sign-in-container">
            <form method="POST">
                <h1>Sign In</h1>

                <?php if (isset($error)): ?>
                    <div class="msg error"><?= $error ?></div>
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