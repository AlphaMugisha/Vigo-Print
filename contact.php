<?php
// --- PHP FORM PROCESSING LOGIC ---
$statusMsg = '';
$statusClass = '';

if(isset($_POST['submit'])){
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $message = htmlspecialchars($_POST['message']);
    
    if(!empty($name) && !empty($email) && !empty($message)){
        $toEmail = 'hello@vigoprint.rw'; 
        $subject = 'New Print Project Request from ' . $name;
        
        $htmlContent = "<h2>New Quote Request</h2>
                        <p><strong>Name:</strong> {$name}</p>
                        <p><strong>Email:</strong> {$email}</p>
                        <p><strong>Phone:</strong> {$phone}</p>
                        <p><strong>Project Details:</strong><br/>{$message}</p>";
        
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8\r\n";
        $headers .= "From: {$name} <{$email}>\r\n";
        
        if(mail($toEmail, $subject, $htmlContent, $headers)){
            $statusMsg = 'Sent successfully! We will contact you shortly.';
            $statusClass = 'alert-success';
        } else {
            $statusMsg = 'Submission failed, please try again.';
            $statusClass = 'alert-error';
        }
    } else {
        $statusMsg = 'Please fill all mandatory fields.';
        $statusClass = 'alert-error';
    }
}
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
</head>
<body class="loading-lock">

    <div id="preloader">
        <div class="preloader-content">
            <div class="logo-float-wrapper">
                <div class="preloader-logo">
                    <div class="preloader-logo-inner">🖨️</div>
                </div>
            </div>
            <div class="preloader-progress-wrapper">
                <div class="preloader-progress-track">
                    <div class="preloader-progress-fill"></div>
                </div>
            </div>
            <div class="preloader-text">Loading Contact Portal...</div>
        </div>
    </div>

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
                <div class="alert <?php echo $statusClass; ?>"><?php echo $statusMsg; ?></div>
            <?php endif; ?>

            <form action="" method="POST" class="contact-form">
                <div class="form-group">
                    <label>Company / Contact Name *</label>
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
                <button type="submit" name="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">
                    <i class="fas fa-paper-plane"></i> Submit Quote Request
                </button>
            </form>
        </div>

        <div class="contact-info reveal reveal-right">
            <h3>Direct Contact</h3>
            <div class="info-item"><i class="fas fa-map-marker-alt"></i><div><strong>Head Office & Factory</strong><br>9 KN 59 Street, Nyarugenge, Kigali</div></div>
            <div class="info-item"><i class="fas fa-phone-alt"></i><div><strong>Phone / WhatsApp</strong><br>+250 788 858 358</div></div>
            
            <div class="map-container">
                <iframe src="https://www.google.com/maps/search/vigo+print/@-1.9666298,30.1011831,15z?entry=ttu&g_ep=EgoyMDI2MDMwMS4xIKXMDSoASAFQAw%3D%3D" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
    </section>

    <footer class="reveal">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-about">
                    <a href="index.php" class="logo"><span class="vigo">VIGO</span> <span class="print">PRINT</span></a>
                    <p>Rwanda's leading industrial printing facility, combining advanced European pre-press technology with high-volume offset printing capacity.</p>
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
                        <li><a href="index.php#services">Our Services</a></li>
                        <li><a href="index.php#about">Company Profile</a></li>
                        <li><a href="index.php#portfolio">Sample Work</a></li>
                        <li><a href="contact.php">Request Quote</a></li>
                    </ul>
                </div>
                
                <div class="footer-contact">
                    <h4>Contact & Visit</h4>
                    <div class="contact-item"><i class="fas fa-map-marker-alt"></i><div><strong>Factory Location</strong><br>9 KN 59 Street, Nyarugenge<br>Kigali, Rwanda</div></div>
                    <div class="contact-item"><i class="fas fa-phone-alt"></i><div><strong>Phone / WhatsApp</strong><br>+250 788 858 358</div></div>
                    <div class="contact-item"><i class="fas fa-clock"></i><div><strong>Hours</strong><br>Mon - Sat: 8:00 AM - 6:00 PM</div></div>
                </div>
            </div>
            
            <div class="footer-bottom">&copy; <?php echo date("Y"); ?> VIGO PRINT. Designed for Industrial Excellence. All Rights Reserved.</div>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>