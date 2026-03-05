<?php
// admin/services.php
session_start();
require_once '../includes/db.php';

// Security Lock
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Handle Adding a New Service
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    $icon = trim($_POST['icon']);
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);

    $stmt = $pdo->prepare("INSERT INTO services (icon, title, description) VALUES (:icon, :title, :description)");
    $stmt->execute(['icon' => $icon, 'title' => $title, 'description' => $description]);
    
    // Refresh the page to show the new service
    header("Location: services.php?msg=added");
    exit;
}

// Fetch all existing services to display
$services = $pdo->query("SELECT * FROM services ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Services | VIGO PRINT</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; margin: 0; display: flex; background: #f4f7f6; }
        .sidebar { width: 250px; background: #111; color: white; height: 100vh; padding: 20px; box-sizing: border-box; position: fixed; }
        .sidebar h2 { color: #25D366; text-align: center; margin-bottom: 40px; }
        .sidebar a { display: block; color: #ccc; text-decoration: none; padding: 12px 15px; border-radius: 8px; margin-bottom: 10px; transition: 0.3s; }
        .sidebar a:hover, .sidebar a.active { background: #333; color: white; }
        .sidebar a i { margin-right: 10px; width: 20px; }
        
        .main-content { margin-left: 250px; padding: 40px; width: calc(100% - 250px); box-sizing: border-box; }
        .header { background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); margin-bottom: 30px; }
        
        .card { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); margin-bottom: 30px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: 600; font-size: 14px; }
        input[type="text"], textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; font-family: inherit; }
        button.btn { padding: 10px 20px; background: #25D366; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #f9f9f9; font-weight: 600; }
        .action-btn { padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 13px; margin-right: 5px; color: white; }
        .btn-edit { background: #3498db; }
        .btn-delete { background: #e74c3c; }
        .success-msg { background: #d4edda; color: #155724; padding: 10px; border-radius: 6px; margin-bottom: 20px; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>VIGO PRINT</h2>
        <a href="dashboard.php"><i class="fas fa-home"></i> Dashboard Home</a>
        <a href="settings.php"><i class="fas fa-cog"></i> Site Settings</a>
        <a href="services.php" class="active"><i class="fas fa-layer-group"></i> Manage Services</a>
        <a href="portfolio.php"><i class="fas fa-images"></i> Portfolio</a>
        <a href="testimonials.php"><i class="fas fa-star"></i> Testimonials</a>
        <a href="logout.php" style="color: #e74c3c; margin-top: 50px;"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="main-content">
        <div class="header">
            <h2>Manage Services</h2>
        </div>

        <?php if (isset($_GET['msg']) && $_GET['msg'] == 'added'): ?>
            <div class="success-msg">Service successfully added!</div>
        <?php endif; ?>
        <?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
            <div class="success-msg" style="background: #f8d7da; color: #721c24;">Service successfully deleted!</div>
        <?php endif; ?>

        <div class="card">
            <h3>Add New Service</h3>
            <form method="POST" action="services.php">
                <input type="hidden" name="action" value="add">
                <div class="form-group">
                    <label>FontAwesome Icon Class (e.g., fas fa-print)</label>
                    <input type="text" name="icon" required placeholder="fas fa-print">
                </div>
                <div class="form-group">
                    <label>Service Title</label>
                    <input type="text" name="title" required placeholder="Offset Printing">
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="3" required placeholder="Describe the service..."></textarea>
                </div>
                <button type="submit" class="btn">Add Service</button>
            </form>
        </div>

        <div class="card">
            <h3>Active Services</h3>
            <table>
                <thead>
                    <tr>
                        <th>Icon</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($services as $service): ?>
                    <tr>
                        <td><i class="<?php echo htmlspecialchars($service['icon']); ?>" style="font-size: 20px;"></i></td>
                        <td><?php echo htmlspecialchars($service['title']); ?></td>
                        <td><?php echo htmlspecialchars(substr($service['description'], 0, 50)) . '...'; ?></td>
                        <td>
                            <a href="#" class="action-btn btn-edit">Edit</a> 
                            
                            <a href="service_delete.php?id=<?php echo $service['id']; ?>" class="action-btn btn-delete" onclick="return confirm('Are you sure you want to delete this service?');">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>