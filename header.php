<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VIGO PRINT | Premium Industrial Printing in Kigali</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="style.css">
    
    <style>
        .hidden-work { display: none !important; }
    </style>
</head>
<body class="loading-lock">

    <?php 
    // === INJECT THE PRELOADER ===
    include 'preloader.php'; 
    ?>

    <header id="navbar">
        <div class="nav-container">
            <a href="index.php" class="logo"><span class="vigo">VIGO</span> <span class="print">PRINT</span></a>
            
            <div class="menu-toggle" id="mobile-toggle"><i class="fas fa-bars"></i></div>
            <nav id="nav-menu">
                <a href="index.php">Home</a>
                <a href="index.php#services">Services</a>
                <a href="index.php#about">About</a>
                <a href="index.php#portfolio">Portfolio</a>
                <a href="contact.php">Contact Us</a>
                <a href="https://wa.me/<?= htmlspecialchars($settings['whatsapp'] ?? '250788858358') ?>" class="nav-btn">Get a Quote</a>
            </nav>
        </div>
    </header>