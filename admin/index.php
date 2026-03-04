<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) { header("Location: ../login.php"); exit(); }
include '../db_connect.php';

$message = "";

// 1. UPDATE GENERAL SETTINGS
if (isset($_POST['save_general'])) {
    $h1 = mysqli_real_escape_string($conn, $_POST['hero_h1']);
    $hp = mysqli_real_escape_string($conn, $_POST['hero_p']);
    $hv = mysqli_real_escape_string($conn, $_POST['hero_video']);
    $a2 = mysqli_real_escape_string($conn, $_POST['about_h2']);
    $sy = mysqli_real_escape_string($conn, $_POST['stat_years']);
    $sp = mysqli_real_escape_string($conn, $_POST['stat_projects']);
    $sa = mysqli_real_escape_string($conn, $_POST['stat_accuracy']);

    $sql = "UPDATE site_settings SET hero_h1='$h1', hero_p='$hp', hero_video_url='$hv', 
            about_h2='$a2', stat_years='$sy', stat_projects='$sp', stat_accuracy='$sa' WHERE id=1";
    if($conn->query($sql)) $message = "General Settings Updated!";
}

// 2. ADD NEW SERVICE
if (isset($_POST['add_service'])) {
    $title = $_POST['s_title'];
    $desc = $_POST['s_desc'];
    $icon = $_POST['s_icon'];
    $conn->query("INSERT INTO services (icon_class, title, description) VALUES ('$icon', '$title', '$desc')");
    $message = "Service Added!";
}

// 3. ADD NEW PORTFOLIO ITEM
if (isset($_POST['add_work'])) {
    $title = $_POST['w_title'];
    $cat = $_POST['w_cat'];
    $url = $_POST['w_url'];
    $conn->query("INSERT INTO portfolio (title, category, image_url) VALUES ('$title', '$cat', '$url')");
    $message = "Portfolio Updated!";
}

$site = $conn->query("SELECT * FROM site_settings WHERE id=1")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>VIGO Full Admin</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <div class="admin-sidebar">
        <h2>VIGO Admin</h2>
        <a href="logout.php" style="color: #ff4d4d; text-decoration: none;"><i class="fas fa-sign-out-alt"></i> Logout</a>
        <hr>
        <p><a href="../index.php" target="_blank" style="color: #8DC63F;">View Live Site</a></p>
    </div>

    <div class="admin-main">
        <h1>Control Center</h1>
        <?php if($message) echo "<div class='alert'>$message</div>"; ?>

        <form method="POST">
            <div class="card">
                <h3><i class="fas fa-desktop"></i> Hero & About Content</h3>
                <div class="form-group">
                    <label>Hero Headline</label>
                    <input type="text" name="hero_h1" value="<?php echo $site['hero_h1']; ?>">
                </div>
                <div class="form-group">
                    <label>Background Video URL (.mp4)</label>
                    <input type="text" name="hero_video" value="<?php echo $site['hero_video_url']; ?>">
                </div>
                <div style="display: flex; gap: 15px;">
                    <div class="form-group">
                        <label>Years Exp.</label>
                        <input type="text" name="stat_years" value="<?php echo $site['stat_years']; ?>">
                    </div>
                    <div class="form-group">
                        <label>Projects</label>
                        <input type="text" name="stat_projects" value="<?php echo $site['stat_projects']; ?>">
                    </div>
                    <div class="form-group">
                        <label>Accuracy %</label>
                        <input type="text" name="stat_accuracy" value="<?php echo $site['stat_accuracy']; ?>">
                    </div>
                </div>
                <button type="submit" name="save_general" class="btn-save">Update Main Content</button>
            </div>
        </form>

        <div class="card">
            <h3><i class="fas fa-concierge-bell"></i> Add New Service</h3>
            <form method="POST">
                <input type="text" name="s_icon" placeholder="Icon Class (e.g., fas fa-print)" required>
                <input type="text" name="s_title" placeholder="Service Title" required>
                <textarea name="s_desc" placeholder="Service Description"></textarea>
                <button type="submit" name="add_service" class="btn-save" style="background: #00AEEF; color: white;">Add Service</button>
            </form>
        </div>

        <div class="card">
            <h3><i class="fas fa-images"></i> Add Portfolio Project</h3>
            <form method="POST">
                <input type="text" name="w_title" placeholder="Project Name" required>
                <input type="text" name="w_cat" placeholder="Category" required>
                <input type="text" name="w_url" placeholder="Image URL" required>
                <button type="submit" name="add_work" class="btn-save">Add to Gallery</button>
            </form>
        </div>
    </div>
</body>
</html>