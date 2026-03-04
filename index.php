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
            
            /* Authentic CMYK Print Colors */
            --cyan: rgba(0, 174, 239, 0.8);
            --magenta: rgba(236, 0, 140, 0.8);
            --yellow: rgba(255, 242, 0, 0.8);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; scroll-behavior: smooth; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg-light); color: var(--text-main); line-height: 1.6; overflow-x: hidden; }
        body.loading-lock { overflow: hidden; }

        /* --- CREATIVE CMYK REGISTRATION PRELOADER --- */
        #preloader {
            position: fixed; inset: 0; background: var(--primary-dark);
            z-index: 99999; display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            transition: opacity 0.5s ease, visibility 0.5s ease;
        }
        #preloader.fade-out { opacity: 0; visibility: hidden; }

        .offset-text {
            font-size: clamp(3rem, 8vw, 5rem);
            font-weight: 800;
            letter-spacing: 2px;
            margin-bottom: 40px;
        }
        
        .offset-text span {
            color: transparent; /* Hides true color until plates align */
            display: inline-block;
            animation: alignPlates 4.5s cubic-bezier(0.25, 1, 0.5, 1) forwards;
        }
        
        .offset-text .vigo { --final-color: var(--accent-green); }
        .offset-text .print { --final-color: var(--white); }

        /* This animation simulates the 4 offset plates locking into place */
        @keyframes alignPlates {
            0% {
                text-shadow: -15px -15px 0 var(--cyan), 15px -10px 0 var(--magenta), -10px 15px 0 var(--yellow);
                filter: blur(3px);
            }
            70% {
                text-shadow: -3px -3px 0 var(--cyan), 3px -2px 0 var(--magenta), -2px 3px 0 var(--yellow);
                filter: blur(0px);
                color: transparent;
            }
            95% {
                text-shadow: 0 0 0 transparent, 0 0 0 transparent, 0 0 0 transparent;
                color: var(--final-color);
            }
            100% {
                text-shadow: 0 0 20px rgba(141, 198, 63, 0.3);
                color: var(--final-color);
            }
        }

        /* Laser Etching Tracker */
        .progress-container { width: 300px; text-align: center; }
        .laser-track {
            width: 100%; height: 1px; background: rgba(255,255,255,0.1);
            position: relative; margin-bottom: 15px;
        }
        .laser-beam {
            position: absolute; top: -1px; left: 0; height: 3px; width: 0%;
            background: var(--accent-green);
            box-shadow: 0 0 10px var(--accent-green), 0 0 20px var(--accent-green);
        }
        .loading-status {
            display: flex; justify-content: space-between; font-size: 0.8rem;
            color: rgba(255,255,255,0.6); font-family: monospace; text-transform: uppercase; letter-spacing: 2px;
        }
        #load-pct { color: var(--accent-green); font-weight: bold; }

        /* --- UTILITY & ANIMATION CLASSES --- */
        .container { max-width: 1200px; margin: 0 auto; padding: 0 5%; }
        h1, h2, h3, h4 { color: var(--primary-dark); font-weight: 700; line-height: 1.2; }
        .section-padding { padding: 100px 0; }
        .text-center { text-align: center; }
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
        .hero-badge { display: inline-block; background: rgba(141, 198, 63, 0.2); color: var(--accent-green); padding: 8px 16px; border-radius: 50px; font-weight: 700; font-size: 0.85rem; letter-spacing: 1px; margin-bottom: 25px; border: 1px solid rgba(141, 198, 63, 0.4); }
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
        .service-card p { color: var(--text-light); font-size: 0.95rem; }

        /* --- ABOUT SECTION --- */
        .about { background: var(--bg-light); overflow: hidden; }
        .about-wrapper { display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: center; }
        .about-img { position: relative; }
        .about-img img { width: 100%; border-radius: var(--border-radius); box-shadow: 0 20px 40px rgba(0,0,0,0.1); transition: var(--transition); }
        .about-img:hover img { transform: scale(1.02); }
        .about-img::before { content: ''; position: absolute; top: -20px; left: -20px; width: 100%; height: 100%; border: 3px solid var(--accent-green); border-radius: var(--border-radius); z-index: -1; transition: var(--transition); }
        .about-img:hover::before { top: 0; left: 0; }
        .about-content h2 { font-size: 2.5rem; margin-bottom: 25px; }
        .about-content p { margin-bottom: 20px; color: var(--text-light); }
        .stats-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 30px; border-top: 1px solid #E2E8F0; padding-top: 30px; }
        .stat-item h4 { font-size: 2.5rem; color: var(--primary-dark); margin-bottom: 5px; }
        .stat-item h4 span { color: var(--accent-green); }
        .stat-item p { font-size: 0.9rem; font-weight: 600; text-transform: uppercase; color: var(--text-light); margin: 0; }

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

        /* --- CTA BANNER --- */
        .cta-section { background: var(--primary-dark); color: var(--white); text-align: center; position: relative; overflow: hidden; }
        .cta-section::before { content: ''; position: absolute; right: -10%; top: -50%; width: 400px; height: 400px; background: rgba(141, 198, 63, 0.1); border-radius: 50%; filter: blur(50px); animation: float 6s ease-in-out infinite alternate; }
        @keyframes float { 0% { transform: translateY(0); } 100% { transform: translateY(50px); } }
        .cta-content { position: relative; z-index: 2; max-width: 800px; margin: 0 auto; }
        .cta-content h2 { color: var(--white); font-size: 2.5rem; margin-bottom: 20px; }
        .cta-content p { color: rgba(255,255,255,0.7); font-size: 1.2rem; margin-bottom: 40px; }

        /* --- REVIEWS --- */
        .reviews { background: var(--bg-light); }
        .reviews-track { display: flex; gap: 30px; overflow-x: auto; padding-bottom: 30px; scroll-snap-type: x mandatory; scrollbar-width: none; cursor: grab; }
        .reviews-track::-webkit-scrollbar { display: none; }
        .review-card { flex: 0 0 350px; background: var(--white); padding: 40px; border-radius: var(--border-radius); scroll-snap-align: center; border: 1px solid #E2E8F0; transition: var(--transition); }
        .review-card:hover { transform: scale(1.02); box-shadow: 0 15px 30px rgba(0,0,0,0.05); border-color: var(--accent-green); }
        .stars { color: #F59E0B; font-size: 1.2rem; margin-bottom: 20px; }
        .review-text { font-style: italic; color: var(--text-main); margin-bottom: 25px; font-size: 1.05rem; }
        .reviewer { display: flex; align-items: center; gap: 15px; }
        .reviewer-avatar { width: 50px; height: 50px; background: var(--primary-light); color: var(--white); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; }
        .reviewer-info h5 { font-size: 1rem; color: var(--primary-dark); }
        .reviewer-info p { font-size: 0.85rem; color: var(--text-light); margin: 0; }

        /* --- FOOTER --- */
        footer { background: var(--primary-dark); color: var(--white); padding: 80px 0 30px; border-top: 5px solid var(--accent-green); }
        .footer-grid { display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 60px; margin-bottom: 60px; }
        .footer-about p { color: rgba(255,255,255,0.7); margin: 20px 0; max-width: 400px; }
        .social-icons { display: flex; gap: 15px; }
        .social-icons a { width: 40px; height: 40px; background: rgba(255,255,255,0.1); color: var(--white); border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: var(--transition); }
        .social-icons a:hover { background: var(--accent-green); color: var(--primary-dark); transform: translateY(-5px); }
        .footer-links h4, .footer-contact h4 { color: var(--white); margin-bottom: 25px; font-size: 1.2rem; position: relative; padding-bottom: 10px; }
        .footer-links h4::after, .footer-contact h4::after { content: ''; position: absolute; left: 0; bottom: 0; width: 30px; height: 2px; background: var(--accent-green); }
        .footer-links ul { list-style: none; }
        .footer-links li { margin-bottom: 15px; }
        .footer-links a { color: rgba(255,255,255,0.7); text-decoration: none; transition: var(--transition); }
        .footer-links a:hover { color: var(--accent-green); padding-left: 5px; }
        .contact-item { display: flex; align-items: flex-start; gap: 15px; margin-bottom: 20px; color: rgba(255,255,255,0.7); transition: var(--transition); }
        .contact-item:hover { transform: translateX(5px); color: var(--white); }
        .contact-item i { color: var(--accent-green); font-size: 1.2rem; margin-top: 5px; }
        .footer-bottom { text-align: center; padding-top: 30px; border-top: 1px solid rgba(255,255,255,0.1); color: rgba(255,255,255,0.5); font-size: 0.9rem; }

        @media (max-width: 992px) {
            .about-wrapper, .footer-grid { grid-template-columns: 1fr; }
            .about-img { order: -1; margin-bottom: 40px; }
        }
        @media (max-width: 768px) {
            .menu-toggle { display: block; }
            nav { position: fixed; top: 0; right: -100%; width: 80%; height: 100vh; background: var(--primary-dark); flex-direction: column; justify-content: center; transition: 0.4s ease-in-out; box-shadow: -10px 0 30px rgba(0,0,0,0.5); }
            nav.active { right: 0; }
            nav a { font-size: 1.2rem; margin: 15px 0; }
            .hero-buttons { flex-direction: column; }
            .btn { width: 100%; justify-content: center; }
            .section-header h2 { font-size: 2rem; }
        }
    </style>
</head>
<body class="loading-lock">

    <div id="preloader">
        <div class="offset-text">
            <span class="vigo">VIGO</span> <span class="print">PRINT</span>
        </div>

        <div class="progress-container">
            <div class="laser-track">
                <div class="laser-beam" id="laser-fill"></div>
            </div>
            <div class="loading-status">
                <span id="load-text">Aligning CMYK Plates...</span>
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
                <a href="#about">About Us</a>
                <a href="#portfolio">Portfolio</a>
                <a href="#reviews">Reviews</a>
                <a href="https://wa.me/250788858358" class="nav-btn">Get a Quote</a>
            </nav>
        </div>
    </header>

    <section class="hero">
        <img src="https://images.unsplash.com/photo-1621831718815-5e608dcb4ec7?q=80&w=2000&auto=format&fit=crop" alt="Industrial Printing Press" class="hero-img">
        <div class="hero-overlay"></div>
        <div class="hero-content reveal">
            <span class="hero-badge reveal delay-1">ISO Standard Print Facility</span>
            <h1 class="reveal delay-2">Precision Industrial Printing in Kigali</h1>
            <p class="reveal delay-3">Empowering Rwandan businesses with high-volume, commercial-grade print production. From cutting-edge Ecoographix CTP plates to flawless Heidelberg Offset output.</p>
            <div class="hero-buttons reveal delay-3">
                <a href="#services" class="btn btn-primary">Explore Services</a>
                <a href="https://wa.me/250788858358" class="btn btn-outline"><i class="fab fa-whatsapp"></i> Chat with an Expert</a>
            </div>
        </div>
    </section>

    <section id="services" class="services section-padding container">
        <div class="section-header reveal">
            <h2>Our Core Capabilities</h2>
            <p>Comprehensive end-to-end industrial printing solutions tailored for commercial enterprises, publishers, and agencies.</p>
        </div>
        <div class="service-grid">
            <div class="service-card reveal">
                <div class="service-icon"><i class="fas fa-layer-group"></i></div>
                <h3>Pre-Press & CTP</h3>
                <p>State-of-the-art Ecoographix Computer-to-Plate (CTP) systems ensuring absolute pinpoint accuracy, sharp dot generation, and perfect registration before the ink even hits the paper.</p>
            </div>
            <div class="service-card reveal delay-1">
                <div class="service-icon"><i class="fas fa-print"></i></div>
                <h3>Offset Printing</h3>
                <p>Powered by Heidelberg MO technology, we deliver unmatched color consistency and cost-efficiency for high-volume jobs like magazines, corporate profiles, and massive flyer runs.</p>
            </div>
            <div class="service-card reveal delay-2">
                <div class="service-icon"><i class="fas fa-box-open"></i></div>
                <h3>Packaging & Labels</h3>
                <p>Custom die-cut packaging boxes, precision product labels, and commercial wrapping solutions designed to make your merchandise stand out on the retail shelf.</p>
            </div>
            <div class="service-card reveal delay-3">
                <div class="service-icon"><i class="fas fa-book-open"></i></div>
                <h3>Post-Press Finishing</h3>
                <p>Professional binding (perfect, saddle-stitch, wire-O), die-cutting, matte/gloss lamination, and UV coating to give your printed materials a durable, premium tactile feel.</p>
            </div>
        </div>
    </section>

    <section id="about" class="about section-padding">
        <div class="container about-wrapper">
            <div class="about-img reveal reveal-left">
                <img src="https://images.unsplash.com/photo-1598301257982-0cf014dabbcd?q=80&w=1000&auto=format&fit=crop" alt="Print Quality Control">
            </div>
            <div class="about-content reveal reveal-right">
                <h2>Setting the Standard for Print Quality in Rwanda.</h2>
                <p>VIGO PRINT is more than just a print shop; we are an industrial-scale commercial printing partner. Operating out of the heart of Nyarugenge, Kigali, we have invested heavily in robust European printing machinery and advanced color-calibration software.</p>
                <p>Whether you need 10,000 corporate brochures by Friday or structural packaging for a new product launch, our facility is equipped to handle strict deadlines without ever compromising on standard CMYK fidelity.</p>
                
                <div class="stats-grid">
                    <div class="stat-item reveal delay-1"><h4>10<span>+</span></h4><p>Years Industry Exp.</p></div>
                    <div class="stat-item reveal delay-2"><h4>5,000<span>+</span></h4><p>Projects Delivered</p></div>
                    <div class="stat-item reveal delay-3"><h4>24<span>/7</span></h4><p>Production Capacity</p></div>
                    <div class="stat-item reveal delay-1"><h4>100<span>%</span></h4><p>Color Accuracy</p></div>
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
            <div class="portfolio-item reveal">
                <img src="https://images.unsplash.com/photo-1563986768609-322da13575f3?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Corporate Reports">
                <div class="portfolio-overlay"><h4>Corporate Annual Reports</h4><p>Perfect Bound & Spot UV</p></div>
            </div>
            <div class="portfolio-item reveal delay-1">
                <img src="https://images.unsplash.com/photo-1589939705384-5185137a7f0f?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Retail Packaging">
                <div class="portfolio-overlay"><h4>Retail Packaging Boxes</h4><p>Die-Cut & Matte Laminated</p></div>
            </div>
            <div class="portfolio-item reveal delay-2">
                <img src="https://images.unsplash.com/photo-1512820790803-83ca734da794?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Magazines">
                <div class="portfolio-overlay"><h4>Commercial Magazines</h4><p>High-Volume Offset Printing</p></div>
            </div>
            
            <div class="portfolio-item hidden-work extra-items">
                <img src="https://images.unsplash.com/photo-1505322022379-7c3353ee6291?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Product Labels">
                <div class="portfolio-overlay"><h4>Product Labels</h4><p>Adhesive & Water Resistant</p></div>
            </div>
            <div class="portfolio-item hidden-work extra-items">
                <img src="https://images.unsplash.com/photo-1581822261290-991b38693d1b?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Marketing Brochures">
                <div class="portfolio-overlay"><h4>Marketing Brochures</h4><p>Tri-Fold Gloss Finish</p></div>
            </div>
            <div class="portfolio-item hidden-work extra-items">
                <img src="https://images.unsplash.com/photo-1601055903647-8f55781a5332?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="CTP Plates">
                <div class="portfolio-overlay"><h4>Ecoographix Plates</h4><p>B2B Pre-Press Services</p></div>
            </div>
        </div>
        <div class="text-center reveal" style="margin-top: 50px;">
            <button class="btn btn-primary" id="loadMoreBtn" onclick="togglePortfolio()">View More Projects</button>
        </div>
    </section>

    <section class="cta-section section-padding reveal">
        <div class="container cta-content">
            <h2>Have a High-Volume Print Project?</h2>
            <p>Send us your artwork files today. Our pre-press team will review your requirements and provide a competitive quote within 24 hours.</p>
            <a href="https://wa.me/250788858358" class="btn btn-primary"><i class="fas fa-file-invoice"></i> Request a Custom Quote</a>
        </div>
    </section>

    <section id="reviews" class="reviews section-padding container">
        <div class="section-header reveal">
            <h2>Client Testimonials</h2>
            <p>Trusted by Kigali's top brands, agencies, and institutions.</p>
        </div>
        <div class="reviews-track reveal delay-1">
            <div class="review-card">
                <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                <p class="review-text">"Vigo Print handles all our FMCG packaging labels. Their Heidelberg press outputs colors that perfectly match our international brand guidelines. Highly recommended."</p>
                <div class="reviewer"><div class="reviewer-avatar">JA</div><div class="reviewer-info"><h5>Jean-Luc A.</h5><p>Procurement, Juice Co. Rwanda</p></div></div>
            </div>
            <div class="review-card">
                <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                <p class="review-text">"We use their CTP services daily for our agency. The Ecoographix plates they produce are incredibly precise and never fail on our own presses."</p>
                <div class="reviewer"><div class="reviewer-avatar">MK</div><div class="reviewer-info"><h5>Moses K.</h5><p>Production Manager</p></div></div>
            </div>
            <div class="review-card">
                <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                <p class="review-text">"Delivered 10,000 perfect-bound annual reports in just 4 days. The lamination and paper quality was premium, and the pricing was the best in Kigali."</p>
                <div class="reviewer"><div class="reviewer-avatar">DN</div><div class="reviewer-info"><h5>Diane N.</h5><p>Corporate Communications</p></div></div>
            </div>
        </div>
    </section>

    <footer class="reveal">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-about">
                    <a href="#" class="logo"><span class="vigo">VIGO</span> <span class="print">PRINT</span></a>
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
                        <li><a href="#services">Our Services</a></li>
                        <li><a href="#about">Company Profile</a></li>
                        <li><a href="#portfolio">Sample Work</a></li>
                        <li><a href="#">Upload Artwork File</a></li>
                    </ul>
                </div>
                <div class="footer-contact">
                    <h4>Contact & Visit</h4>
                    <div class="contact-item"><i class="fas fa-map-marker-alt"></i><div><strong>Head Office & Factory</strong><br>9 KN 59 Street, Nyarugenge<br>Kigali, Rwanda</div></div>
                    <div class="contact-item"><i class="fas fa-phone-alt"></i><div><strong>Phone / WhatsApp</strong><br>+250 788 858 358</div></div>
                    <div class="contact-item"><i class="fas fa-clock"></i><div><strong>Production Hours</strong><br>Mon - Sat: 8:00 AM - 6:00 PM</div></div>
                </div>
            </div>
            <div class="footer-bottom">&copy; <?php echo date("Y"); ?> VIGO PRINT. Designed for Industrial Excellence. All Rights Reserved.</div>
        </div>
    </footer>

    <script>
        // --- 5-SECOND CMYK REGISTRATION PRELOADER ---
        document.addEventListener("DOMContentLoaded", () => {
            const preloader = document.getElementById('preloader');
            const laserFill = document.getElementById('laser-fill');
            const loadText = document.getElementById('load-text');
            const loadPct = document.getElementById('load-pct');

            let progress = 0;
            const totalTime = 5000; // Strictly 5 seconds
            const intervalTime = 50; 
            const increment = (intervalTime / totalTime) * 100;

            const loadInterval = setInterval(() => {
                progress += increment;
                
                if (progress >= 100) progress = 100;
                
                laserFill.style.width = `${progress}%`;
                loadPct.innerText = `${Math.floor(progress)}%`;

                if (progress > 30 && progress < 60) {
                    loadText.innerText = "Applying Magenta & Yellow...";
                } else if (progress >= 60 && progress < 85) {
                    loadText.innerText = "Locking Key (Black) Plate...";
                } else if (progress >= 85 && progress < 100) {
                    loadText.innerText = "Checking Registration Fidelity...";
                } else if (progress === 100) {
                    loadText.innerText = "Print Run Ready.";
                }

                if (progress === 100) {
                    clearInterval(loadInterval);
                    setTimeout(() => {
                        preloader.classList.add('fade-out');
                        document.body.classList.remove('loading-lock');
                    }, 500); 
                }
            }, intervalTime);
        });

        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            const header = document.getElementById('navbar');
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });

        // Scroll Reveal Animations
        const revealElements = document.querySelectorAll('.reveal');
        const revealObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                    observer.unobserve(entry.target); 
                }
            });
        }, { threshold: 0.1, rootMargin: "0px 0px -50px 0px" });
        revealElements.forEach(el => revealObserver.observe(el));

        // Mobile Menu
        const toggle = document.getElementById('mobile-toggle');
        const menu = document.getElementById('nav-menu');
        const icon = toggle.querySelector('i');

        toggle.onclick = () => {
            menu.classList.toggle('active');
            if (menu.classList.contains('active')) {
                icon.classList.replace('fa-bars', 'fa-times');
            } else {
                icon.classList.replace('fa-times', 'fa-bars');
            }
        };

        document.querySelectorAll('nav a').forEach(link => {
            link.onclick = () => {
                menu.classList.remove('active');
                icon.classList.replace('fa-times', 'fa-bars');
            }
        });

        // Toggle Portfolio
        function togglePortfolio() {
            const hiddenItems = document.querySelectorAll('.extra-items');
            const btn = document.getElementById('loadMoreBtn');
            hiddenItems.forEach(item => {
                if(item.style.display === 'block') {
                    item.style.display = 'none';
                    btn.innerText = 'View More Projects';
                } else {
                    item.style.display = 'block';
                    item.classList.add('active'); 
                    btn.innerText = 'Show Less';
                }
            });
        }
    </script>
</body>
</html>