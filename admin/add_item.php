<?php
// admin/add_item.php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['admin_logged_in'])) { header("Location: login.php"); exit; }

$type = $_GET['type'] ?? 'service';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($type == 'service') {
        $pdo->prepare("INSERT INTO services (icon, title, description) VALUES (?, ?, ?)")
            ->execute([$_POST['icon'], $_POST['title'], $_POST['description']]);
    } elseif ($type == 'portfolio') {
        $pdo->prepare("INSERT INTO portfolio (title, subtitle, image_url) VALUES (?, ?, ?)")
            ->execute([$_POST['title'], $_POST['subtitle'], $_POST['image_url']]);
    }
    header("Location: dashboard.php?section=" . $type . "s");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add New Item | VIGO PRINT</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans'; background: #f4f7f6; padding: 50px; display: flex; justify-content: center; }
        .box { background: white; padding: 40px; border-radius: 12px; width: 450px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        input, textarea { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; margin-bottom: 20px; box-sizing: border-box; font-family: inherit; }
        button { background: #25D366; color: white; border: none; padding: 15px; border-radius: 8px; cursor: pointer; font-weight: bold; width: 100%; }
        .back { display: block; text-align: center; margin-top: 15px; color: #888; text-decoration: none; font-size: 14px; }
    </style>
</head>
<body>
    <div class="box">
        <h3>Create New <?= ucfirst($type) ?></h3>
        <form method="POST">
            <?php if ($type == 'service'): ?>
                <label>Icon Class (e.g. fas fa-print)</label>
                <input type="text" name="icon" required>
                <label>Service Name</label>
                <input type="text" name="title" required>
                <label>Description</label>
                <textarea name="description" required></textarea>
            <?php else: ?>
                <label>Project Title</label>
                <input type="text" name="title" required>
                <label>Short Detail (e.g. Matte Laminated)</label>
                <input type="text" name="subtitle" required>
                <label>Image URL</label>
                <input type="text" name="image_url" required>
            <?php endif; ?>
            <button type="submit">Publish to Website</button>
            <a href="dashboard.php" class="back">Go Back</a>
        </form>
    </div>
</body>
</html>