<?php
include '../db_connect.php';

// 1. UPDATE SETTINGS
if (isset($_POST['update_settings'])) {
    $h1 = $conn->real_escape_string($_POST['hero_h1']);
    $hp = $conn->real_escape_string($_POST['hero_p']);
    $hv = $conn->real_escape_string($_POST['hero_video_url']);
    $ah2 = $conn->real_escape_string($_POST['about_h2']);
    $ap1 = $conn->real_escape_string($_POST['about_p1']);
    $sy = $conn->real_escape_string($_POST['stat_years']);
    $sp = $conn->real_escape_string($_POST['stat_projects']);

    $sql = "UPDATE site_settings SET hero_h1='$h1', hero_p='$hp', hero_video_url='$hv', about_h2='$ah2', about_p1='$ap1', stat_years='$sy', stat_projects='$sp' WHERE id=1";
    $conn->query($sql);
}

// 2. ADD SERVICE
if (isset($_POST['add_service'])) {
    $icon = $conn->real_escape_string($_POST['icon']);
    $title = $conn->real_escape_string($_POST['title']);
    $desc = $conn->real_escape_string($_POST['desc']);
    $conn->query("INSERT INTO services (icon_class, title, description) VALUES ('$icon', '$title', '$desc')");
}

// 3. DELETE SERVICE
if (isset($_GET['delete_service'])) {
    $id = (int)$_GET['delete_service'];
    $conn->query("DELETE FROM services WHERE id=$id");
    header("Location: index.php");
    exit();
}

// Fetch current settings to populate the form
$site = $conn->query("SELECT * FROM site_settings WHERE id=1")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin-style.css">
</head>
<body>

    <div class="sidebar">
        <h2>VIGO Admin</h2>
        <a href="#settings">Site Settings</a>
        <a href="#services">Manage Services</a>
        <a href="../index.php" target="_blank" style="color: #8DC63F; margin-top: 20px;">View Live Site</a>
    </div>

    <div class="main-content">
        <h1>Dashboard</h1>

        <div class="card" id="settings">
            <h3>General Content</h3>
            <form method="POST">
                <div class="form-group"><label>Hero Headline</label><input type="text" name="hero_h1" value="<?php echo $site['hero_h1']; ?>"></div>
                <div class="form-group"><label>Hero Paragraph</label><textarea name="hero_p"><?php echo $site['hero_p']; ?></textarea></div>
                <div class="form-group"><label>Hero Video URL</label><input type="text" name="hero_video_url" value="<?php echo $site['hero_video_url']; ?>"></div>
                <div class="form-group"><label>About Heading</label><input type="text" name="about_h2" value="<?php echo $site['about_h2']; ?>"></div>
                <div class="form-group"><label>About Paragraph</label><textarea name="about_p1"><?php echo $site['about_p1']; ?></textarea></div>
                
                <div style="display: flex; gap: 10px;">
                    <div class="form-group" style="flex:1"><label>Years of Exp</label><input type="text" name="stat_years" value="<?php echo $site['stat_years']; ?>"></div>
                    <div class="form-group" style="flex:1"><label>Total Projects</label><input type="text" name="stat_projects" value="<?php echo $site['stat_projects']; ?>"></div>
                </div>
                <button type="submit" name="update_settings" class="btn">Save Settings</button>
            </form>
        </div>

        <div class="card" id="services">
            <h3>Add New Service</h3>
            <form method="POST" style="display: flex; gap: 10px; margin-bottom: 20px;">
                <input type="text" name="icon" placeholder="Icon (e.g. fas fa-print)" required style="width: 20%;">
                <input type="text" name="title" placeholder="Service Title" required style="width: 30%;">
                <input type="text" name="desc" placeholder="Description" required style="width: 40%;">
                <button type="submit" name="add_service" class="btn">Add</button>
            </form>

            <table>
                <tr><th>Icon Class</th><th>Title</th><th>Description</th><th>Action</th></tr>
                <?php $services = $conn->query("SELECT * FROM services"); while($s = $services->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $s['icon_class']; ?></td>
                    <td><?php echo $s['title']; ?></td>
                    <td><?php echo $s['description']; ?></td>
                    <td><a href="?delete_service=<?php echo $s['id']; ?>" class="btn-delete">Delete</a></td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>

    </div>
</body>
</html>