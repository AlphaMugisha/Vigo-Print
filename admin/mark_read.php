<?php
// admin/mark_read.php
session_start();
require_once '../includes/db.php';

// Security check
if (!isset($_SESSION['admin_logged_in'])) {
    exit;
}

$id = $_GET['id'] ?? 0;

if ($id) {
    // Update the database to set this message as read (1)
    $stmt = $pdo->prepare("UPDATE contact_inbox SET is_read = 1 WHERE id = ?");
    $stmt->execute([$id]);
}

// Send the admin right back to the inbox
header("Location: dashboard.php?section=inbox");
exit;