<?php
// admin/dashboard.php
session_start();
require_once '../includes/db.php';

// Security Lock
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$success_msg = '';

// Determine which section is active from the URL (default to top)
$section = isset($_GET['section']) ? $_GET['section'] : 'top';

// --- 1. HANDLE SETTINGS UPDATES (Top, About, Bottom) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_settings'])) {
    $update_query = "UPDATE site_settings SET ";
    $params = [];
    
    foreach ($_POST as $key => $value) {
        if ($key != 'update_settings' && $key != 'section') {
            $update_query .= "$key = :$key, ";
            $params[$key] = $value;
        }
    }
    
    $update_query = rtrim($update_query, ", ") . " WHERE id = 1";
    $stmt = $pdo->prepare($update_query);
    $stmt->execute($params);
    
    $success_msg = "Website text updated successfully!";
    $section = $_POST['section'];
}

// --- 2. HANDLE ADDING NEW ITEMS (Services, Portfolio, Reviews) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_service'])) {
    $stmt = $pdo->prepare("INSERT INTO services (icon, title, description) VALUES (:icon, :title, :description)");
    $stmt->execute(['icon' => $_POST['icon'], 'title' => $_POST['title'], 'description' => $_POST['description']]);
    $success_msg = "New service created successfully!";
    $section = 'services';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_portfolio'])) {
    $stmt = $pdo->prepare("INSERT INTO portfolio (image_url, title, subtitle) VALUES (:image_url, :title, :subtitle)");
    $stmt->execute(['image_url' => $_POST['image_url'], 'title' => $_POST['title'], 'subtitle' => $_POST['subtitle']]);
    $success_msg = "New portfolio project created successfully!";
    $section = 'portfolio';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_review'])) {
    $stmt = $pdo->prepare("INSERT INTO testimonials (stars, text, avatar_initials, name, position) VALUES (:stars, :text, :avatar_initials, :name, :position)");
    $stmt->execute([
        'stars' => $_POST['stars'], 'text' => $_POST['text'], 
        'avatar_initials' => $_POST['avatar_initials'], 'name' => $_POST['name'], 'position' => $_POST['position']
    ]);
    $success_msg = "New client review created successfully!";
    $section = 'reviews';
}

// --- FETCH ALL DATA FOR DISPLAY ---
$settings = $pdo->query("SELECT * FROM site_settings WHERE id = 1")->fetch(PDO::FETCH_ASSOC);
$services = $pdo->query("SELECT * FROM services ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
$portfolio = $pdo->query("SELECT * FROM portfolio ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
$testimonials = $pdo->query("SELECT * FROM testimonials ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
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
        
        .sidebar { width: 260px; background: #111; color: white; height: 100vh; padding: 20px; box-sizing: border-box; position: fixed; overflow-y: auto;}
        .sidebar h2 { color: #25D366; text-align: center; margin-bottom: 40px; font-size: 20px; letter-spacing: 1px; }
        .sidebar a { display: block; color: #999; text-decoration: none; padding: 12px 15px; border-radius: 8px; margin-bottom: 8px; transition: 0.3s; font-weight: 600; }
        .sidebar a:hover, .sidebar a.active { background: #222; color: #25D366; }
        .sidebar a i { margin-right: 12px; width: 20px; text-align: center; }
        
        .main-content { margin-left: 260px; padding: 40px; width: calc(100% - 260px); box-sizing: border-box; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .header h2 { margin: 0; font-size: 28px; }
        .header-profile { background: white; padding: 10px 20px; border-radius: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); font-weight: 600; }
        
        .card { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); margin-bottom: 30px; }
        .card-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 20px; }
        .card-header h3 { margin: 0; color: #111; font-size: 22px; }
        
        .preview-label { font-size: 13px; color: #888; text-transform: uppercase; letter-spacing: 1px; font-weight: 700; margin-bottom: 5px; }
        .preview-value { font-size: 16px; margin-bottom: 25px; padding: 15px; background: #f9f9f9; border-radius: 8px; border-left: 4px solid #25D366; }
        
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; font-size: 14px; color: #555; }
        input[type="text"], input[type="number"], textarea { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; font-family: inherit; transition: 0.3s; }
        
        .btn { padding: 12px 24px; border: none; border-radius: 8px; cursor: pointer; font-weight: 700; font-size: 14px; transition: 0.3s; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; }
        .btn-edit { background: #f0f0f0; color: #333; }
        .btn-edit:hover { background: #e0e0e0; }
        .btn-create { background: #000; color: white; }
        .btn-create:hover { background: #333; }
        .btn-save { background: #25D366; color: white; }
        .btn-cancel { background: #fff; color: #e74c3c; border: 1px solid #e74c3c; }
        
        .success-msg { background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: 600; border-left: 4px solid #28a745; display: flex; align-items: center; gap: 10px; }
        
        /* Grid Cards for Items */
        .grid-cards { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; }
        .item-card { background: #f9f9f9; padding: 20px; border-radius: 10px; border: 1px solid #eee; position: relative; display: flex; flex-direction: column; justify-content: space-between; }
        .item-card img { width: 100%; height: 150px; object-fit: cover; border-radius: 6px; margin-bottom: 15px; }
        .item-card h4 { margin: 0 0 10px 0; font-size: 18px; }
        .item-actions { display: flex; gap: 10px; margin-top: 15px; border-top: 1px solid #eee; padding-top: 15px; }
        .btn-small { padding: 8px 12px; font-size: 12px; border-radius: 6px; flex: 1; text-align: center; justify-content: center; }
        
        .edit-form { display: none; background: #fcfcfc; padding: 25px; border-radius: 8px; border: 1px dashed #ccc; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>VIGO PRINT</h2>
        <a href="?section=top" class="<?= $section == 'top' ? 'active' : '' ?>"><i class="fas fa-home"></i> Top Welcome Banner</a>
        <a href="?section=about" class="<?= $section == 'about' ? 'active' : '' ?>"><i class="fas fa-info-circle"></i> About Us Section</a>
        <a href="?section=services" class="<?= $section == 'services' ? 'active' : '' ?>"><i class="fas fa-layer-group"></i> Our Services</a>
        <a href="?section=portfolio" class="<?= $section == 'portfolio' ? 'active' : '' ?>"><i class="fas fa-images"></i> Our Work (Portfolio)</a>
        <a href="?section=reviews" class="<?= $section == 'reviews' ? 'active' : '' ?>"><i class="fas fa-star"></i> Client Reviews</a>
        <a href="?section=bottom" class="<?= $section == 'bottom' ? 'active' : '' ?>"><i class="fas fa-address-card"></i> Contact & Footer</a>
        <a href="logout.php" style="color: #e74c3c; margin-top: 50px;"><i class="fas fa-sign-out-alt"></i> Log Out</a>
    </div>

    <div class="main-content">
        <div class="header">
            <h2>Website Control Panel</h2>
            <div class="header-profile"><i class="fas fa-user-circle"></i> <?= htmlspecialchars($_SESSION['admin_username']); ?></div>
        </div>

        <?php if ($success_msg): ?>
            <div class="success-msg"><i class="fas fa-check-circle"></i> <?= $success_msg; ?></div>
        <?php endif; ?>

        <?php if ($section == 'top'): ?>
        <div class="card">
            <div class="card-header">
                <h3>Currently on Website: Top Banner</h3>
                <button class="btn btn-edit" onclick="toggleView('top')"><i class="fas fa-pen"></i> Edit Text</button>
            </div>
            
            <div id="top-preview">
                <div class="preview-label">Small Text (Above Main Title)</div>
                <div class="preview-value"><?= htmlspecialchars($settings['hero_badge']); ?></div>
                <div class="preview-label">Main Big Title</div>
                <div class="preview-value" style="font-size: 24px; font-weight: 700;"><?= htmlspecialchars($settings['hero_title']); ?></div>
                <div class="preview-label">Short Welcome Paragraph</div>
                <div class="preview-value"><?= htmlspecialchars($settings['hero_desc']); ?></div>
                <div class="preview-label">Main WhatsApp Number</div>
                <div class="preview-value"><i class="fab fa-whatsapp"></i> +<?= htmlspecialchars($settings['whatsapp']); ?></div>
            </div>

            <form id="top-form" class="edit-form" method="POST" action="">
                <input type="hidden" name="update_settings" value="1">
                <input type="hidden" name="section" value="top">
                <h3 style="margin-top:0;">Edit Top Banner</h3>
                
                <div class="form-group">
                    <label>Small Text (Above Main Title)</label>
                    <input type="text" name="hero_badge" value="<?= htmlspecialchars($settings['hero_badge']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Main Big Title</label>
                    <input type="text" name="hero_title" value="<?= htmlspecialchars($settings['hero_title']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Short Welcome Paragraph</label>
                    <textarea name="hero_desc" rows="3" required><?= htmlspecialchars($settings['hero_desc']); ?></textarea>
                </div>
                <div class="form-group">
                    <label>Main WhatsApp Number (No + sign)</label>
                    <input type="text" name="whatsapp" value="<?= htmlspecialchars($settings['whatsapp']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Background Video File Name (Advanced)</label>
                    <input type="text" name="hero_video" value="<?= htmlspecialchars($settings['hero_video']); ?>" required>
                </div>
                
                <button type="submit" class="btn btn-save"><i class="fas fa-save"></i> Save Changes to Live Site</button>
                <button type="button" class="btn btn-cancel" onclick="toggleView('top')">Cancel</button>
            </form>
        </div>
        <?php endif; ?>

        <?php if ($section == 'about'): ?>
        <div class="card">
            <div class="card-header">
                <h3>Currently on Website: About Us</h3>
                <button class="btn btn-edit" onclick="toggleView('about')"><i class="fas fa-pen"></i> Edit Text</button>
            </div>
            
            <div id="about-preview">
                <div class="preview-label">About Title</div>
                <div class="preview-value" style="font-size: 20px; font-weight: 700;"><?= htmlspecialchars($settings['about_title']); ?></div>
                <div class="preview-label">Paragraph 1</div>
                <div class="preview-value"><?= htmlspecialchars($settings['about_desc_1']); ?></div>
                <div class="preview-label">Paragraph 2</div>
                <div class="preview-value"><?= htmlspecialchars($settings['about_desc_2']); ?></div>
                <div class="preview-label">About Image Link</div>
                <div class="preview-value" style="word-break: break-all;"><?= htmlspecialchars($settings['about_img']); ?></div>
            </div>

            <form id="about-form" class="edit-form" method="POST" action="">
                <input type="hidden" name="update_settings" value="1">
                <input type="hidden" name="section" value="about">
                <h3 style="margin-top:0;">Edit About Section</h3>
                
                <div class="form-group">
                    <label>About Title</label>
                    <input type="text" name="about_title" value="<?= htmlspecialchars($settings['about_title']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Paragraph 1</label>
                    <textarea name="about_desc_1" rows="4" required><?= htmlspecialchars($settings['about_desc_1']); ?></textarea>
                </div>
                <div class="form-group">
                    <label>Paragraph 2</label>
                    <textarea name="about_desc_2" rows="4" required><?= htmlspecialchars($settings['about_desc_2']); ?></textarea>
                </div>
                <div class="form-group">
                    <label>Image Link (URL)</label>
                    <input type="text" name="about_img" value="<?= htmlspecialchars($settings['about_img']); ?>" required>
                </div>
                
                <button type="submit" class="btn btn-save"><i class="fas fa-save"></i> Save Changes</button>
                <button type="button" class="btn btn-cancel" onclick="toggleView('about')">Cancel</button>
            </form>
        </div>
        <?php endif; ?>

        <?php if ($section == 'services'): ?>
        <div class="card">
            <div class="card-header">
                <h3>Our Services</h3>
                <button class="btn btn-create" onclick="toggleView('services')"><i class="fas fa-plus-circle"></i> Create New Service</button>
            </div>
            
            <form id="services-form" class="edit-form" method="POST" action="" style="margin-bottom: 30px;">
                <h3 style="margin-top:0; color:#25D366;">Create a New Service</h3>
                <input type="hidden" name="add_service" value="1">
                <div class="form-group">
                    <label>Icon Name (e.g. fas fa-print)</label>
                    <input type="text" name="icon" required>
                </div>
                <div class="form-group">
                    <label>Service Name</label>
                    <input type="text" name="title" required>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-save"><i class="fas fa-save"></i> Publish Service</button>
                <button type="button" class="btn btn-cancel" onclick="toggleView('services')">Cancel</button>
            </form>

            <div id="services-preview" class="grid-cards">
                <?php foreach ($services as $service): ?>
                <div class="item-card">
                    <div>
                        <i class="<?= htmlspecialchars($service['icon']); ?>" style="font-size: 30px; color: #25D366; margin-bottom: 10px;"></i>
                        <h4><?= htmlspecialchars($service['title']); ?></h4>
                        <p style="font-size: 14px; color: #666;"><?= htmlspecialchars($service['description']); ?></p>
                    </div>
                    <div class="item-actions">
                        <a href="edit_item.php?type=service&id=<?= $service['id']; ?>" class="btn btn-edit btn-small"><i class="fas fa-pen"></i> Edit</a>
                        <a href="delete_item.php?type=service&id=<?= $service['id']; ?>" class="btn btn-cancel btn-small" onclick="return confirm('Are you sure you want to delete this service?');"><i class="fas fa-trash"></i> Delete</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($section == 'portfolio'): ?>
        <div class="card">
            <div class="card-header">
                <h3>Our Work (Portfolio)</h3>
                <button class="btn btn-create" onclick="toggleView('portfolio')"><i class="fas fa-plus-circle"></i> Add New Project</button>
            </div>
            
            <form id="portfolio-form" class="edit-form" method="POST" action="" style="margin-bottom: 30px;">
                <h3 style="margin-top:0; color:#25D366;">Add a New Project</h3>
                <input type="hidden" name="add_portfolio" value="1">
                <div class="form-group">
                    <label>Project Title</label>
                    <input type="text" name="title" required>
                </div>
                <div class="form-group">
                    <label>Short Detail / Paper Finish</label>
                    <input type="text" name="subtitle" required>
                </div>
                <div class="form-group">
                    <label>Image Link (URL)</label>
                    <input type="text" name="image_url" required>
                </div>
                <button type="submit" class="btn btn-save"><i class="fas fa-save"></i> Publish Project</button>
                <button type="button" class="btn btn-cancel" onclick="toggleView('portfolio')">Cancel</button>
            </form>

            <div id="portfolio-preview" class="grid-cards">
                <?php foreach ($portfolio as $item): ?>
                <div class="item-card">
                    <div>
                        <img src="<?= htmlspecialchars($item['image_url']); ?>" alt="Project">
                        <h4><?= htmlspecialchars($item['title']); ?></h4>
                        <p style="font-size: 14px; color: #666;"><?= htmlspecialchars($item['subtitle']); ?></p>
                    </div>
                    <div class="item-actions">
                        <a href="edit_item.php?type=portfolio&id=<?= $item['id']; ?>" class="btn btn-edit btn-small"><i class="fas fa-pen"></i> Edit</a>
                        <a href="delete_item.php?type=portfolio&id=<?= $item['id']; ?>" class="btn btn-cancel btn-small" onclick="return confirm('Delete this project?');"><i class="fas fa-trash"></i> Delete</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($section == 'reviews'): ?>
        <div class="card">
            <div class="card-header">
                <h3>Client Reviews</h3>
                <button class="btn btn-create" onclick="toggleView('reviews')"><i class="fas fa-plus-circle"></i> Add New Review</button>
            </div>
            
            <form id="reviews-form" class="edit-form" method="POST" action="" style="margin-bottom: 30px;">
                <h3 style="margin-top:0; color:#25D366;">Add a New Review</h3>
                <input type="hidden" name="add_review" value="1">
                <div class="form-group">
                    <label>Review Text (What did they say?)</label>
                    <textarea name="text" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label>Client Name</label>
                    <input type="text" name="name" required>
                </div>
                <div class="form-group">
                    <label>Company / Position</label>
                    <input type="text" name="position" required>
                </div>
                <div class="form-group" style="display:flex; gap: 20px;">
                    <div style="flex:1;">
                        <label>Avatar Initials (e.g. JD)</label>
                        <input type="text" name="avatar_initials" maxlength="3" required>
                    </div>
                    <div style="flex:1;">
                        <label>Star Rating (1 to 5)</label>
                        <input type="number" name="stars" min="1" max="5" value="5" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-save"><i class="fas fa-save"></i> Publish Review</button>
                <button type="button" class="btn btn-cancel" onclick="toggleView('reviews')">Cancel</button>
            </form>

            <div id="reviews-preview" class="grid-cards">
                <?php foreach ($testimonials as $review): ?>
                <div class="item-card">
                    <div>
                        <div style="color:#f39c12; margin-bottom: 10px;">
                            <?php for($i=0; $i<$review['stars']; $i++) echo '<i class="fas fa-star"></i>'; ?>
                        </div>
                        <p style="font-size: 14px; color: #666; font-style: italic;">"<?= htmlspecialchars($review['text']); ?>"</p>
                        <h4 style="margin-bottom: 2px; font-size: 16px;"><?= htmlspecialchars($review['name']); ?></h4>
                        <p style="font-size: 12px; color: #888; margin: 0;"><?= htmlspecialchars($review['position']); ?></p>
                    </div>
                    <div class="item-actions">
                        <a href="edit_item.php?type=review&id=<?= $review['id']; ?>" class="btn btn-edit btn-small"><i class="fas fa-pen"></i> Edit</a>
                        <a href="delete_item.php?type=review&id=<?= $review['id']; ?>" class="btn btn-cancel btn-small" onclick="return confirm('Delete this review?');"><i class="fas fa-trash"></i> Delete</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($section == 'bottom'): ?>
        <div class="card">
            <div class="card-header">
                <h3>Currently on Website: Contact & Footer</h3>
                <button class="btn btn-edit" onclick="toggleView('bottom')"><i class="fas fa-pen"></i> Edit Text</button>
            </div>
            
            <div id="bottom-preview">
                <div class="preview-label">Bottom Banner Title</div>
                <div class="preview-value"><?= htmlspecialchars($settings['cta_title']); ?></div>
                <div class="preview-label">Company Description (In Footer)</div>
                <div class="preview-value"><?= htmlspecialchars($settings['footer_about']); ?></div>
                <div class="preview-label">Physical Address</div>
                <div class="preview-value"><?= htmlspecialchars($settings['address']); ?></div>
                <div class="preview-label">Working Hours</div>
                <div class="preview-value"><?= htmlspecialchars($settings['hours']); ?></div>
            </div>

            <form id="bottom-form" class="edit-form" method="POST" action="">
                <input type="hidden" name="update_settings" value="1">
                <input type="hidden" name="section" value="bottom">
                <h3 style="margin-top:0;">Edit Bottom Section</h3>
                
                <div class="form-group">
                    <label>Bottom Banner Title</label>
                    <input type="text" name="cta_title" value="<?= htmlspecialchars($settings['cta_title']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Company Description (In Footer)</label>
                    <textarea name="footer_about" rows="3" required><?= htmlspecialchars($settings['footer_about']); ?></textarea>
                </div>
                <div class="form-group">
                    <label>Physical Address</label>
                    <textarea name="address" rows="2" required><?= htmlspecialchars($settings['address']); ?></textarea>
                </div>
                <div class="form-group">
                    <label>Working Hours</label>
                    <input type="text" name="hours" value="<?= htmlspecialchars($settings['hours']); ?>" required>
                </div>
                
                <button type="submit" class="btn btn-save"><i class="fas fa-save"></i> Save Changes</button>
                <button type="button" class="btn btn-cancel" onclick="toggleView('bottom')">Cancel</button>
            </form>
        </div>
        <?php endif; ?>

    </div>

    <script>
        function toggleView(section) {
            const previewDiv = document.getElementById(section + '-preview');
            const formDiv = document.getElementById(section + '-form');
            
            if (formDiv.style.display === 'block') {
                formDiv.style.display = 'none';
                previewDiv.style.display = (section === 'services' || section === 'portfolio' || section === 'reviews') ? 'grid' : 'block';
            } else {
                formDiv.style.display = 'block';
                previewDiv.style.display = 'none';
            }
        }
    </script>
</body>
</html>