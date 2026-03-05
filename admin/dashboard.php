<?php
// admin/dashboard.php
session_start();
require_once '../includes/db.php';

// Security Lock: Only allowed for logged-in admins
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$success_msg = '';
// Determine which section is active from the URL sidebar links
$section = isset($_GET['section']) ? $_GET['section'] : 'top';

// --- FETCH DATA FOR DISPLAY ---
// Site settings (Banner, About, Contact)
$settings = $pdo->query("SELECT * FROM site_settings WHERE id = 1")->fetch(PDO::FETCH_ASSOC);
// Services list
$services = $pdo->query("SELECT * FROM services ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
// Portfolio projects
$portfolio = $pdo->query("SELECT * FROM portfolio ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
// Client reviews (using the testimonials table)
$testimonials = $pdo->query("SELECT * FROM testimonials ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
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
        
        /* Main Dashboard Area */
        .main-content { margin-left: 260px; padding: 40px; width: calc(100% - 260px); box-sizing: border-box; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .header h2 { margin: 0; font-size: 28px; }
        
        /* Card UI for Paragraphs and Items */
        .section-title { margin-bottom: 30px; border-bottom: 2px solid #ddd; padding-bottom: 10px; display: flex; justify-content: space-between; align-items: center;}
        .grid-cards { display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 20px; }
        .card { background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); display: flex; flex-direction: column; justify-content: space-between; border: 1px solid #eee; }
        .card-label { font-size: 11px; color: #25D366; text-transform: uppercase; font-weight: 800; margin-bottom: 10px; letter-spacing: 1px;}
        .card-content { font-size: 15px; line-height: 1.6; color: #444; margin-bottom: 20px; flex-grow: 1; }
        .card-footer { display: flex; gap: 10px; border-top: 1px solid #f0f0f0; padding-top: 15px; }
        
        /* Buttons */
        .btn { padding: 10px 15px; border-radius: 8px; text-decoration: none; font-weight: 700; font-size: 13px; display: flex; align-items: center; gap: 8px; border: none; cursor: pointer; flex: 1; justify-content: center;}
        .btn-edit { background: #f0f0f0; color: #333; }
        .btn-delete { background: #fff; color: #e74c3c; border: 1px solid #e74c3c; }
        .btn-add { background: #25D366; color: white; padding: 12px 25px; flex: none; }
        
        img.preview-img { width: 100%; height: 150px; object-fit: cover; border-radius: 8px; margin-bottom: 15px; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>VIGO PRINT</h2>
        <a href="?section=top" class="<?= $section == 'top' ? 'active' : '' ?>"><i class="fas fa-home"></i> Welcome Banner</a>
        <a href="?section=about" class="<?= $section == 'about' ? 'active' : '' ?>"><i class="fas fa-info-circle"></i> About Us</a>
        <a href="?section=services" class="<?= $section == 'services' ? 'active' : '' ?>"><i class="fas fa-layer-group"></i> Services</a>
        <a href="?section=portfolio" class="<?= $section == 'portfolio' ? 'active' : '' ?>"><i class="fas fa-images"></i> Portfolio</a>
        <a href="?section=reviews" class="<?= $section == 'reviews' ? 'active' : '' ?>"><i class="fas fa-star"></i> Client Reviews</a>
        <a href="?section=contact" class="<?= $section == 'contact' ? 'active' : '' ?>"><i class="fas fa-phone"></i> Contact Info</a>
        <a href="logout.php" style="color: #e74c3c; margin-top: 50px;"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="main-content">
        
        <?php if ($section == 'top'): ?>
        <div class="section-title"><h2>Welcome Banner Paragraphs</h2></div>
        <div class="grid-cards">
            <div class="card">
                <div class="card-label">Paragraph: Badge Text</div>
                <div class="card-content"><?= htmlspecialchars($settings['hero_badge']) ?></div>
                <div class="card-footer"><a href="edit_item.php?type=settings&field=hero_badge" class="btn btn-edit"><i class="fas fa-pen"></i> Edit Paragraph</a></div>
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
                <img src="<?= htmlspecialchars($p['image_url']) ?>" class="preview-img" onerror="this.src='https://placehold.co/600x400?text=No+Image'">
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