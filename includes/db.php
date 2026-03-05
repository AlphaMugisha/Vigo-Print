<?php
// includes/db.php

$host = 'localhost';
$dbname = 'print_db'; // The name of the database we will create
$username = 'root';        // Default XAMPP username
$password = '';            // Default XAMPP password (blank)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("ERROR: Could not connect to the database. " . $e->getMessage());
}
?>