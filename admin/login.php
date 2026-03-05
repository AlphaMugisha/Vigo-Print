<?php
// admin/login.php
session_start();
require_once '../includes/db.php';

// STRICT SECURITY RULE: Force brand new login attempt on normal page load
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    session_unset();
    session_destroy();
    session_start(); 
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Fetch the user from the database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verify the password
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $user['username'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Admin Login | VIGO PRINT</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --primary: #25D366;
            --primary-dark: #1ebc5a;
            --bg-color: #f0f4f3;
            --text-main: #111;
            --text-muted: #666;
            --input-bg: #f8f9fa;
            --input-border: #e9ecef;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #f0f4f3 0%, #d9e2e0 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: var(--text-main);
        }

        .login-wrapper {
            width: 100%;
            max-width: 420px;
            padding: 20px;
            box-sizing: border-box;
        }

        .login-card {
            background: white;
            padding: 50px 40px;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.08), 0 1px 3px rgba(0,0,0,0.03);
            text-align: center;
        }

        .brand {
            margin-bottom: 35px;
        }

        .brand h2 {
            margin: 0;
            font-size: 30px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .brand h2 span {
            color: var(--primary);
        }

        .brand p {
            margin: 8px 0 0;
            color: var(--text-muted);
            font-size: 15px;
            font-weight: 500;
        }

        .form-group {
            margin-bottom: 22px;
            text-align: left;
            position: relative;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 12px;
            color: var(--text-muted);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 15px 16px;
            background: var(--input-bg);
            border: 2px solid var(--input-border);
            border-radius: 12px;
            box-sizing: border-box;
            font-family: inherit;
            font-size: 15px;
            font-weight: 500;
            color: var(--text-main);
            transition: all 0.3s ease;
            outline: none;
        }

        /* Focus glow effect */
        input[type="text"]:focus, input[type="password"]:focus {
            background: white;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(37, 211, 102, 0.15);
        }

        .btn-login {
            width: 100%;
            padding: 16px;
            background: var(--text-main);
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }

        /* Button hover animation */
        .btn-login:hover {
            background: var(--primary);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(37, 211, 102, 0.25);
        }

        .error {
            background: #fff5f5;
            color: #e74c3c;
            padding: 14px;
            border-radius: 10px;
            font-size: 14px;
            margin-bottom: 25px;
            border: 1px solid #ffebeb;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .security-badge {
            margin-top: 30px;
            font-size: 13px;
            color: #999;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            font-weight: 500;
        }
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            margin-top: 25px;
            transition: all 0.3s ease;
        }

        .back-link:hover {
            color: var(--text-main);
            transform: translateX(-5px); /* Smoothly slides left on hover */
        }
    </style>
</head>
<body>

    <div class="login-wrapper">
        <div class="login-card">
            
            <div class="brand">
                <h2>VIGO <span>PRINT</span></h2>
                <p>Management Portal</p>
            </div>
            
            <?php if ($error): ?>
                <div class="error"><i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label>Admin Username</label>
                    <input type="text" name="username" placeholder="Enter your username" required autocomplete="off">
                </div>
                
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="••••••••" required>
                </div>
                
                <button type="submit" class="btn-login">
                    Access Dashboard <i class="fas fa-arrow-right"></i>
                </button>
            </form>

            <div class="security-badge">
                <i class="fas fa-lock"></i> Secure 256-bit Encrypted Session
            </div>
            <a href="../index.php" style="text-decoration: none; color: #888; font-size: 14px; font-weight: 600; margin-top: 20px; display: inline-block;"><i class="fas fa-arrow-left" style="margin-right: 5px;"></i> Back to website</a>

        </div>
    </div>

</body>
</html>