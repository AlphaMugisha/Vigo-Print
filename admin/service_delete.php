<?php
// admin/service_delete.php
session_start();
require_once '../includes/db.php';

// Security Lock
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Check if an ID was passed in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Delete from database
    $stmt = $pdo->prepare("DELETE FROM services WHERE id = :id");
    $stmt->execute(['id' => $id]);
}

// Redirect back to the services page with a success message
header("Location: services.php?msg=deleted");
exit;
?>