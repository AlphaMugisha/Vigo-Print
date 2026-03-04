<?php
session_start();

$error = "";

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Your specific credentials
    if ($username === "admin" && $password === "123") {
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin/index.php");
        exit();
    } else {
        $error = "Invalid Username or Password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login | VIGO PRINT</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .login-box {
            max-width: 400px; margin: 150px auto; padding: 40px;
            background: var(--primary-dark); border-radius: 8px; color: white;
            text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        .login-box input {
            width: 100%; padding: 12px; margin: 10px 0;
            border-radius: 4px; border: none;
        }
        .error-text { color: #ff4d4d; font-size: 0.9rem; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Admin Login</h2>
        <?php if($error) echo "<p class='error-text'>$error</p>"; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login" class="btn btn-primary" style="width: 100%; margin-top: 20px;">Login</button>
        </form>
        <p><a href="index.php" style="color: grey; font-size: 0.8rem; text-decoration: none; display: block; margin-top: 20px;">Return to Site</a></p>
    </div>
</body>
</html>