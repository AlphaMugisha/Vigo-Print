<?php
// admin/dashboard.php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$success_msg = '';
$error_msg = '';
$section = isset($_GET['section']) ? $_GET['section'] : 'inbox';

// --- HANDLE ADMIN CREDENTIAL UPDATES ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_admin'])) {
    $new_username = trim($_POST['username']);
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $admin_user = $_SESSION['admin_username'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$admin_user]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (password_verify($current_password, $user['password'])) {
        if (!empty($new_password)) {
            if ($new_password === $confirm_password) {
                $hashed_pw = password_hash($new_password, PASSWORD_DEFAULT);
                $update = $pdo->prepare("UPDATE users SET username = ?, password = ? WHERE id = ?");
                $update->execute([$new_username, $hashed_pw, $user['id']]);
                $_SESSION['admin_username'] = $new_username; 
                $success_msg = "Username and Password updated successfully!";
            } else {
                $error_msg = "Your new passwords do not match. Try again.";
            }
        } else {
            $update = $pdo->prepare("UPDATE users SET username = ? WHERE id = ?");
            $update->execute([$new_username, $user['id']]);
            $_SESSION['admin_username'] = $new_username; 
            $success_msg = "Username updated successfully!";
        }
    } else {
        $error_msg = "Incorrect Current Password. Changes were not saved.";
    }
}

// --- FETCH DATA FOR DISPLAY ---
$settings = $pdo->query("SELECT * FROM site_settings WHERE id = 1")->fetch(PDO::FETCH_ASSOC);
$services = $pdo->query("SELECT * FROM services ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
$portfolio = $pdo->query("SELECT * FROM portfolio ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
$testimonials = $pdo->query("SELECT * FROM testimonials ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);

// Fetch Inbox and calculate UNREAD count
$messages = $pdo->query("SELECT * FROM contact_inbox ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
$unread_count = 0;
foreach ($messages as $m) {
    if (isset($m['is_read']) && $m['is_read'] == 0) {
        $unread_count++;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Control Panel | VIGO PRINT</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; margin: 0; display: flex; background: #f4f7f6; color: #333; }
        
        /* Sidebar Navigation */
        .sidebar { width: 260px; background: #111; color: white; height: 100vh; padding: 20px; box-sizing: border-box; position: fixed; overflow-y: auto;}
        .sidebar h2 { color: #25D366; text-align: center; margin-bottom: 40px; font-size: 20px; letter-spacing: 1px; }
        .sidebar a { display: block; color: #999; text-decoration: none; padding: 12px 15px; border-radius: 8px; margin-bottom: 8px; transition: 0.3s; font-weight: 600; }
        .sidebar a:hover, .sidebar a.active { background: #222; color: #25D366; }
        .sidebar a i { margin-right: 12px; width: 20px; text-align: center; }
        
        .inbox-badge { background: #e74c3c; color: white; border-radius: 50%; padding: 2px 7px; font-size: 11px; float: right; margin-top: 2px; }

        /* Main Dashboard Area */
        .main-content { margin-left: 260px; padding: 40px; width: calc(100% - 260px); box-sizing: border-box; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .header h2 { margin: 0; font-size: 28px; }
        
        .success-msg { background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: 600; border-left: 4px solid #28a745; display: flex; align-items: center; gap: 10px; }
        .error-msg { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: 600; border-left: 4px solid #dc3545; display: flex; align-items: center; gap: 10px; }

        /* Card UI for Paragraphs and Items */
        .section-title { margin-bottom: 30px; border-bottom: 2px solid #ddd; padding-bottom: 10px; display: flex; justify-content: space-between; align-items: center;}
        .grid-cards { display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 20px; }
        .card { background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); display: flex; flex-direction: column; justify-content: space-between; border: 1px solid #eee; transition: opacity 0.3s; }
        .card-label { font-size: 11px; color: #25D366; text-transform: uppercase; font-weight: 800; margin-bottom: 10px; letter-spacing: 1px;}
        .card-content { font-size: 15px; line-height: 1.6; color: #444; margin-bottom: 20px; flex-grow: 1; }
        .card-footer { display: flex; gap: 10px; border-top: 1px solid #f0f0f0; padding-top: 15px; }
        
        /* Form Inputs */
        input[type="text"], input[type="password"] { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; font-family: inherit; margin-bottom: 15px;}
        label { display: block; margin-bottom: 5px; font-weight: 600; font-size: 13px; color: #555; }

        /* Buttons */
        .btn { padding: 10px 15px; border-radius: 8px; text-decoration: none; font-weight: 700; font-size: 13px; display: flex; align-items: center; gap: 8px; border: none; cursor: pointer; flex: 1; justify-content: center;}
        .btn-edit { background: #f0f0f0; color: #333; }
        .btn-delete { background: #fff; color: #e74c3c; border: 1px solid #e74c3c; }
        .btn-add { background: #25D366; color: white; padding: 12px 25px; flex: none; }
        .btn-whatsapp { background: #25D366; color: white; }
        .btn-read { background: #f39c12; color: white; }
        
        img.preview-img { width: 100%; height: 150px; object-fit: cover; border-radius: 8px; margin-bottom: 15px; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>VIGO PRINT</h2>
        <a href="?section=inbox" class="<?= $section == 'inbox' ? 'active' : '' ?>">
            <i class="fas fa-envelope"></i> Customer Inbox
            <?php if($unread_count > 0): ?><span class="inbox-badge"><?= $unread_count ?></span><?php endif; ?>
        </a>
        <a href="?section=top" class="<?= $section == 'top' ? 'active' : '' ?>"><i class="fas fa-home"></i> Welcome Banner</a>
        <a href="?section=about" class="<?= $section == 'about' ? 'active' : '' ?>"><i class="fas fa-info-circle"></i> About Us</a>
        <a href="?section=services" class="<?= $section == 'services' ? 'active' : '' ?>"><i class="fas fa-layer-group"></i> Services</a>
        <a href="?section=portfolio" class="<?= $section == 'portfolio' ? 'active' : '' ?>"><i class="fas fa-images"></i> Portfolio</a>
        <a href="?section=reviews" class="<?= $section == 'reviews' ? 'active' : '' ?>"><i class="fas fa-star"></i> Client Reviews</a>
        <a href="?section=contact" class="<?= $section == 'contact' ? 'active' : '' ?>"><i class="fas fa-phone"></i> Contact Info</a>
        <hr style="border-color: #333; margin: 20px 0;">
        <a href="?section=admin_settings" class="<?= $section == 'admin_settings' ? 'active' : '' ?>"><i class="fas fa-cog"></i> Admin Settings</a>
        <a href="logout.php" style="color: #e74c3c;"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="main-content">
        
        <?php if ($success_msg): ?>
            <div class="success-msg"><i class="fas fa-check-circle"></i> <?= $success_msg; ?></div>
        <?php endif; ?>
        <?php if ($error_msg): ?>
            <div class="error-msg"><i class="fas fa-exclamation-triangle"></i> <?= $error_msg; ?></div>
        <?php endif; ?>

        <?php if ($section == 'admin_settings'): ?>
            <div class="section-title"><h2>Admin Credentials</h2></div>
            <div class="card" style="max-width: 500px;">
                <div class="card-label" style="margin-bottom: 20px;">Update Username & Password</div>
                
                <form method="POST" action="dashboard.php?section=admin_settings">
                    <input type="hidden" name="update_admin" value="1">
                    
                    <label>Admin Username</label>
                    <input type="text" name="username" value="<?= htmlspecialchars($_SESSION['admin_username']) ?>" required>
                    
                    <div style="background: #f9f9f9; padding: 15px; border-radius: 8px; border: 1px solid #eee; margin-bottom: 15px;">
                        <label>New Password <span style="color:#888; font-weight:normal;">(Leave blank to keep current)</span></label>
                        <input type="password" name="new_password" placeholder="Enter new password">
                        
                        <label>Confirm New Password</label>
                        <input type="password" name="confirm_password" placeholder="Type new password again">
                    </div>

                    <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">

                    <label style="color: #e74c3c;">Current Password (Required to save changes) *</label>
                    <input type="password" name="current_password" placeholder="Prove it's really you..." required>
                    
                    <button type="submit" class="btn btn-add" style="width: 100%; font-size: 15px;"><i class="fas fa-save"></i> Save Security Changes</button>
                </form>
            </div>
        <?php endif; ?>

        <?php if ($section == 'inbox'): ?>
            <div class="section-title"><h2>Customer Quote Requests</h2></div>
            <?php if (empty($messages)): ?>
                <div class="card"><p>No quote requests received yet.</p></div>
            <?php else: ?>
                <div class="grid-cards" style="grid-template-columns: 1fr;">
                    <?php foreach ($messages as $m): ?>
                    <?php 
                        $is_read = isset($m['is_read']) && $m['is_read'] == 1; 
                        $border_color = $is_read ? '#ccc' : '#25D366';
                        $opacity = $is_read ? '0.7' : '1';
                    ?>
                    <div class="card" style="border-left: 5px solid <?= $border_color ?>; opacity: <?= $opacity ?>;">
                        <div class="card-label">
                            Received: <?= date('M d, Y - H:i', strtotime($m['created_at'])) ?>
                            <?php if (!$is_read): ?>
                                <span style="background: #e74c3c; color: white; padding: 3px 6px; border-radius: 4px; margin-left: 10px; font-size: 10px;">NEW</span>
                            <?php endif; ?>
                        </div>
                        <div class="card-content">
                            <p><strong>Name:</strong> <?= htmlspecialchars($m['name']) ?></p>
                            <p><strong>Email:</strong> <?= htmlspecialchars($m['email']) ?></p>
                            <p><strong>Phone:</strong> <?= htmlspecialchars($m['phone']) ?></p>
                            <p><strong>Project Details:</strong></p>
                            <div style="background: #f9f9f9; padding: 15px; border-radius: 8px; border: 1px solid #eee; font-style: italic;">
                                <?= nl2br(htmlspecialchars($m['project_details'])) ?>
                            </div>
                        </div>
                        <div class="card-footer">
                            <?php if (!$is_read): ?>
                                <a href="mark_read.php?id=<?= $m['id'] ?>" class="btn btn-read">
                                    <i class="fas fa-check"></i> Mark Read
                                </a>
                            <?php endif; ?>
                            <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $m['phone']) ?>" target="_blank" class="btn btn-whatsapp">
                                <i class="fab fa-whatsapp"></i> WhatsApp
                            </a>
                            <a href="mailto:<?= htmlspecialchars($m['email']) ?>" class="btn btn-edit">
                                <i class="fas fa-envelope"></i> Email
                            </a>
                            <a href="delete_item.php?type=inbox&id=<?= $m['id'] ?>" class="btn btn-delete" onclick="return confirm('Delete this request permanently?')">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if ($section == 'top'): ?>
            <div class="section-title"><h2>Welcome Banner Paragraphs</h2></div>
            <div class="grid-cards">
                <div class="card">
                    <div class="card-label">Paragraph: Badge Text</div>
                    <div class="card-content"><?= htmlspecialchars($settings['hero_badge']) ?></div>
                    <div class="card-footer"><a href="edit_item.php?type=settings&field=hero_badge" class="btn btn-edit"><i class="fas fa-pen"></i> Edit Paragraph</a></div>
                </div>
                <div class="card">
                    <div class="card-label">Media: Background Video</div>
                    <div class="card-content" style="word-break: break-all; color: #3498db;">
                        <?= htmlspecialchars($settings['hero_video']) ?>
                    </div>
                    <div class="card-footer"><a href="edit_item.php?type=settings&field=hero_video" class="btn btn-edit"><i class="fas fa-video"></i> Change Video</a></div>
                </div>
                <div class="card">
                    <div class="card-label">Paragraph: Main Title</div>
                    <div class="card-content"><strong><?= htmlspecialchars($settings['hero_title']) ?></strong></div>
                    <div class="card-footer"><a href="edit_item.php?type=settings&field=hero_title" class="btn btn-edit"><i class="fas fa-pen"></i> Edit Paragraph</a></div>
                </div>
                <div class="card">
                    <div class="card-label">Paragraph: Description</div>
                    <div class="card-content"><?= htmlspecialchars($settings['hero_desc']) ?></div>
                    <div class="card-footer"><a href="edit_item.php?type=settings&field=hero_desc" class="btn btn-edit"><i class="fas fa-pen"></i> Edit Paragraph</a></div>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($section == 'about'): ?>
            <div class="section-title"><h2>About Us Paragraphs</h2></div>
            <div class="grid-cards">
                <div class="card">
                    <div class="card-label">Paragraph: Section Title</div>
                    <div class="card-content"><strong><?= htmlspecialchars($settings['about_title']) ?></strong></div>
                    <div class="card-footer"><a href="edit_item.php?type=settings&field=about_title" class="btn btn-edit"><i class="fas fa-pen"></i> Edit Paragraph</a></div>
                </div>
                <div class="card">
                    <div class="card-label">Paragraph: Text Body 1</div>
                    <div class="card-content"><?= htmlspecialchars($settings['about_desc_1']) ?></div>
                    <div class="card-footer"><a href="edit_item.php?type=settings&field=about_desc_1" class="btn btn-edit"><i class="fas fa-pen"></i> Edit Paragraph</a></div>
                </div>
                <div class="card">
                    <div class="card-label">Media: About Us Image</div>
                    <img src="../<?= htmlspecialchars($settings['about_img']) ?>" class="preview-img" onerror="this.src='<?= htmlspecialchars($settings['about_img']) ?>'">
                    <div class="card-footer"><a href="edit_item.php?type=settings&field=about_img" class="btn btn-edit"><i class="fas fa-image"></i> Change Image</a></div>
                </div>
                <div class="card">
                    <div class="card-label">Paragraph: Text Body 2</div>
                    <div class="card-content"><?= htmlspecialchars($settings['about_desc_2']) ?></div>
                    <div class="card-footer"><a href="edit_item.php?type=settings&field=about_desc_2" class="btn btn-edit"><i class="fas fa-pen"></i> Edit Paragraph</a></div>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($section == 'services'): ?>
            <div class="section-title">
                <h2>Manage Services</h2>
                <a href="add_item.php?type=service" class="btn btn-add"><i class="fas fa-plus"></i> Create New Service</a>
            </div>
            <div class="grid-cards">
                <?php foreach ($services as $s): ?>
                <div class="card">
                    <div class="card-label">Service Card</div>
                    <div class="card-content">
                        <i class="<?= $s['icon'] ?> fa-2x" style="color:#25D366; margin-bottom:10px;"></i>
                        <h4><?= htmlspecialchars($s['title']) ?></h4>
                        <p><?= htmlspecialchars($s['description']) ?></p>
                    </div>
                    <div class="card-footer">
                        <a href="edit_item.php?type=service&id=<?= $s['id'] ?>" class="btn btn-edit"><i class="fas fa-pen"></i> Edit</a>
                        <a href="delete_item.php?type=service&id=<?= $s['id'] ?>" class="btn btn-delete" onclick="return confirm('Delete this service?')"><i class="fas fa-trash"></i> Delete</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($section == 'portfolio'): ?>
            <div class="section-title">
                <h2>Our Work Portfolio</h2>
                <a href="add_item.php?type=portfolio" class="btn btn-add"><i class="fas fa-plus"></i> Create New Project</a>
            </div>
            <div class="grid-cards">
                <?php foreach ($portfolio as $p): ?>
                <div class="card">
                    <div class="card-label">Portfolio Card</div>
                    <img src="../<?= htmlspecialchars($p['image_url']) ?>" class="preview-img" onerror="this.src='https://placehold.co/600x400?text=No+Image'">
                    <div class="card-content">
                        <h4><?= htmlspecialchars($p['title']) ?></h4>
                        <p><?= htmlspecialchars($p['subtitle']) ?></p>
                    </div>
                    <div class="card-footer">
                        <a href="edit_item.php?type=portfolio&id=<?= $p['id'] ?>" class="btn btn-edit"><i class="fas fa-pen"></i> Edit</a>
                        <a href="delete_item.php?type=portfolio&id=<?= $p['id'] ?>" class="btn btn-delete" onclick="return confirm('Delete this project?')"><i class="fas fa-trash"></i> Delete</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($section == 'reviews'): ?>
            <div class="section-title">
                <h2>Client Testimonials</h2>
                <a href="add_item.php?type=review" class="btn btn-add"><i class="fas fa-plus"></i> Create New Review</a>
            </div>
            <div class="grid-cards">
                <?php foreach ($testimonials as $r): ?>
                <div class="card">
                    <div class="card-label">Review Paragraph</div>
                    <div class="card-content">
                        <div style="color:#f39c12; margin-bottom: 10px;">
                            <?php for($i=0; $i<$r['stars']; $i++) echo '<i class="fas fa-star"></i>'; ?>
                        </div>
                        <p><i>"<?= htmlspecialchars($r['text']) ?>"</i></p>
                        <p><strong><?= htmlspecialchars($r['name']) ?></strong> - <?= htmlspecialchars($r['position']) ?></p>
                    </div>
                    <div class="card-footer">
                        <a href="edit_item.php?type=review&id=<?= $r['id'] ?>" class="btn btn-edit"><i class="fas fa-pen"></i> Edit</a>
                        <a href="delete_item.php?type=review&id=<?= $r['id'] ?>" class="btn btn-delete" onclick="return confirm('Delete this review?')"><i class="fas fa-trash"></i> Delete</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($section == 'contact'): ?>
            <div class="section-title"><h2>Contact Info Paragraphs</h2></div>
            <div class="grid-cards">
                <div class="card">
                    <div class="card-label">Paragraph: WhatsApp</div>
                    <div class="card-content"><?= htmlspecialchars($settings['whatsapp']) ?></div>
                    <div class="card-footer"><a href="edit_item.php?type=settings&field=whatsapp" class="btn btn-edit"><i class="fas fa-pen"></i> Edit Paragraph</a></div>
                </div>
                <div class="card">
                    <div class="card-label">Paragraph: Address</div>
                    <div class="card-content"><?= $settings['address'] ?></div>
                    <div class="card-footer"><a href="edit_item.php?type=settings&field=address" class="btn btn-edit"><i class="fas fa-pen"></i> Edit Paragraph</a></div>
                </div>
                <div class="card">
                    <div class="card-label">Paragraph: Working Hours</div>
                    <div class="card-content"><?= htmlspecialchars($settings['hours']) ?></div>
                    <div class="card-footer"><a href="edit_item.php?type=settings&field=hours" class="btn btn-edit"><i class="fas fa-pen"></i> Edit Paragraph</a></div>
                </div>
            </div>
        <?php endif; ?>

    </div>
</body>
</html>