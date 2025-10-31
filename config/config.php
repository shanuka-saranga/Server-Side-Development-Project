<?php
$host = "localhost";
$user = "root";  // your XAMPP username
$pass = "";      // usually empty
$dbname = "festora_db"; // your database name

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>