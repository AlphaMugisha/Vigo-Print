<?php
// admin/setup_user.php
require_once '../includes/db.php';

// Change these to whatever you want your admin login to be
$admin_username = 'admin';
$admin_password = 'password123'; // Make this secure!

// Hash the password securely
$hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);

try {
    // Check if user already exists to prevent duplicates
    $check = $pdo->query("SELECT * FROM users WHERE username = '$admin_username'");
    if ($check->rowCount() > 0) {
        die("User '$admin_username' already exists in the database.");
    }

    // Insert the new admin user
    $sql = "INSERT INTO users (username, password) VALUES (:username, :password)";
    $stmt = $pdo->prepare($sql);
    
    $stmt->execute([
        ':username' => $admin_username,
        ':password' => $hashed_password
    ]);

    echo "<h1>Success!</h1>";
    echo "<p>Admin user '<strong>$admin_username</strong>' has been securely created.</p>";
    echo "<p style='color:red;'><strong>SECURITY WARNING:</strong> Please delete this setup_user.php file immediately so no one else can create admin accounts!</p>";
    
} catch(PDOException $e) {
    die("Error creating user: " . $e->getMessage());
}
?>