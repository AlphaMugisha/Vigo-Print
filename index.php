<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VIGO PRINT | Premium Industrial Printing in Kigali</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-dark: #0A192F;
            --primary-light: #172A45;
            --accent-green: #8DC63F;
            --accent-hover: #7AB030;
            --text-main: #334155;
            --text-light: #64748B;
            --bg-light: #F8FAFC;
            --white: #FFFFFF;
            --border-radius: 8px;
            --transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; scroll-behavior: smooth; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg-light); color: var(--text-main); line-height: 1.6; overflow-x: hidden; }
        body.loading-lock { overflow: hidden; }

        /* --- MINIMALIST CIRCULAR PRELOADER --- */
        #preloader {
            position: fixed; inset: 0; background: var(--primary-dark);
            z-index: 99999; display: flex; align-items: center; justify-content: center;
            transition: opacity 0.6s ease, visibility 0.6s ease;
        }
        #preloader.fade-out { opacity: 0; visibility: hidden; }

        .loader-content {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 300px;
            height: 300px;
        }

        /* SVG Circular Progress Bar */
        .loader-svg {
            position: absolute;
            transform: rotate(-90deg); /* Start at the top */
        }
        .loader-svg circle {
            fill: none;
            stroke-width: 4;
            stroke-linecap: round;
        }
        .loader-svg .bg-circle {
            stroke: rgba(255, 255, 255, 0.05);
        }
        .loader-svg .progress-circle {
            stroke: var(--accent-green);
            stroke-dasharray: 628; /* Circumference (2 * pi * 100) */
            stroke-dashoffset: 628; /* Hidden at start */
            filter: drop-shadow(0 0 8px var(--accent-green));
            transition: stroke-dashoffset 0.1s linear;
        }

        .loader-text-box {
            text-align: center;
            z-index: 2;
        }
        .loader-logo {
            font-size: 1.8rem;
            font-weight: 800;
            letter-spacing: 2px;
            margin-bottom: 5px;
            color: var(--white);
        }
        .loader-logo span { color: var(--accent-green); }
        
        #load-pct {
            display: block;
            font-size: 1.2rem;
            font-weight: 300;
            color: rgba(255,255,255,0.6);
            font-family: monospace;
        }

        /* --- UTILITY & ANIMATION CLASSES --- */
        .container { max-width: 1200px; margin: 0 auto; padding: 0 5%; }
        h1, h2, h3, h4 { color: var(--primary-dark); font-weight: 700; line-height: 1.2; }
        .section-padding { padding: 100px 0; }
        .section-header { margin-bottom: 60px; text-align: center; }
        .section-header h2 { font-size: 2.5rem; margin-bottom: 15px; position: relative; display: inline-block; }
        .section-header h2::after { content: ''; position: absolute; width: 60px; height: 4px; background: var(--accent-green); bottom: -10px; left: 50%; transform: translateX(-50%); }
        .section-header p { color: var(--text-light); font-size: 1.1rem; max-width: 600px; margin: 20px auto 0; }

        .reveal { opacity: 0; transform: translateY(40px); transition: 0.8s cubic-bezier(0.5, 0, 0, 1); }
        .reveal.active { opacity: 1; transform: translateY(0); }
        .delay-1 { transition-delay: 0.1s; }
        .delay-2 { transition-delay: 0.2s; }
        .delay-3 { transition-delay: 0.3s; }
        .reveal-left { transform: translateX(-50px); }
        .reveal-right { transform: translateX(50px); }
        .reveal-left.active, .reveal-right.active { transform: translateX(0); }

        @keyframes pulseBtn { 0% { box-shadow: 0 0 0 0 rgba(141, 198, 63, 0.4); } 70% { box-shadow: 0 0 0 15px rgba(141, 198, 63, 0); } 100% { box-shadow: 0 0 0 0 rgba(141, 198, 63, 0); } }
        
        .btn { display: inline-flex; align-items: center; gap: 10px; padding: 16px 32px; font-weight: 700; border-radius: 50px; text-decoration: none; border: none; cursor: pointer; transition: var(--transition); font-size: 1rem; }
        .btn-primary { background: var(--accent-green); color: var(--primary-dark); animation: pulseBtn 2s infinite; }
        .btn-primary:hover { background: var(--accent-hover); transform: translateY(-5px); animation: none; box-shadow: 0 10px 20px rgba(141, 198, 63, 0.3); }
        .btn-outline { background: transparent; color: var(--white); border: 2px solid var(--white); }
        .btn-outline:hover { background: var(--white); color: var(--primary-dark); transform: translateY(-5px); }

        /* --- DYNAMIC NAVIGATION --- */
        header { padding: 20px 0; position: fixed; width: 100%; top: 0; z-index: 1000; transition: var(--transition); background: transparent; }
        header.scrolled { background: rgba(10, 25, 47, 0.98); backdrop-filter: blur(10px); padding: 10px 0; box-shadow: 0 4px 20px rgba(0,0,0,0.2); border-bottom: 1px solid rgba(255,255,255,0.05); }
        .nav-container { display: flex; justify-content: space-between; align-items: center; max-width: 1200px; margin: 0 auto; padding: 0 5%; }
        .logo { font-size: 1.6rem; text-decoration: none; font-weight: 800; letter-spacing: 1px; transition: var(--transition); }
        header.scrolled .logo { font-size: 1.4rem; }
        .logo .vigo { color: var(--accent-green); }
        .logo .print { color: var(--white); }

        nav { display: flex; align-items: center; gap: 30px; }
        nav a { color: var(--white); text-decoration: none; font-weight: 600; font-size: 0.95rem; position: relative; }
        nav a::after { content: ''; position: absolute; bottom: -5px; left: 0; width: 0; height: 2px; background: var(--accent-green); transition: var(--transition); }
        nav a:hover::after { width: 100%; }
        .nav-btn { background: var(--accent-green); color: var(--primary-dark); padding: 10px 24px; border-radius: 50px; }
        .nav-btn::after { display: none; }
        .nav-btn:hover { background: var(--white); color: var(--primary-dark); transform: scale(1.05); }
        .menu-toggle { display: none; color: var(--white); font-size: 1.5rem; cursor: pointer; z-index: 1001; }

        /* --- HERO SECTION --- */
        .hero { position: relative; height: 100vh; min-height: 600px; display: flex; align-items: center; text-align: center; background: #000; overflow: hidden; }
        .hero-img { position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; opacity: 0.4; transform: scale(1.05); animation: slowZoom 20s infinite alternate; }
        @keyframes slowZoom { from { transform: scale(1); } to { transform: scale(1.1); } }
        .hero-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, rgba(10, 25, 47, 0.9) 0%, rgba(10, 25, 47, 0.6) 100%); }
        .hero-content { position: relative; z-index: 3; width: 100%; max-width: 900px; margin: 0 auto; padding: 0 5%; }
        .hero h1 { color: var(--white); font-size: clamp(2.5rem, 6vw, 4.5rem); margin-bottom: 25px; letter-spacing: -1px; }
        .hero p { color: rgba(255,255,255,0.8); font-size: clamp(1.1rem, 2vw, 1.3rem); margin-bottom: 40px; max-width: 700px; margin-inline: auto; }
        .hero-buttons { display: flex; gap: 20px; justify-content: center; flex-wrap: wrap; }

        /* --- SERVICES SECTION --- */
        .services { background: var(--white); }
        .service-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px; }
        .service-card { background: var(--bg-light); padding: 40px 30px; border-radius: var(--border-radius); border: 1px solid #E2E8F0; transition: var(--transition); }
        .service-card:hover { transform: translateY(-15px); box-shadow: 0 20px 40px rgba(0,0,0,0.08); border-color: var(--accent-green); background: var(--white); }
        .service-icon { width: 60px; height: 60px; background: rgba(141, 198, 63, 0.1); color: var(--accent-green); display: flex; align-items: center; justify-content: center; font-size: 1.8rem; border-radius: 12px; margin-bottom: 25px; transition: var(--transition); }
        .service-card:hover .service-icon { background: var(--accent-green); color: var(--white); transform: rotateY(180deg); }
        .service-card h3 { font-size: 1.3rem; margin-bottom: 15px; }

        /* --- PORTFOLIO SECTION --- */
        .portfolio { background: var(--white); }
        .portfolio-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 25px; }
        .portfolio-item { position: relative; border-radius: var(--border-radius); overflow: hidden; height: 350px; cursor: pointer; }
        .portfolio-item img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.8s cubic-bezier(0.16, 1, 0.3, 1); }
        .portfolio-item:hover img { transform: scale(1.15); }
        .portfolio-overlay { position: absolute; inset: 0; background: linear-gradient(to top, rgba(10, 25, 47, 0.9) 0%, rgba(10, 25, 47, 0.1) 100%); display: flex; flex-direction: column; justify-content: flex-end; padding: 30px; opacity: 0; transition: var(--transition); }
        .portfolio-item:hover .portfolio-overlay { opacity: 1; }
        .portfolio-overlay h4 { color: var(--white); font-size: 1.4rem; margin-bottom: 5px; transform: translateY(20px); transition: var(--transition); }
        .portfolio-overlay p { color: var(--accent-green); font-weight: 600; font-size: 0.9rem; transform: translateY(20px); transition: var(--transition); transition-delay: 0.1s; }
        .portfolio-item:hover .portfolio-overlay h4, .portfolio-item:hover .portfolio-overlay p { transform: translateY(0); }
        .hidden-work { display: none; }

        /* --- FOOTER --- */
        footer { background: var(--primary-dark); color: var(--white); padding: 80px 0 30px; border-top: 5px solid var(--accent-green); }
        .footer-grid { display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 60px; margin-bottom: 60px; }
        .footer-bottom { text-align: center; padding-top: 30px; border-top: 1px solid rgba(255,255,255,0.1); color: rgba(255,255,255,0.5); font-size: 0.9rem; }

        @media (max-width: 768px) {
            .menu-toggle { display: block; }
            nav { position: fixed; top: 0; right: -100%; width: 80%; height: 100vh; background: var(--primary-dark); flex-direction: column; justify-content: center; transition: 0.4s ease-in-out; box-shadow: -10px 0 30px rgba(0,0,0,0.5); }
            nav.active { right: 0; }
            nav a { font-size: 1.2rem; margin: 15px 0; }
        }
    </style>
</head>
<body class="loading-lock">

    <div id="preloader">
        <div class="loader-content">
            <svg class="loader-svg" width="220" height="220">
                <circle class="bg-circle" cx="110" cy="110" r="100"></circle>
                <circle class="progress-circle" id="circle-progress" cx="110" cy="110" r="100"></circle>
            </svg>
            
            <div class="loader-text-box">
                <div class="loader-logo"><span>VIGO</span> PRINT</div>
                <span id="load-pct">0%</span>
            </div>
        </div>
    </div>

    <header id="navbar">
        <div class="nav-container">
            <a href="#" class="logo"><span class="vigo">VIGO</span> <span class="print">PRINT</span></a>
            <div class="menu-toggle" id="mobile-toggle"><i class="fas fa-bars"></i></div>
            <nav id="nav-menu">
                <a href="#services">Services</a>
                <a href="#about">About</a>
                <a href="#portfolio">Portfolio</a>
                <a href="https://wa.me/250788858358" class="nav-btn">Get a Quote</a>
            </nav>
        </div>
    </header>

    <section class="hero">
        <img src="https://images.unsplash.com/photo-1621831718815-5e608dcb4ec7?q=80&w=2000&auto=format&fit=crop" alt="Industrial Printing Press" class="hero-img">
        <div class="hero-overlay"></div>
        <div class="hero-content reveal">
            <h1>Precision Industrial Printing in Kigali</h1>
            <p>Empowering Rwandan businesses with high-volume, commercial-grade print production. From cutting-edge Ecoographix CTP plates to flawless Heidelberg Offset output.</p>
            <div class="hero-buttons">
                <a href="#services" class="btn btn-primary">Explore Services</a>
                <a href="https://wa.me/250788858358" class="btn btn-outline"><i class="fab fa-whatsapp"></i> WhatsApp Us</a>
            </div>
        </div>
    </section>

    <section id="services" class="services section-padding container">
        <div class="section-header reveal">
            <h2>Our Core Capabilities</h2>
            <p>Comprehensive end-to-end industrial printing solutions tailored for commercial enterprises.</p>
        </div>
        <div class="service-grid">
            <div class="service-card reveal"><div class="service-icon"><i class="fas fa-layer-group"></i></div><h3>Pre-Press & CTP</h3><p>State-of-the-art Ecoographix Computer-to-Plate systems ensuring absolute pinpoint accuracy.</p></div>
            <div class="service-card reveal delay-1"><div class="service-icon"><i class="fas fa-print"></i></div><h3>Offset Printing</h3><p>Powered by Heidelberg MO technology, we deliver unmatched color consistency for high-volume jobs.</p></div>
            <div class="service-card reveal delay-2"><div class="service-icon"><i class="fas fa-box-open"></i></div><h3>Packaging & Labels</h3><p>Custom die-cut packaging boxes and precision labels designed to make your merchandise stand out.</p></div>
        </div>
    </section>

    <section id="portfolio" class="portfolio section-padding container">
        <div class="section-header reveal"><h2>Industrial Portfolio</h2><p>Commercial products we manufacture daily.</p></div>
        <div class="portfolio-grid">
            <div class="portfolio-item reveal"><img src="https://images.unsplash.com/photo-1563986768609-322da13575f3?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"><div class="portfolio-overlay"><h4>Annual Reports</h4><p>Spot UV Finish</p></div></div>
            <div class="portfolio-item reveal delay-1"><img src="https://images.unsplash.com/photo-1589939705384-5185137a7f0f?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"><div class="portfolio-overlay"><h4>Retail Boxes</h4><p>Laminated Board</p></div></div>
            <div class="portfolio-item reveal delay-2"><img src="https://images.unsplash.com/photo-1512820790803-83ca734da794?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"><div class="portfolio-overlay"><h4>Magazines</h4><p>High-Volume Runs</p></div></div>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="footer-bottom">&copy; <?php echo date("Y"); ?> VIGO PRINT. Designed for Excellence.</div>
        </div>
    </footer>

    <script>
        // --- 5-SECOND CIRCULAR PRELOADER LOGIC ---
        document.addEventListener("DOMContentLoaded", () => {
            const preloader = document.getElementById('preloader');
            const progressCircle = document.getElementById('circle-progress');
            const loadPct = document.getElementById('load-pct');

            let progress = 0;
            const totalTime = 5000; // 5 Seconds
            const intervalTime = 50; 
            const increment = (intervalTime / totalTime) * 100;
            const circumference = 628; // 2 * pi * radius(100)

            const loadInterval = setInterval(() => {
                progress += increment;
                if (progress >= 100) progress = 100;

                // Update Percentage Text
                loadPct.innerText = `${Math.floor(progress)}%`;

                // Update SVG Ring
                // Dashoffset goes from 628 (empty) to 0 (full)
                const offset = circumference - (progress / 100) * circumference;
                progressCircle.style.strokeDashoffset = offset;

                if (progress === 100) {
                    clearInterval(loadInterval);
                    setTimeout(() => {
                        preloader.classList.add('fade-out');
                        document.body.classList.remove('loading-lock');
                    }, 500); 
                }
            }, intervalTime);
        });

        // Other Page Functions
        window.addEventListener('scroll', () => {
            const header = document.getElementById('navbar');
            if (window.scrollY > 50) header.classList.add('scrolled');
            else header.classList.remove('scrolled');
        });

        const revealObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                    observer.unobserve(entry.target); 
                }
            });
        }, { threshold: 0.1 });
        document.querySelectorAll('.reveal').forEach(el => revealObserver.observe(el));

        document.getElementById('mobile-toggle').onclick = function() {
            document.getElementById('nav-menu').classList.toggle('active');
            this.querySelector('i').classList.toggle('fa-bars');
            this.querySelector('i').classList.toggle('fa-times');
        };
    </script>
</body>
</html>