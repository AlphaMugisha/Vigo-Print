<?php
// admin/delete_item.php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

$type = $_GET['type'] ?? '';
$id = $_GET['id'] ?? 0;

// Determine which table to delete from
if ($type == 'service') {
    $stmt = $pdo->prepare("DELETE FROM services WHERE id = ?");
} elseif ($type == 'portfolio') {
    $stmt = $pdo->prepare("DELETE FROM portfolio WHERE id = ?");
} elseif ($type == 'review') {
    $stmt = $pdo->prepare("DELETE FROM testimonials WHERE id = ?");
}

if (isset($stmt)) {
    $stmt->execute([$id]);
}

// Redirect back to the correct tab in the dashboard
header("Location: dashboard.php?section=" . $type . "s");
exit;