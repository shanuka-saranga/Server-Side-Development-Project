<?php
session_start();
if (empty($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="../Public/assests/css/navbar.css">
    <link rel="stylesheet" href="../Public/assests/css/navbar.css">
</head>

<body>
    <?php require_once '../includes/navbar.php'; ?>



    <div style="padding: 100px 20px; text-align:center;">
        <h1>Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?>!</h1>
        <p>This is your profile page.</p>
        <a href="../Public/logout.php">Logout</a>
    </div>



    <?php require_once '../includes/footer.php'; ?>
</body>

</html>