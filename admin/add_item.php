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

// Curated list of icons useful for a printing business
$icon_list = [
    'fas fa-print', 'fas fa-copy', 'fas fa-box-open', 'fas fa-layer-group',
    'fas fa-palette', 'fas fa-file-alt', 'fas fa-book', 'fas fa-id-card',
    'fas fa-tshirt', 'fas fa-camera', 'fas fa-bullhorn', 'fas fa-magic',
    'fas fa-cogs', 'fas fa-medal', 'fas fa-truck', 'fas fa-leaf'
];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add <?= ucfirst($type) ?> | VIGO PRINT</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans'; background: #f4f7f6; padding: 50px; display: flex; justify-content: center; color: #333;}
        .box { background: white; padding: 40px; border-radius: 12px; width: 450px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        input[type="text"], input[type="number"], textarea, input[type="file"] { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; margin-bottom: 20px; box-sizing: border-box; font-family: inherit; }
        label { display: block; margin-bottom: 8px; font-weight: 600; font-size: 13px; color: #555; text-transform: uppercase; letter-spacing: 0.5px;}
        button { background: #25D366; color: white; border: none; padding: 15px; border-radius: 8px; cursor: pointer; font-weight: bold; width: 100%; transition: 0.3s; font-size: 15px;}
        button:hover { background: #1ebc5a; }
        .back { display: block; text-align: center; margin-top: 15px; color: #888; text-decoration: none; font-size: 14px; font-weight: 600;}
        .media-box { background: #f8f9fa; padding: 20px; border-radius: 8px; border: 1px dashed #ccc; margin-bottom: 20px; }
        
        /* Icon Picker Styles */
        .icon-grid { display: grid; grid-template-columns: repeat(8, 1fr); gap: 10px; margin-bottom: 20px; }
        .icon-option { background: #f8f9fa; border: 2px solid #eee; border-radius: 8px; padding: 15px 0; text-align: center; cursor: pointer; transition: 0.2s; font-size: 20px; color: #666; }
        .icon-option:hover { background: #e9ecef; }
        .icon-option.active { border-color: #25D366; color: #25D366; background: #e8f5e9; box-shadow: 0 4px 10px rgba(37,211,102,0.15); transform: translateY(-2px);}
    </style>
</head>
<body>
    <div class="box">
        <h3 style="margin-top: 0; font-size: 22px;">Create New <?= ucfirst($type) ?></h3>
        <form method="POST" enctype="multipart/form-data">
            
            <?php if ($type == 'service'): ?>
                <label>1. Select an Icon</label>
                <input type="hidden" name="icon" id="selected-icon" value="fas fa-print">
                
                <div class="icon-grid">
                    <?php foreach($icon_list as $index => $i): ?>
                        <div class="icon-option <?= $index === 0 ? 'active' : '' ?>" data-icon="<?= $i ?>" onclick="selectIcon(this)">
                            <i class="<?= $i ?>"></i>
                        </div>
                    <?php endforeach; ?>
                </div>

                <label>2. Service Name</label>
                <input type="text" name="title" placeholder="e.g. Offset Printing" required>
                
                <label>3. Description</label>
                <textarea name="description" rows="3" placeholder="Describe the service..." required></textarea>
                
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
                <textarea name="text" rows="3" required></textarea>
                <label>Client Name</label>
                <input type="text" name="name" required>
                <label>Position</label>
                <input type="text" name="position" required>
                <label>Initials (e.g. JD)</label>
                <input type="text" name="avatar_initials" required maxlength="3">
                <label>Stars (1-5)</label>
                <input type="number" name="stars" required min="1" max="5" value="5">
            <?php endif; ?>
            
            <button type="submit"><i class="fas fa-save"></i> Publish to Website</button>
            <a href="dashboard.php" class="back"><i class="fas fa-arrow-left"></i> Cancel</a>
        </form>
    </div>

    <script>
        // JavaScript to make the icon picker work
        function selectIcon(element) {
            // Remove 'active' class from all icons
            document.querySelectorAll('.icon-option').forEach(el => el.classList.remove('active'));
            // Add 'active' class to the clicked icon
            element.classList.add('active');
            // Update the hidden input with the correct class name
            document.getElementById('selected-icon').value = element.getAttribute('data-icon');
        }
    </script>
</body>
</html>