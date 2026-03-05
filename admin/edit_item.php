<?php
// admin/edit_item.php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['admin_logged_in'])) { header("Location: login.php"); exit; }

$type = $_GET['type'] ?? '';
$field = $_GET['field'] ?? ''; 
$id = $_GET['id'] ?? 0;        

$title = "Edit Content"; 
$is_media = false; // Flag to check if we are editing an image or video

// 1. Fetch Current Data
if ($type == 'settings') {
    $current = $pdo->query("SELECT $field FROM site_settings WHERE id = 1")->fetchColumn();
    $title = "Edit: " . ucwords(str_replace('_', ' ', $field));
    if ($field == 'hero_video' || $field == 'about_img') { $is_media = true; }
} elseif ($type == 'service') {
    $stmt = $pdo->prepare("SELECT * FROM services WHERE id = ?");
    $stmt->execute([$id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    $title = "Edit Service: " . $data['title'];
} elseif ($type == 'portfolio') {
    $stmt = $pdo->prepare("SELECT * FROM portfolio WHERE id = ?");
    $stmt->execute([$id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    $title = "Edit Portfolio: " . $data['title'];
    $is_media = true; 
} elseif ($type == 'review') {
    $stmt = $pdo->prepare("SELECT * FROM testimonials WHERE id = ?");
    $stmt->execute([$id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    $title = "Edit Review: " . $data['name'];
}

// 2. Handle the Save/Update Action
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // --- SMART UPLOAD LOGIC ---
    // Automatically create 'uploads' folder if it doesn't exist in the root directory
    $upload_dir = '../uploads/';
    if (!is_dir($upload_dir)) { mkdir($upload_dir, 0777, true); }

    $final_media_url = $_POST['media_url'] ?? ''; 

    // If a file was uploaded, use the file instead of the text URL
    if (isset($_FILES['file_upload']) && $_FILES['file_upload']['error'] === UPLOAD_ERR_OK) {
        $filename = time() . '_' . preg_replace("/[^a-zA-Z0-9.]/", "", basename($_FILES['file_upload']['name']));
        $target_file = $upload_dir . $filename;
        if (move_uploaded_file($_FILES['file_upload']['tmp_name'], $target_file)) {
            $final_media_url = 'uploads/' . $filename; // Save relative path for index.php
        }
    }
    // --------------------------

    if ($type == 'settings') {
        $val = $is_media ? $final_media_url : $_POST['content'];
        $stmt = $pdo->prepare("UPDATE site_settings SET $field = ? WHERE id = 1");
        $stmt->execute([$val]);
        header("Location: dashboard.php");
    } elseif ($type == 'service') {
        $stmt = $pdo->prepare("UPDATE services SET icon = ?, title = ?, description = ? WHERE id = ?");
        $stmt->execute([$_POST['icon'], $_POST['title'], $_POST['description'], $id]);
        header("Location: dashboard.php?section=services");
    } elseif ($type == 'portfolio') {
        $stmt = $pdo->prepare("UPDATE portfolio SET title = ?, subtitle = ?, image_url = ? WHERE id = ?");
        $stmt->execute([$_POST['title'], $_POST['subtitle'], $final_media_url, $id]);
        header("Location: dashboard.php?section=portfolio");
    } elseif ($type == 'review') {
        $stmt = $pdo->prepare("UPDATE testimonials SET stars = ?, text = ?, avatar_initials = ?, name = ?, position = ? WHERE id = ?");
        $stmt->execute([$_POST['stars'], $_POST['text'], $_POST['avatar_initials'], $_POST['name'], $_POST['position'], $id]);
        header("Location: dashboard.php?section=reviews");
    }
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($title) ?> | VIGO PRINT</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans'; background: #f4f7f6; padding: 50px; display: flex; justify-content: center; }
        .box { background: white; padding: 40px; border-radius: 12px; width: 500px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        textarea, input[type="text"], input[type="number"], input[type="file"] { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; margin-bottom: 20px; box-sizing: border-box; font-family: inherit; }
        label { display: block; margin-bottom: 5px; font-weight: bold; font-size: 13px; color: #666; }
        button { background: #25D366; color: white; border: none; padding: 15px; border-radius: 8px; cursor: pointer; font-weight: bold; width: 100%; }
        .back { display: block; text-align: center; margin-top: 15px; color: #888; text-decoration: none; }
        .media-box { background: #f8f9fa; padding: 20px; border-radius: 8px; border: 1px dashed #ccc; margin-bottom: 20px; }
        .media-box hr { border: 0; border-top: 1px solid #e1e1e1; margin: 15px 0; }
    </style>
</head>
<body>
    <div class="box">
        <h3><?= htmlspecialchars($title) ?></h3>
        <form method="POST" enctype="multipart/form-data">
            
            <?php if ($type == 'settings' && !$is_media): ?>
                <label>Text Content</label>
                <textarea name="content" rows="6"><?= htmlspecialchars($current) ?></textarea>

            <?php elseif ($type == 'settings' && $is_media): ?>
                <div class="media-box">
                    <label style="color:#25D366;">Option 1: Upload from Computer</label>
                    <input type="file" name="file_upload" accept="image/*,video/mp4,video/webm">
                    <hr>
                    <label>Option 2: Keep or Paste Online Link</label>
                    <input type="text" name="media_url" value="<?= htmlspecialchars($current) ?>">
                </div>

            <?php elseif ($type == 'portfolio'): ?>
                <label>Project Title</label>
                <input type="text" name="title" value="<?= htmlspecialchars($data['title']) ?>" required>
                <label>Detail/Finish</label>
                <input type="text" name="subtitle" value="<?= htmlspecialchars($data['subtitle']) ?>" required>
                
                <div class="media-box">
                    <label style="color:#25D366;">Option 1: Upload New Image</label>
                    <input type="file" name="file_upload" accept="image/*">
                    <hr>
                    <label>Option 2: Keep or Paste Online Link</label>
                    <input type="text" name="media_url" value="<?= htmlspecialchars($data['image_url']) ?>">
                </div>

            <?php elseif ($type == 'service'): ?>
                <label>Icon Class</label>
                <input type="text" name="icon" value="<?= htmlspecialchars($data['icon']) ?>">
                <label>Title</label>
                <input type="text" name="title" value="<?= htmlspecialchars($data['title']) ?>">
                <label>Description</label>
                <textarea name="description"><?= htmlspecialchars($data['description']) ?></textarea>

            <?php elseif ($type == 'review'): ?>
                <label>Review Text</label>
                <textarea name="text"><?= htmlspecialchars($data['text']) ?></textarea>
                <label>Client Name</label>
                <input type="text" name="name" value="<?= htmlspecialchars($data['name']) ?>">
                <label>Position</label>
                <input type="text" name="position" value="<?= htmlspecialchars($data['position']) ?>">
                <label>Initials</label>
                <input type="text" name="avatar_initials" value="<?= htmlspecialchars($data['avatar_initials']) ?>">
                <label>Stars (1-5)</label>
                <input type="number" name="stars" min="1" max="5" value="<?= htmlspecialchars($data['stars']) ?>">
            <?php endif; ?>

            <button type="submit">Update Website</button>
            <a href="dashboard.php" class="back">Cancel</a>
        </form>
    </div>
</body>
</html>