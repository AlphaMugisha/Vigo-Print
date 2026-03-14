<?php
// includes/db.php must exist, or the page will crash. 
@include_once 'includes/db.php';

// Initialize empty arrays/variables to prevent errors before the database is connected
$settings = [
    'whatsapp' => '250788858358',
    'hero_video' => 'video.mp4',
    'hero_badge' => 'ISO Standard Print Facility',
    'hero_title' => 'Precision Industrial Printing in Kigali',
    'hero_desc' => 'Empowering Rwandan businesses with high-volume, commercial-grade print production.',
    'about_img' => 'https://images.unsplash.com/photo-1598301257982-0cf014dabbcd?q=80&w=1000&auto=format&fit=crop',
    'about_title' => 'Setting the Standard for Print Quality in Rwanda.',
    'about_desc_1' => 'VIGO PRINT is more than just a print shop; we are an industrial-scale commercial printing partner.',
    'about_desc_2' => 'Whether you need 10,000 corporate brochures by Friday or structural packaging for a new product launch, our facility is equipped to handle strict deadlines without ever compromising on standard CMYK fidelity.',
    'cta_title' => 'Have a High-Volume Print Project?',
    'cta_desc' => 'Send us your artwork files today. Our pre-press team will review your requirements and provide a competitive quote within 24 hours.',
    'footer_about' => "Rwanda's leading industrial printing facility, combining advanced European pre-press technology with high-volume offset printing capacity.",
    'address' => '9 KN 59 Street, Nyarugenge<br>Kigali, Rwanda',
    'hours' => 'Mon - Sat: 8:00 AM - 6:00 PM'
];
$services = [];
$stats = [];
$portfolio = [];
$testimonials = [];

// Once your DB is connected, these queries will pull the live data
if (isset($pdo)) {
    $stmt = $pdo->query("SELECT * FROM site_settings WHERE id = 1");
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { $settings = $row; }
    $services = $pdo->query("SELECT * FROM services ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
    $stats = $pdo->query("SELECT * FROM stats ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
    $portfolio = $pdo->query("SELECT * FROM portfolio ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
    $testimonials = $pdo->query("SELECT * FROM testimonials ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
}

// === BRING IN THE HEADER ===
include 'header.php';
?>

    <section class="hero">
        <video autoplay loop muted playsinline class="hero-video">
            <source src="<?= htmlspecialchars($settings['hero_video'] ?? 'video.mp4') ?>" type="video/mp4">
        </video>
        
        <div class="hero-overlay"></div>
        <div class="hero-content reveal">
            <span class="hero-badge reveal delay-1"><?= htmlspecialchars($settings['hero_badge']) ?></span>
            <h1 class="reveal delay-2"><?= htmlspecialchars($settings['hero_title']) ?></h1>
            <p class="reveal delay-3"><?= htmlspecialchars($settings['hero_desc']) ?></p>
            <div class="hero-buttons reveal delay-3">
                <a href="#services" class="btn btn-primary">Explore Services</a>
                <a href="https://wa.me/<?= htmlspecialchars($settings['whatsapp'] ?? '250788858358') ?>" class="btn btn-outline"><i class="fab fa-whatsapp"></i> Chat with an Expert</a>
            </div>
        </div>
    </section>

    <section id="services" class="services section-padding container">
        <div class="section-header reveal">
            <h2>Our Core Capabilities</h2>
            <p>Comprehensive end-to-end industrial printing solutions tailored for commercial enterprises, publishers, and agencies.</p>
        </div>
        <div class="service-grid">
            <?php if (!empty($services)): ?>
                <?php 
                $delay = 0;
                foreach ($services as $service): 
                    $delayClass = $delay > 0 ? "delay-" . $delay : "";
                ?>
                <div class="service-card reveal <?= $delayClass ?>">
                    <div class="service-icon"><i class="<?= htmlspecialchars($service['icon']) ?>"></i></div>
                    <h3><?= htmlspecialchars($service['title']) ?></h3>
                    <p><?= htmlspecialchars($service['description']) ?></p>
                </div>
                <?php 
                    $delay++;
                endforeach; 
                ?>
            <?php else: ?>
                <div class="service-card reveal"><div class="service-icon"><i class="fas fa-layer-group"></i></div><h3>Pre-Press & CTP</h3><p>State-of-the-art Ecoographix...</p></div>
            <?php endif; ?>
        </div>
    </section>

    <section id="about" class="about section-padding">
        <div class="container about-wrapper">
            <div class="about-img reveal reveal-left">
                <img src="<?= htmlspecialchars($settings['about_img']) ?>" alt="Print Quality Control">
            </div>
            <div class="about-content reveal reveal-right">
                <h2><?= htmlspecialchars($settings['about_title']) ?></h2>
                <p><?= htmlspecialchars($settings['about_desc_1']) ?></p>
                <p><?= htmlspecialchars($settings['about_desc_2']) ?></p>
                
                <div class="stats-grid">
                    <?php if (!empty($stats)): ?>
                        <?php 
                        $delay = 1;
                        foreach ($stats as $stat): 
                            $delayClass = "delay-" . $delay;
                        ?>
                        <div class="stat-item reveal <?= $delayClass ?>">
                            <h4><?= htmlspecialchars($stat['number']) ?><span><?= htmlspecialchars($stat['symbol']) ?></span></h4>
                            <p><?= htmlspecialchars($stat['label']) ?></p>
                        </div>
                        <?php 
                            $delay = $delay >= 3 ? 1 : $delay + 1;
                        endforeach; 
                        ?>
                    <?php else: ?>
                        <div class="stat-item reveal delay-1"><h4>10<span>+</span></h4><p>Years Industry Exp.</p></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <section id="portfolio" class="portfolio section-padding container">
        <div class="section-header reveal">
            <h2>Industrial Portfolio</h2>
            <p>A glimpse into the commercial products we manufacture daily.</p>
        </div>
        <div class="portfolio-grid">
            <?php if (!empty($portfolio)): ?>
                <?php 
                $index = 0;
                foreach ($portfolio as $item): 
                    $delay = $index % 3;
                    $delayClass = $delay > 0 ? "delay-" . $delay : "";
                    $hiddenClass = $index >= 3 ? "hidden-work extra-items" : "extra-items-visible"; 
                ?>
                <div class="portfolio-item <?= $hiddenClass ?> reveal <?= $delayClass ?>">
                    <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['title']) ?>">
                    <div class="portfolio-overlay">
                        <h4><?= htmlspecialchars($item['title']) ?></h4>
                        <p><?= htmlspecialchars($item['subtitle']) ?></p>
                    </div>
                </div>
                <?php 
                    $index++;
                endforeach; 
                ?>
            <?php else: ?>
                 <div class="portfolio-item reveal"><img src="https://images.unsplash.com/photo-1563986768609-322da13575f3?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Fallback"><div class="portfolio-overlay"><h4>Connect DB to see portfolio</h4><p>Setup needed</p></div></div>
            <?php endif; ?>
        </div>
        
        <?php if (!empty($portfolio) && count($portfolio) > 3): ?>
        <div class="text-center reveal" style="margin-top: 50px;">
            <button class="btn btn-primary" id="loadMoreBtn" onclick="togglePortfolio()">View More Projects</button>
        </div>
        <?php endif; ?>
    </section>

    <section class="cta-section section-padding reveal">
        <div class="container cta-content">
            <h2><?= htmlspecialchars($settings['cta_title']) ?></h2>
            <p><?= htmlspecialchars($settings['cta_desc']) ?></p>
            <a href="contact.php" class="btn btn-primary"><i class="fas fa-file-invoice"></i> Request a Custom Quote</a>
        </div>
    </section>

    <section id="reviews" class="reviews section-padding container">
        <div class="section-header reveal">
            <h2>Client Testimonials</h2>
            <p>Trusted by Kigali's top brands, agencies, and institutions.</p>
        </div>
        <div class="reviews-track reveal delay-1">
            <?php if (!empty($testimonials)): ?>
                <?php foreach ($testimonials as $review): ?>
                <div class="review-card">
                    <div class="stars">
                        <?php for($i=0; $i<$review['stars']; $i++) echo '<i class="fas fa-star"></i>'; ?>
                    </div>
                    <p class="review-text">"<?= htmlspecialchars($review['text']) ?>"</p>
                    <div class="reviewer">
                        <div class="reviewer-avatar"><?= htmlspecialchars($review['avatar_initials']) ?></div>
                        <div class="reviewer-info">
                            <h5><?= htmlspecialchars($review['name']) ?></h5>
                            <p><?= htmlspecialchars($review['position']) ?></p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                 <div class="review-card"><p class="review-text">"Connect DB to see reviews."</p></div>
            <?php endif; ?>
        </div>
    </section>

<?php 
// === BRING IN THE FOOTER ===
include 'footer.php'; 
?>