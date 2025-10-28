<?php
// includes/config.php
$host = 'localhost';
$db = 'festora_db';
$user = 'root';     // Change if needed
$pass = '';         // Change if needed

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>