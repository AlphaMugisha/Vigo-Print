<?php
include '../db_connect.php'; // Go up one folder to find the connection

$message = "";

// UPDATE LOGIC: If the user clicks "Save Settings"
if (isset($_POST['update_settings'])) {
    $hero_h1 = mysqli_real_escape_string($conn, $_POST['hero_h1']);
    $hero_p = mysqli_real_escape_string($conn, $_POST['hero_p']);
    $about_h2 = mysqli_real_escape_string($conn, $_POST['about_h2']);
    $stat_years = mysqli_real_escape_string($conn, $_POST['stat_years']);
    $stat_projects = mysqli_real_escape_string($conn, $_POST['stat_projects']);

    $update_sql = "UPDATE site_settings SET 
                   hero_h1='$hero_h1', 
                   hero_p='$hero_p', 
                   about_h2='$about_h2',
                   stat_years='$stat_years',
                   stat_projects='$stat_projects' 
                   WHERE id=1";

    if ($conn->query($update_sql)) {
        $message = "Site updated successfully!";
    }
}

// FETCH CURRENT DATA
$res = $conn->query("SELECT * FROM site_settings WHERE id=1");
$data = $res->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>VIGO Admin Dashboard</title>
    <link rel="stylesheet" href="admin-style.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>

    <div class="admin-sidebar">
        <h3>VIGO Admin</h3>
        <p>Dashboard</p>
        <p><a href="../index.php" target="_blank" style="color: #8DC63F; text-decoration: none;">View Live Site</a></p>
    </div>

    <div class="admin-main">
        <h2>Manage Homepage Content</h2>
        
        <?php if($message): ?>
            <div class="alert"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="card">
                <h3>Hero Section</h3>
                <div class="form-group">
                    <label>Hero Main Heading</label>
                    <input type="text" name="hero_h1" value="<?php echo $data['hero_h1']; ?>">
                </div>
                <div class="form-group">
                    <label>Hero Subtext</label>
                    <textarea name="hero_p"><?php echo $data['hero_p']; ?></textarea>
                </div>
            </div>

            <div class="card">
                <h3>About & Stats</h3>
                <div class="form-group">
                    <label>About Heading</label>
                    <input type="text" name="about_h2" value="<?php echo $data['about_h2']; ?>">
                </div>
                <div style="display: flex; gap: 20px;">
                    <div class="form-group" style="flex: 1;">
                        <label>Years of Experience</label>
                        <input type="text" name="stat_years" value="<?php echo $data['stat_years']; ?>">
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label>Completed Projects</label>
                        <input type="text" name="stat_projects" value="<?php echo $data['stat_projects']; ?>">
                    </div>
                </div>
            </div>

            <button type="submit" name="update_settings" class="btn-save">Save All Changes</button>
        </form>
    </div>

</body>
</html>