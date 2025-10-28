<?php
session_start();

// include the database connection
require_once __DIR__ . '/database.php';

// =======================
// SIGN UP SECTION
// =======================
if (isset($_POST['signup'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if user already exists
    $check = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('User already exists! Please log in.'); window.location='index.html';</script>";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $password);
        if ($stmt->execute()) {
            echo "<script>alert('Registration successful! You can now log in.'); window.location='index.html';</script>";
        } else {
            echo "<script>alert('Error registering user.'); window.location='index.html';</script>";
        }
    }
}

// =======================
// LOGIN SECTION
// =======================
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user'] = $row['name'];
            header("Location: ../Frontend/index.html");
            exit();
        } else {
            echo "<script>alert('Incorrect password!'); window.location='../Frontend/login/signup/login_signup.html';</script>";
        }
    } else {
        echo "<script>alert('User not found! Please sign up.'); window.location='../Frontend/login/signup/login_signup.html';</script>";
    }
}
?>