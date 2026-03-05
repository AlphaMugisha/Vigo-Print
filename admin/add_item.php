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
        // Handle file upload
        $final_media_url = $_POST['media_url'] ?? ''; 
        if (isset($_FILES['file_upload']) && $_FILES['file_upload']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../uploads/';
            if (!is_dir($upload_dir)) { mkdir($upload_dir, 0777, true); }
            
            $filename = time() . '_' . preg_replace("/[^a-zA-Z0-9.]/", "", basename($_FILES['file_upload']['name']));
            if (move_uploaded_file($_FILES['file_upload']['tmp_name'], $upload_dir . $filename)) {
                $final_media_url = 'uploads/' . $filename;
            }
        }

        $pdo->prepare("INSERT INTO portfolio (title, subtitle, image_url) VALUES (?, ?, ?)")
            ->execute([$_POST['title'], $_POST['subtitle'], $final_media_url]);
    } elseif ($type == 'review') {
        $pdo->prepare("INSERT INTO testimonials (stars, text, avatar_initials, name, position) VALUES (?, ?, ?, ?, ?)")
            ->execute([$_POST['stars'], $_POST['text'], $_POST['avatar_initials'], $_POST['name'], $_POST['position']]);
    }
    
    header("Location: dashboard.php?section=" . $type . "s");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add <?= ucfirst($type) ?> | VIGO PRINT</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans'; background: #f4f7f6; padding: 50px; display: flex; justify-content: center; }
        .box { background: white; padding: 40px; border-radius: 12px; width: 450px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        input, textarea { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; margin-bottom: 20px; box-sizing: border-box; font-family: inherit; }
        label { display: block; margin-bottom: 5px; font-weight: bold; font-size: 13px; color: #666; }
        button { background: #25D366; color: white; border: none; padding: 15px; border-radius: 8px; cursor: pointer; font-weight: bold; width: 100%; }
        .back { display: block; text-align: center; margin-top: 15px; color: #888; text-decoration: none; font-size: 14px; }
        .media-box { background: #f8f9fa; padding: 20px; border-radius: 8px; border: 1px dashed #ccc; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="box">
        <h3>Create New <?= ucfirst($type) ?></h3>
        <form method="POST" enctype="multipart/form-data">
            
            <?php if ($type == 'service'): ?>
                <label>Icon Class (e.g. fas fa-print)</label>
                <input type="text" name="icon" required>
                <label>Service Name</label>
                <input type="text" name="title" required>
                <label>Description</label>
                <textarea name="description" required></textarea>
                
            <?php elseif ($type == 'portfolio'): ?>
                <label>Project Title</label>
                <input type="text" name="title" required>
                <label>Short Detail (e.g. Matte Laminated)</label>
                <input type="text" name="subtitle" required>
                
                <div class="media-box">
                    <label style="color:#25D366;">Option 1: Upload Image</label>
                    <input type="file" name="file_upload" accept="image/*">
                    <label style="margin-top: 15px;">Option 2: Paste Online Link</label>
                    <input type="text" name="media_url" placeholder="https://...">
                </div>

            <?php elseif ($type == 'review'): ?>
                <label>Review Text</label>
                <textarea name="text" required></textarea>
                <label>Client Name</label>
                <input type="text" name="name" required>
                <label>Position</label>
                <input type="text" name="position" required>
                <label>Initials (e.g. JD)</label>
                <input type="text" name="avatar_initials" required maxlength="3">
                <label>Stars (1-5)</label>
                <input type="number" name="stars" required min="1" max="5" value="5">
            <?php endif; ?>
            
            <button type="submit">Publish to Website</button>
            <a href="dashboard.php" class="back">Go Back</a>
        </form>
    </div>
</body>
</html>