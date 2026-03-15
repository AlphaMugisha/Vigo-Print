<?php
// --- PHP FORM PROCESSING LOGIC ---
require_once 'includes/db.php';

$statusMsg = '';
$statusClass = '';

if(isset($_POST['submit'])){
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $message = htmlspecialchars($_POST['message']); // Project Details
    
    // --- ARTWORK UPLOAD LOGIC ---
    $artwork_path = null; // Default to null if they don't upload anything
    
    if (isset($_FILES['artwork']) && $_FILES['artwork']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        // Create folder if it doesn't exist
        if (!is_dir($upload_dir)) { mkdir($upload_dir, 0777, true); }
        
        // Clean the filename and add a timestamp so files don't overwrite each other
        $filename = time() . '_' . preg_replace("/[^a-zA-Z0-9.]/", "", basename($_FILES['artwork']['name']));
        $target_file = $upload_dir . $filename;
        
        if (move_uploaded_file($_FILES['artwork']['tmp_name'], $target_file)) {
            $artwork_path = $target_file; // e.g., "uploads/167890_design.pdf"
        }
    }
    // ----------------------------

    if(!empty($name) && !empty($email) && !empty($message)){
        try {
            // Save to Database Inbox (Now includes artwork_url)
            $stmt = $pdo->prepare("INSERT INTO contact_inbox (name, email, phone, project_details, artwork_url) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $email, $phone, $message, $artwork_path]);

            $statusMsg = 'Quote Request Submitted Successfully! We will contact you shortly.';
            $statusClass = 'alert-success';
        } catch(PDOException $e) {
            $statusMsg = 'Submission failed, please try again.';
            $statusClass = 'alert-error';
        }
    } else {
        $statusMsg = 'Please fill all mandatory fields.';
        $statusClass = 'alert-error';
    }
}
include 'preloader.php';
// Fetch settings for the footer/contact info
$settings = $pdo->query("SELECT * FROM site_settings WHERE id = 1")->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | VIGO PRINT</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        /* Quick style to make the file input look decent */
        input[type="file"].form-control {
            padding: 10px;
            background: #fff;
            border: 1px dashed #ccc;
            cursor: pointer;
        }
    </style>
</head>
<body class="loading-lock">

    <header id="navbar" class="scrolled">
        <div class="nav-container">
            <a href="index.php" class="logo"><span class="vigo">VIGO</span> <span class="print">PRINT</span></a>
            <div class="menu-toggle" id="mobile-toggle"><i class="fas fa-bars"></i></div>
            <nav id="nav-menu">
                <a href="index.php">Home</a>
                <a href="index.php#services">Services</a>
                <a href="index.php#about">About Us</a>
                <a href="index.php#portfolio">Portfolio</a>
                <a href="contact.php" class="nav-btn">Contact Us</a>
            </nav>
        </div>
    </header>

    <section class="contact-hero">
        <div class="hero-content reveal">
            <h1>Let's Print Something Great.</h1>
            <p>Reach out to our Kigali facility for high-volume commercial quotes.</p>
        </div>
    </section>

    <section class="contact-section">
        <div class="contact-form-wrapper reveal reveal-left">
            <?php if(!empty($statusMsg)): ?>
                <div class="alert <?php echo $statusClass; ?>" style="background: #e8f5e9; color: #2e7d32; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: 600;"><?php echo $statusMsg; ?></div>
            <?php endif; ?>

            <form action="" method="POST" class="contact-form" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Name:</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. Media Ltd." required>
                </div>
                <div class="form-group">
                    <label>Email Address *</label>
                    <input type="email" name="email" class="form-control" placeholder="your@email.com" required>
                </div>
                <div class="form-group">
                    <label>Phone / WhatsApp</label>
                    <input type="text" name="phone" class="form-control" placeholder="+250 788 858 358">
                </div>
                <div class="form-group">
                    <label>Project Details *</label>
                    <textarea name="message" class="form-control" placeholder="Quantity, paper type, etc..." required></textarea>
                </div>
                
                <div class="form-group">
                    <label>Attach Artwork (PDF, PNG, JPG, ZIP) - Optional</label>
                    <input type="file" name="artwork" class="form-control" accept=".pdf,.png,.jpg,.jpeg,.zip">
                </div>

                <button type="submit" name="submit" class="btn btn-primary" style="width: 100%; justify-content: center; margin-top: 10px;">
                    <i class="fas fa-paper-plane"></i> Submit Quote Request
                </button>
            </form>
        </div>

        <div class="contact-info reveal reveal-right">
            <h3>Direct Contact</h3>
            <div class="info-item"><i class="fas fa-map-marker-alt"></i><div><strong>Head Office & Factory</strong><br><?= $settings['address'] ?></div></div>
            <div class="info-item"><i class="fas fa-phone-alt"></i><div><strong>Phone / WhatsApp</strong><br>+<?= htmlspecialchars($settings['whatsapp']) ?></div></div>
            
            <div class="map-container">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3987.502919429141!2d30.056024474251433!3d-1.9520858367203387!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x19dca429b6999999%3A0x8888888888888888!2sKigali!5e0!3m2!1sen!2srw!4v1700000000000" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
    </section>
     <footer class="reveal">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-about">
                    <a href="#" class="logo"><span class="vigo">VIGO</span> <span class="print">PRINT</span></a>
                    <p><?= htmlspecialchars($settings['footer_about']) ?></p>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-x-twitter"></i></a>
                    </div>
                </div>
                <div class="footer-links">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="#services">Our Services</a></li>
                        <li><a href="#about">About Us</a></li>
                        <li><a href="#portfolio">Sample Work</a></li>
                        <li><a href="admin/login.php" style="color: var(--accent-green);"><i class="fas fa-lock"></i> Admin Portal</a></li>
                    </ul>
                </div>
                <div class="footer-contact">
                    <h4>Contact & Visit</h4>
                    <div class="contact-item"><i class="fas fa-map-marker-alt"></i><div><strong>Head Office & Factory</strong><br><?= $settings['address'] ?></div></div>
                    <div class="contact-item"><i class="fas fa-phone-alt"></i><div><strong>Phone / WhatsApp</strong><br>+<?= htmlspecialchars($settings['whatsapp'] ?? '250788858358') ?></div></div>
                    <div class="contact-item"><i class="fas fa-clock"></i><div><strong>Production Hours</strong><br><?= htmlspecialchars($settings['hours']) ?></div></div>
                </div>
            </div>
            <div class="footer-bottom">&copy; <?php echo date("Y"); ?> VIGO PRINT. Designed for Industrial Excellence. All Rights Reserved.</div>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>