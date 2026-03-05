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

// Determine which section is active from the URL (default to hero)
$section = isset($_GET['section']) ? $_GET['section'] : 'hero';

// --- HANDLE FORM SUBMISSIONS ---

// 1. Handle Settings Updates (Hero, About, CTA)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_settings'])) {
    // We update the specific fields based on which form was submitted
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
    
    $success_msg = "Section updated successfully!";
    $section = $_POST['section']; // Keep user on the same tab
}

// 2. Handle Adding a Service
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_service'])) {
    $stmt = $pdo->prepare("INSERT INTO services (icon, title, description) VALUES (:icon, :title, :description)");
    $stmt->execute(['icon' => $_POST['icon'], 'title' => $_POST['title'], 'description' => $_POST['description']]);
    $success_msg = "New service added successfully!";
    $section = 'services';
}

// --- FETCH DATA FOR DISPLAY ---
$settings = $pdo->query("SELECT * FROM site_settings WHERE id = 1")->fetch(PDO::FETCH_ASSOC);
$services = $pdo->query("SELECT * FROM services ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | VIGO PRINT</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; margin: 0; display: flex; background: #f4f7f6; color: #333; }
        
        /* Sidebar Styles */
        .sidebar { width: 250px; background: #111; color: white; height: 100vh; padding: 20px; box-sizing: border-box; position: fixed; }
        .sidebar h2 { color: #25D366; text-align: center; margin-bottom: 40px; font-size: 20px; letter-spacing: 1px; }
        .sidebar a { display: block; color: #999; text-decoration: none; padding: 12px 15px; border-radius: 8px; margin-bottom: 8px; transition: 0.3s; font-weight: 600; }
        .sidebar a:hover, .sidebar a.active { background: #222; color: #25D366; }
        .sidebar a i { margin-right: 12px; width: 20px; text-align: center; }
        
        /* Main Content Styles */
        .main-content { margin-left: 250px; padding: 40px; width: calc(100% - 250px); box-sizing: border-box; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .header h2 { margin: 0; font-size: 28px; }
        .header-profile { background: white; padding: 10px 20px; border-radius: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); font-weight: 600; }
        
        /* Cards & Forms */
        .card { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); margin-bottom: 30px; }
        .card-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 20px; }
        .card-header h3 { margin: 0; color: #111; }
        
        .preview-data { margin-bottom: 20px; }
        .preview-label { font-size: 12px; color: #888; text-transform: uppercase; letter-spacing: 1px; font-weight: 700; margin-bottom: 5px; }
        .preview-value { font-size: 16px; margin-bottom: 20px; padding: 15px; background: #f9f9f9; border-radius: 8px; border-left: 4px solid #25D366; }
        
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; font-size: 14px; color: #555; }
        input[type="text"], textarea { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; font-family: inherit; transition: 0.3s; }
        input[type="text"]:focus, textarea:focus { border-color: #25D366; outline: none; }
        
        /* Buttons */
        .btn { padding: 10px 20px; border: none; border-radius: 8px; cursor: pointer; font-weight: 700; font-size: 14px; transition: 0.3s; display: inline-flex; align-items: center; gap: 8px; }
        .btn-edit { background: #f0f0f0; color: #333; }
        .btn-edit:hover { background: #e0e0e0; }
        .btn-save { background: #25D366; color: white; }
        .btn-save:hover { background: #1ebc5a; }
        .btn-cancel { background: #fff; color: #e74c3c; border: 1px solid #e74c3c; }
        
        .success-msg { background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: 600; border-left: 4px solid #28a745; display: flex; align-items: center; gap: 10px; }
        
        /* Grid for Multi-items (Services/Portfolio) */
        .grid-cards { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; }
        .item-card { background: #f9f9f9; padding: 20px; border-radius: 10px; border: 1px solid #eee; }
        .item-card i { font-size: 30px; color: #25D366; margin-bottom: 15px; }
        .item-card h4 { margin: 0 0 10px 0; font-size: 18px; }
        .item-card p { font-size: 14px; color: #666; margin-bottom: 15px; line-height: 1.5; }
        
        /* Hide forms initially */
        .edit-form { display: none; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>VIGO PRINT</h2>
        <a href="?section=hero" class="<?= $section == 'hero' ? 'active' : '' ?>"><i class="fas fa-home"></i> Hero Section</a>
        <a href="?section=about" class="<?= $section == 'about' ? 'active' : '' ?>"><i class="fas fa-info-circle"></i> About Section</a>
        <a href="?section=services" class="<?= $section == 'services' ? 'active' : '' ?>"><i class="fas fa-layer-group"></i> Services</a>
        <a href="?section=cta" class="<?= $section == 'cta' ? 'active' : '' ?>"><i class="fas fa-bullhorn"></i> CTA & Footer</a>
        <a href="logout.php" style="color: #e74c3c; margin-top: 50px;"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="main-content">
        <div class="header">
            <h2>Dashboard Overview</h2>
            <div class="header-profile"><i class="fas fa-user-circle"></i> <?= htmlspecialchars($_SESSION['admin_username']); ?></div>
        </div>

        <?php if ($success_msg): ?>
            <div class="success-msg"><i class="fas fa-check-circle"></i> <?= $success_msg; ?></div>
        <?php endif; ?>

        <?php if ($section == 'hero'): ?>
        <div class="card">
            <div class="card-header">
                <h3>Current Hero Section</h3>
                <button class="btn btn-edit" onclick="toggleView('hero')"><i class="fas fa-pen"></i> Edit Content</button>
            </div>
            
            <div id="hero-preview">
                <div class="preview-label">Hero Badge</div>
                <div class="preview-value"><?= htmlspecialchars($settings['hero_badge']); ?></div>
                
                <div class="preview-label">Main Title</div>
                <div class="preview-value" style="font-size: 24px; font-weight: 700;"><?= htmlspecialchars($settings['hero_title']); ?></div>
                
                <div class="preview-label">Description</div>
                <div class="preview-value"><?= htmlspecialchars($settings['hero_desc']); ?></div>
                
                <div class="preview-label">WhatsApp Number</div>
                <div class="preview-value"><i class="fab fa-whatsapp"></i> +<?= htmlspecialchars($settings['whatsapp']); ?></div>
            </div>

            <form id="hero-form" class="edit-form" method="POST" action="">
                <input type="hidden" name="update_settings" value="1">
                <input type="hidden" name="section" value="hero">
                
                <div class="form-group">
                    <label>Hero Badge</label>
                    <input type="text" name="hero_badge" value="<?= htmlspecialchars($settings['hero_badge']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Main Title</label>
                    <input type="text" name="hero_title" value="<?= htmlspecialchars($settings['hero_title']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="hero_desc" rows="3" required><?= htmlspecialchars($settings['hero_desc']); ?></textarea>
                </div>
                <div class="form-group">
                    <label>WhatsApp Number</label>
                    <input type="text" name="whatsapp" value="<?= htmlspecialchars($settings['whatsapp']); ?>" required>
                </div>
                
                <button type="submit" class="btn btn-save"><i class="fas fa-save"></i> Save Changes</button>
                <button type="button" class="btn btn-cancel" onclick="toggleView('hero')">Cancel</button>
            </form>
        </div>
        <?php endif; ?>

        <?php if ($section == 'about'): ?>
        <div class="card">
            <div class="card-header">
                <h3>Current About Section</h3>
                <button class="btn btn-edit" onclick="toggleView('about')"><i class="fas fa-pen"></i> Edit Content</button>
            </div>
            
            <div id="about-preview">
                <div class="preview-label">About Title</div>
                <div class="preview-value" style="font-size: 20px; font-weight: 700;"><?= htmlspecialchars($settings['about_title']); ?></div>
                
                <div class="preview-label">Paragraph 1</div>
                <div class="preview-value"><?= htmlspecialchars($settings['about_desc_1']); ?></div>
                
                <div class="preview-label">Paragraph 2</div>
                <div class="preview-value"><?= htmlspecialchars($settings['about_desc_2']); ?></div>
            </div>

            <form id="about-form" class="edit-form" method="POST" action="">
                <input type="hidden" name="update_settings" value="1">
                <input type="hidden" name="section" value="about">
                
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
                
                <button type="submit" class="btn btn-save"><i class="fas fa-save"></i> Save Changes</button>
                <button type="button" class="btn btn-cancel" onclick="toggleView('about')">Cancel</button>
            </form>
        </div>
        <?php endif; ?>

        <?php if ($section == 'services'): ?>
        <div class="card">
            <div class="card-header">
                <h3>Active Services</h3>
                <button class="btn btn-save" onclick="toggleView('services')"><i class="fas fa-plus"></i> Add New Service</button>
            </div>
            
            <div id="services-preview" class="grid-cards">
                <?php foreach ($services as $service): ?>
                <div class="item-card">
                    <i class="<?= htmlspecialchars($service['icon']); ?>"></i>
                    <h4><?= htmlspecialchars($service['title']); ?></h4>
                    <p><?= htmlspecialchars($service['description']); ?></p>
                    <a href="service_delete.php?id=<?= $service['id']; ?>" class="btn btn-cancel" style="padding: 5px 10px; font-size: 12px;" onclick="return confirm('Delete this service?');"><i class="fas fa-trash"></i> Delete</a>
                </div>
                <?php endforeach; ?>
            </div>

            <form id="services-form" class="edit-form" method="POST" action="">
                <input type="hidden" name="add_service" value="1">
                
                <div class="form-group">
                    <label>Icon Class (e.g. fas fa-print)</label>
                    <input type="text" name="icon" required>
                </div>
                <div class="form-group">
                    <label>Service Title</label>
                    <input type="text" name="title" required>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="3" required></textarea>
                </div>
                
                <button type="submit" class="btn btn-save"><i class="fas fa-plus"></i> Create Service</button>
                <button type="button" class="btn btn-cancel" onclick="toggleView('services')">Cancel</button>
            </form>
        </div>
        <?php endif; ?>

        <?php if ($section == 'cta'): ?>
        <div class="card">
            <div class="card-header">
                <h3>Current CTA & Footer</h3>
                <button class="btn btn-edit" onclick="toggleView('cta')"><i class="fas fa-pen"></i> Edit Content</button>
            </div>
            
            <div id="cta-preview">
                <div class="preview-label">CTA Title</div>
                <div class="preview-value"><?= htmlspecialchars($settings['cta_title']); ?></div>
                
                <div class="preview-label">Footer About Text</div>
                <div class="preview-value"><?= htmlspecialchars($settings['footer_about']); ?></div>
                
                <div class="preview-label">Physical Address</div>
                <div class="preview-value"><?= htmlspecialchars($settings['address']); ?></div>
            </div>

            <form id="cta-form" class="edit-form" method="POST" action="">
                <input type="hidden" name="update_settings" value="1">
                <input type="hidden" name="section" value="cta">
                
                <div class="form-group">
                    <label>CTA Title</label>
                    <input type="text" name="cta_title" value="<?= htmlspecialchars($settings['cta_title']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Footer About Text</label>
                    <textarea name="footer_about" rows="3" required><?= htmlspecialchars($settings['footer_about']); ?></textarea>
                </div>
                <div class="form-group">
                    <label>Physical Address</label>
                    <textarea name="address" rows="2" required><?= htmlspecialchars($settings['address']); ?></textarea>
                </div>
                
                <button type="submit" class="btn btn-save"><i class="fas fa-save"></i> Save Changes</button>
                <button type="button" class="btn btn-cancel" onclick="toggleView('cta')">Cancel</button>
            </form>
        </div>
        <?php endif; ?>

    </div>

    <script>
        function toggleView(section) {
            const previewDiv = document.getElementById(section + '-preview');
            const formDiv = document.getElementById(section + '-form');
            
            if (previewDiv.style.display === 'none') {
                previewDiv.style.display = 'block';
                if (section === 'services') previewDiv.style.display = 'grid';
                formDiv.style.display = 'none';
            } else {
                previewDiv.style.display = 'none';
                formDiv.style.display = 'block';
            }
        }
    </script>
</body>
</html>