<style>
/* ========================================= */
/* THE "SCANNER" PRELOADER (FORCED STYLES)   */
/* ========================================= */
body.loading-lock { overflow: hidden !important; height: 100vh !important; }

#vigo-preloader {
    position: fixed !important; top: 0 !important; left: 0 !important; 
    width: 100% !important; height: 100vh !important;
    z-index: 999999 !important; display: flex !important; 
    align-items: center !important; justify-content: center !important;
    background: transparent !important;
    pointer-events: all;
}

/* The dark panels that slide apart */
.vigo-panel-top, .vigo-panel-bottom {
    position: absolute; left: 0; width: 100%; height: 50vh;
    background: #0A192F; /* Hardcoded navy blue */
    z-index: 1;
    transition: transform 0.8s cubic-bezier(0.77, 0, 0.175, 1);
}
.vigo-panel-top { top: 0; border-bottom: 2px solid #8DC63F; }
.vigo-panel-bottom { bottom: 0; }

/* The Split Animation */
body.loaded .vigo-panel-top { transform: translateY(-100%); }
body.loaded .vigo-panel-bottom { transform: translateY(100%); }
body.loaded .vigo-loader-content { opacity: 0; visibility: hidden; }
body.loaded #vigo-preloader { pointer-events: none; transition-delay: 0.8s; }

/* Content wrapper */
.vigo-loader-content {
    position: relative; z-index: 2; display: flex; flex-direction: column; 
    align-items: center; gap: 20px; transition: opacity 0.3s;
}

.vigo-logo-wrapper { position: relative; overflow: hidden; padding: 10px; }

/* STRICT LOGO SIZING */
.vigo-loader-logo {
    height: 70px !important;
    width: auto !important;
    max-width: 280px !important;
    object-fit: contain !important;
    display: block !important;
    filter: grayscale(100%) opacity(0.2);
    animation: colorReveal 2s ease-out forwards;
}

/* The CMYK Scanner Beam */
.vigo-scanner-beam {
    position: absolute; top: 0; left: -100%; width: 50px; height: 100%;
    background: linear-gradient(90deg, transparent, rgba(0,174,239,0.8), rgba(236,0,140,0.8), rgba(255,242,0,0.8), transparent);
    filter: blur(4px);
    animation: scanSweep 2s ease-in-out infinite;
}

.vigo-loading-text {
    color: #8DC63F !important; font-size: 14px !important; font-weight: bold !important;
    letter-spacing: 4px !important; text-transform: uppercase !important; 
    font-family: monospace !important;
    animation: textBlink 1.5s infinite;
}

@keyframes scanSweep { 0% { left: -100%; } 100% { left: 200%; } }
@keyframes colorReveal { 0% { filter: grayscale(100%) opacity(0.2); } 100% { filter: grayscale(0%) opacity(1); } }
@keyframes textBlink { 0%, 100% { opacity: 1; } 50% { opacity: 0.4; } }
</style>

<div id="vigo-preloader">
    <div class="vigo-panel-top"></div>
    <div class="vigo-panel-bottom"></div>
    
    <div class="vigo-loader-content">
        <div class="vigo-logo-wrapper">
            <img src="images/logo.png" alt="RwandaPrint" class="vigo-loader-logo">
            <div class="vigo-scanner-beam"></div>
        </div>
        <div class="vigo-loading-text">CALIBRATING PRESS...</div>
    </div>
</div>

<script>
    window.addEventListener('load', () => {
        // Runs the animation for exactly 2 seconds before splitting the screen
        setTimeout(() => {
            document.body.classList.remove('loading-lock');
            document.body.classList.add('loaded');
        }, 2000); 
    });
</script>