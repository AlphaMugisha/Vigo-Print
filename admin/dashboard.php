<?php
// admin/dashboard.php
session_start();
require_once '../includes/db.php';

// SECURITY LOCK: Check if user is actually logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}
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
        body { font-family: 'Plus Jakarta Sans', sans-serif; margin: 0; display: flex; background: #f4f7f6; }
        .sidebar { width: 250px; background: #111; color: white; height: 100vh; padding: 20px; box-sizing: border-box; position: fixed; }
        .sidebar h2 { color: #25D366; text-align: center; margin-bottom: 40px; }
        .sidebar a { display: block; color: #ccc; text-decoration: none; padding: 12px 15px; border-radius: 8px; margin-bottom: 10px; transition: 0.3s; }
        .sidebar a:hover, .sidebar a.active { background: #333; color: white; }
        .sidebar a i { margin-right: 10px; width: 20px; }
        .main-content { margin-left: 250px; padding: 40px; width: calc(100% - 250px); box-sizing: border-box; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
        .welcome-card { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>VIGO PRINT</h2>
        <a href="dashboard.php" class="active"><i class="fas fa-home"></i> Dashboard Home</a>
        <a href="#"><i class="fas fa-cog"></i> Site Settings</a>
        <a href="#"><i class="fas fa-layer-group"></i> Manage Services</a>
        <a href="#"><i class="fas fa-images"></i> Portfolio</a>
        <a href="logout.php" style="color: #e74c3c; margin-top: 50px;"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="main-content">
        <div class="header">
            <h2>Dashboard Overview</h2>
            <div>Welcome, <strong><?php echo htmlspecialchars($_SESSION['admin_username']); ?></strong></div>
        </div>

        <div class="welcome-card">
            <h3>System is Online</h3>
            <p>Welcome to your control panel. From here, you will be able to edit the website text, update services, and add new portfolio items dynamically.</p>
            <p>Select an option from the sidebar to begin.</p>
        </div>
    </div>

</body>
</html>