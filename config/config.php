<?php
// Database connection
$servername = "localhost";    // usually localhost
$username = "root";           // your DB username
$password = "";               // your DB password
$dbname = "festora_db";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>