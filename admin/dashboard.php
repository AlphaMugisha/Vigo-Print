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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --bg-body: #f8fafc;
            --bg-sidebar: #0f172a;
            --sidebar-hover: #1e293b;
            --text-sidebar: #94a3b8;
            --text-main: #334155;
            --text-dark: #0f172a;
            --primary: #25D366;
            --primary-hover: #1ebc5a;
            --border-color: #e2e8f0;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.025);
            --card-hover-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -4px rgba(0, 0, 0, 0.04);
        }

        body { font-family: 'Plus Jakarta Sans', sans-serif; margin: 0; display: flex; background: var(--bg-body); color: var(--text-main); }
        
        /* Sidebar Navigation */
        .sidebar { width: 260px; background: var(--bg-sidebar); color: white; height: 100vh; padding: 24px 20px; box-sizing: border-box; position: fixed; overflow-y: auto; border-right: 1px solid #1e293b;}
        .sidebar h2 { color: white; text-align: center; margin-bottom: 40px; font-size: 22px; font-weight: 800; letter-spacing: -0.5px; }
        .sidebar h2 span { color: var(--primary); }
        .sidebar a { display: flex; align-items: center; color: var(--text-sidebar); text-decoration: none; padding: 12px 16px; border-radius: 10px; margin-bottom: 8px; transition: all 0.2s ease; font-weight: 600; font-size: 14px; }
        .sidebar a:hover, .sidebar a.active { background: var(--sidebar-hover); color: var(--primary); }
        .sidebar a i { margin-right: 12px; width: 20px; text-align: center; font-size: 16px; }
        
        .inbox-badge { background: #ef4444; color: white; border-radius: 20px; padding: 2px 8px; font-size: 11px; margin-left: auto; font-weight: 700; box-shadow: 0 2px 4px rgba(239,68,68,0.3); }

        /* Main Dashboard Area */
        .main-content { margin-left: 260px; padding: 40px; width: calc(100% - 260px); box-sizing: border-box; animation: fadeIn 0.4s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        .header { display: flex; justify-content: space-between; align-items: center; background: white; padding: 20px 30px; border-radius: 16px; box-shadow: var(--card-shadow); margin-bottom: 40px; border: 1px solid var(--border-color); }
        .header h2 { margin: 0; font-size: 24px; color: var(--text-dark); font-weight: 700; }
        .user-profile { background: #f1f5f9; padding: 8px 16px; border-radius: 20px; font-size: 14px; font-weight: 600; color: var(--text-dark); display: flex; align-items: center; gap: 8px; }

        .success-msg { background: #ecfdf5; color: #065f46; padding: 16px; border-radius: 12px; margin-bottom: 24px; font-weight: 600; border: 1px solid #a7f3d0; display: flex; align-items: center; gap: 10px; }
        .error-msg { background: #fef2f2; color: #991b1b; padding: 16px; border-radius: 12px; margin-bottom: 24px; font-weight: 600; border: 1px solid #fecaca; display: flex; align-items: center; gap: 10px; }

        /* Section Titles & Cards */
        .section-title { margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; }
        .section-title h2 { margin: 0; font-size: 20px; color: var(--text-dark); }
        
        .grid-cards { display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 24px; }
        .card { background: white; padding: 24px; border-radius: 16px; box-shadow: var(--card-shadow); border: 1px solid var(--border-color); display: flex; flex-direction: column; justify-content: space-between; transition: all 0.3s ease; }
        .card:hover { box-shadow: var(--card-hover-shadow); transform: translateY(-2px); }
        
        .card-label { font-size: 11px; color: var(--primary); text-transform: uppercase; font-weight: 800; margin-bottom: 12px; letter-spacing: 1px;}
        .card-content { font-size: 14px; line-height: 1.6; color: var(--text-main); margin-bottom: 24px; flex-grow: 1; }
        .card-footer { display: flex; gap: 10px; border-top: 1px solid var(--border-color); padding-top: 16px; }
        
        /* Form Inputs */
        input[type="text"], input[type="password"] { width: 100%; padding: 14px 16px; border: 1px solid var(--border-color); border-radius: 10px; box-sizing: border-box; font-family: inherit; margin-bottom: 16px; background: #f8fafc; transition: all 0.2s; color: var(--text-dark); font-weight: 500;}
        input[type="text"]:focus, input[type="password"]:focus { background: white; border-color: var(--primary); outline: none; box-shadow: 0 0 0 3px rgba(37,211,102,0.1); }
        label { display: block; margin-bottom: 6px; font-weight: 700; font-size: 12px; color: var(--text-sidebar); text-transform: uppercase; letter-spacing: 0.5px; }

        /* Buttons */
        .btn { padding: 10px 16px; border-radius: 10px; text-decoration: none; font-weight: 700; font-size: 13px; display: flex; align-items: center; gap: 8px; border: none; cursor: pointer; flex: 1; justify-content: center; transition: all 0.2s; }
        .btn-edit { background: #f1f5f9; color: var(--text-dark); }
        .btn-edit:hover { background: #e2e8f0; }
        .btn-delete { background: #fff; color: #ef4444; border: 1px solid #fecaca; }
        .btn-delete:hover { background: #fef2f2; }
        .btn-add { background: var(--text-dark); color: white; padding: 12px 24px; flex: none; }
        .btn-add:hover { background: #000; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .btn-whatsapp { background: var(--primary); color: white; }
        .btn-whatsapp:hover { background: var(--primary-hover); }
        .btn-read { background: #f59e0b; color: white; }
        .btn-read:hover { background: #d97706; }
        
        img.preview-img { width: 100%; height: 160px; object-fit: cover; border-radius: 10px; margin-bottom: 16px; border: 1px solid var(--border-color); }
        .data-box { background: #f8fafc; padding: 16px; border-radius: 10px; border: 1px solid var(--border-color); font-style: italic; color: var(--text-dark); }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>VIGO <span>PRINT</span></h2>
        <div style="margin-bottom: 20px; padding-left: 16px; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 1px;">Communications</div>
        <a href="?section=inbox" class="<?= $section == 'inbox' ? 'active' : '' ?>">
            <i class="fas fa-inbox"></i> Customer Inbox
            <?php if($unread_count > 0): ?><span class="inbox-badge"><?= $unread_count ?></span><?php endif; ?>
        </a>
        
        <div style="margin: 30px 0 20px; padding-left: 16px; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 1px;">Website Content</div>
        <a href="?section=top" class="<?= $section == 'top' ? 'active' : '' ?>"><i class="fas fa-layer-group"></i> Welcome Banner</a>
        <a href="?section=about" class="<?= $section == 'about' ? 'active' : '' ?>"><i class="fas fa-building"></i> About Us</a>
        <a href="?section=services" class="<?= $section == 'services' ? 'active' : '' ?>"><i class="fas fa-th-large"></i> Services</a>
        <a href="?section=portfolio" class="<?= $section == 'portfolio' ? 'active' : '' ?>"><i class="fas fa-camera-retro"></i> Portfolio</a>
        <a href="?section=reviews" class="<?= $section == 'reviews' ? 'active' : '' ?>"><i class="fas fa-star"></i> Client Reviews</a>
        <a href="?section=contact" class="<?= $section == 'contact' ? 'active' : '' ?>"><i class="fas fa-map-marker-alt"></i> Contact Info</a>
        
        <div style="margin: 30px 0 20px; padding-left: 16px; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 1px;">System</div>
        <a href="?section=admin_settings" class="<?= $section == 'admin_settings' ? 'active' : '' ?>"><i class="fas fa-shield-alt"></i> Security Settings</a>
        <a href="logout.php" style="color: #ef4444;"><i class="fas fa-sign-out-alt"></i> Secure Logout</a>
    </div>

    <div class="main-content">
        
        <div class="header">
            <h2>Overview Dashboard</h2>
            <div class="user-profile">
                <i class="fas fa-user-circle" style="color: var(--primary); font-size: 18px;"></i> <?= htmlspecialchars($_SESSION['admin_username']) ?>
            </div>
        </div>

        <?php if ($success_msg): ?>
            <div class="success-msg"><i class="fas fa-check-circle"></i> <?= $success_msg; ?></div>
        <?php endif; ?>
        <?php if ($error_msg): ?>
            <div class="error-msg"><i class="fas fa-exclamation-triangle"></i> <?= $error_msg; ?></div>
        <?php endif; ?>

        <?php if ($section == 'admin_settings'): ?>
            <div class="section-title"><h2>Security Credentials</h2></div>
            <div class="card" style="max-width: 500px;">
                <div class="card-label" style="margin-bottom: 24px; font-size: 13px;"><i class="fas fa-lock" style="margin-right:5px;"></i> Update Username & Password</div>
                
                <form method="POST" action="dashboard.php?section=admin_settings">
                    <input type="hidden" name="update_admin" value="1">
                    
                    <label>Admin Username</label>
                    <input type="text" name="username" value="<?= htmlspecialchars($_SESSION['admin_username']) ?>" required>
                    
                    <div style="background: var(--bg-body); padding: 20px; border-radius: 12px; border: 1px solid var(--border-color); margin-bottom: 20px;">
                        <label>New Password <span style="color:#94a3b8; font-weight:normal; text-transform:none;">(Leave blank to keep current)</span></label>
                        <input type="password" name="new_password" placeholder="Enter new password">
                        
                        <label>Confirm New Password</label>
                        <input type="password" name="confirm_password" placeholder="Type new password again" style="margin-bottom: 0;">
                    </div>

                    <label style="color: #ef4444;">Current Password (Required) *</label>
                    <input type="password" name="current_password" placeholder="Verify your identity..." required>
                    
                    <button type="submit" class="btn btn-add" style="width: 100%; font-size: 15px; margin-top: 10px;"><i class="fas fa-save"></i> Save Security Changes</button>
                </form>
            </div>
        <?php endif; ?>

        <?php if ($section == 'inbox'): ?>
            <div class="section-title"><h2>Customer Quote Requests</h2></div>
            <?php if (empty($messages)): ?>
                <div class="card" style="text-align: center; padding: 40px;"><p style="color: var(--text-sidebar); font-weight: 500;">Your inbox is empty. No new quote requests.</p></div>
            <?php else: ?>
                <div class="grid-cards" style="grid-template-columns: 1fr;">
                    <?php foreach ($messages as $m): ?>
                    <?php 
                        $is_read = isset($m['is_read']) && $m['is_read'] == 1; 
                        $border_color = $is_read ? 'var(--border-color)' : 'var(--primary)';
                        $opacity = $is_read ? '0.6' : '1';
                    ?>
                    <div class="card" style="border-left: 5px solid <?= $border_color ?>; opacity: <?= $opacity ?>;">
                        <div class="card-label">
                            <i class="far fa-clock"></i> <?= date('M d, Y - H:i', strtotime($m['created_at'])) ?>
                            <?php if (!$is_read): ?>
                                <span style="background: #ef4444; color: white; padding: 4px 8px; border-radius: 6px; margin-left: 10px; font-size: 10px;">NEW REQUEST</span>
                            <?php endif; ?>
                        </div>
                        <div class="card-content">
                            <div style="display: flex; gap: 30px; margin-bottom: 15px; flex-wrap: wrap;">
                                <div><span style="color:var(--text-sidebar); font-size:12px; text-transform:uppercase; font-weight:700;">Client</span><br><strong><?= htmlspecialchars($m['name']) ?></strong></div>
                                <div><span style="color:var(--text-sidebar); font-size:12px; text-transform:uppercase; font-weight:700;">Email</span><br><strong><?= htmlspecialchars($m['email']) ?></strong></div>
                                <div><span style="color:var(--text-sidebar); font-size:12px; text-transform:uppercase; font-weight:700;">Phone</span><br><strong><?= htmlspecialchars($m['phone']) ?></strong></div>
                            </div>
                            <span style="color:var(--text-sidebar); font-size:12px; text-transform:uppercase; font-weight:700; display:block; margin-bottom: 5px;">Project Details</span>
                            <div class="data-box">
                                <?= nl2br(htmlspecialchars($m['project_details'])) ?>
                            </div>
                        </div>
                        <div class="card-footer">
                            <?php if (!$is_read): ?>
                                <a href="mark_read.php?id=<?= $m['id'] ?>" class="btn btn-read">
                                    <i class="fas fa-check-double"></i> Mark Read
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
            <div class="section-title"><h2>Welcome Banner</h2></div>
            <div class="grid-cards">
                <div class="card">
                    <div class="card-label">Badge Text</div>
                    <div class="card-content"><?= htmlspecialchars($settings['hero_badge']) ?></div>
                    <div class="card-footer"><a href="edit_item.php?type=settings&field=hero_badge" class="btn btn-edit"><i class="fas fa-pen"></i> Edit</a></div>
                </div>
                <div class="card">
                    <div class="card-label">Background Video</div>
                    <div class="card-content" style="word-break: break-all; color: var(--primary);">
                        <i class="fas fa-film"></i> <?= htmlspecialchars($settings['hero_video']) ?>
                    </div>
                    <div class="card-footer"><a href="edit_item.php?type=settings&field=hero_video" class="btn btn-edit"><i class="fas fa-video"></i> Change Media</a></div>
                </div>
                <div class="card">
                    <div class="card-label">Main Title</div>
                    <div class="card-content"><strong style="font-size: 18px; color: var(--text-dark);"><?= htmlspecialchars($settings['hero_title']) ?></strong></div>
                    <div class="card-footer"><a href="edit_item.php?type=settings&field=hero_title" class="btn btn-edit"><i class="fas fa-pen"></i> Edit</a></div>
                </div>
                <div class="card">
                    <div class="card-label">Description</div>
                    <div class="card-content"><?= htmlspecialchars($settings['hero_desc']) ?></div>
                    <div class="card-footer"><a href="edit_item.php?type=settings&field=hero_desc" class="btn btn-edit"><i class="fas fa-pen"></i> Edit</a></div>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($section == 'about'): ?>
            <div class="section-title"><h2>About Us Content</h2></div>
            <div class="grid-cards">
                <div class="card">
                    <div class="card-label">Section Title</div>
                    <div class="card-content"><strong style="font-size: 18px; color: var(--text-dark);"><?= htmlspecialchars($settings['about_title']) ?></strong></div>
                    <div class="card-footer"><a href="edit_item.php?type=settings&field=about_title" class="btn btn-edit"><i class="fas fa-pen"></i> Edit</a></div>
                </div>
                <div class="card">
                    <div class="card-label">Text Body 1</div>
                    <div class="card-content"><?= htmlspecialchars($settings['about_desc_1']) ?></div>
                    <div class="card-footer"><a href="edit_item.php?type=settings&field=about_desc_1" class="btn btn-edit"><i class="fas fa-pen"></i> Edit</a></div>
                </div>
                <div class="card">
                    <div class="card-label">About Us Image</div>
                    <img src="../<?= htmlspecialchars($settings['about_img']) ?>" class="preview-img" onerror="this.src='<?= htmlspecialchars($settings['about_img']) ?>'">
                    <div class="card-footer"><a href="edit_item.php?type=settings&field=about_img" class="btn btn-edit"><i class="fas fa-image"></i> Change Image</a></div>
                </div>
                <div class="card">
                    <div class="card-label">Text Body 2</div>
                    <div class="card-content"><?= htmlspecialchars($settings['about_desc_2']) ?></div>
                    <div class="card-footer"><a href="edit_item.php?type=settings&field=about_desc_2" class="btn btn-edit"><i class="fas fa-pen"></i> Edit</a></div>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($section == 'services'): ?>
            <div class="section-title">
                <h2>Manage Services</h2>
                <a href="add_item.php?type=service" class="btn btn-add"><i class="fas fa-plus"></i> Add Service</a>
            </div>
            <div class="grid-cards">
                <?php foreach ($services as $s): ?>
                <div class="card">
                    <div class="card-label">Service Config</div>
                    <div class="card-content">
                        <i class="<?= $s['icon'] ?> fa-2x" style="color:var(--primary); margin-bottom:12px;"></i>
                        <h4 style="margin:0 0 8px 0; color: var(--text-dark);"><?= htmlspecialchars($s['title']) ?></h4>
                        <p style="margin:0;"><?= htmlspecialchars($s['description']) ?></p>
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
                <a href="add_item.php?type=portfolio" class="btn btn-add"><i class="fas fa-plus"></i> Add Project</a>
            </div>
            <div class="grid-cards">
                <?php foreach ($portfolio as $p): ?>
                <div class="card">
                    <div class="card-label">Portfolio Item</div>
                    <img src="../<?= htmlspecialchars($p['image_url']) ?>" class="preview-img" onerror="this.src='https://placehold.co/600x400?text=No+Image'">
                    <div class="card-content">
                        <h4 style="margin:0 0 5px 0; color: var(--text-dark);"><?= htmlspecialchars($p['title']) ?></h4>
                        <p style="margin:0;"><?= htmlspecialchars($p['subtitle']) ?></p>
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
                <a href="add_item.php?type=review" class="btn btn-add"><i class="fas fa-plus"></i> Add Review</a>
            </div>
            <div class="grid-cards">
                <?php foreach ($testimonials as $r): ?>
                <div class="card">
                    <div class="card-label">Client Feedback</div>
                    <div class="card-content">
                        <div style="color:#f59e0b; margin-bottom: 12px; font-size: 12px;">
                            <?php for($i=0; $i<$r['stars']; $i++) echo '<i class="fas fa-star"></i>'; ?>
                        </div>
                        <p style="font-style: italic; margin-top:0;">"<?= htmlspecialchars($r['text']) ?>"</p>
                        <p style="margin:0; color: var(--text-dark);"><strong><?= htmlspecialchars($r['name']) ?></strong><br><span style="font-size:12px; color:var(--text-sidebar);"><?= htmlspecialchars($r['position']) ?></span></p>
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
            <div class="section-title"><h2>Contact Info Data</h2></div>
            <div class="grid-cards">
                <div class="card">
                    <div class="card-label">WhatsApp Number</div>
                    <div class="card-content" style="font-size: 18px; font-weight: 700; color: var(--text-dark);"><i class="fab fa-whatsapp" style="color:var(--primary);"></i> +<?= htmlspecialchars($settings['whatsapp']) ?></div>
                    <div class="card-footer"><a href="edit_item.php?type=settings&field=whatsapp" class="btn btn-edit"><i class="fas fa-pen"></i> Edit</a></div>
                </div>
                <div class="card">
                    <div class="card-label">Physical Address</div>
                    <div class="card-content"><i class="fas fa-map-marker-alt" style="color:var(--text-sidebar);"></i> <?= $settings['address'] ?></div>
                    <div class="card-footer"><a href="edit_item.php?type=settings&field=address" class="btn btn-edit"><i class="fas fa-pen"></i> Edit</a></div>
                </div>
                <div class="card">
                    <div class="card-label">Working Hours</div>
                    <div class="card-content"><i class="far fa-clock" style="color:var(--text-sidebar);"></i> <?= htmlspecialchars($settings['hours']) ?></div>
                    <div class="card-footer"><a href="edit_item.php?type=settings&field=hours" class="btn btn-edit"><i class="fas fa-pen"></i> Edit</a></div>
                </div>
            </div>
        <?php endif; ?>

    </div>
</body>
</html>