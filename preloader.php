<style>
/* ========================================= */
/* VIGO BRANDED INDUSTRIAL PRELOADER         */
/* ========================================= */
body.loading-lock { overflow: hidden !important; height: 100vh !important; }

#vigo-preloader {
    position: fixed !important; top: 0 !important; left: 0 !important; 
    width: 100% !important; height: 100vh !important;
    z-index: 999999 !important; display: flex !important; 
    align-items: center !important; justify-content: center !important;
    background: #071222 !important; /* Slightly darker than your primary background */
    pointer-events: all;
}

/* 1. Print Registration Marks (Now in subtle white/green) */
.reg-mark {
    position: absolute; width: 60px; height: 60px;
    border: 1px solid rgba(141, 198, 63, 0.15); border-radius: 50%;
    animation: slowSpin 8s linear infinite;
    z-index: 2; pointer-events: none;
}
.reg-mark::before, .reg-mark::after { content: ''; position: absolute; background: rgba(255,255,255,0.1); }
.reg-mark::before { width: 100%; height: 1px; top: 50%; left: 0; }
.reg-mark::after { width: 1px; height: 100%; top: 0; left: 50%; }
.mark-tl { top: 40px; left: 40px; }
.mark-br { bottom: 40px; right: 40px; }
.mark-tr { top: 40px; right: 40px; }
.mark-bl { bottom: 40px; left: 40px; }

/* 2. The dark panels that slide apart */
.vigo-panel-top, .vigo-panel-bottom {
    position: absolute; left: 0; width: 100%; height: 50vh;
    background: #0A192F; /* Your exact primary dark color */
    z-index: 1;
    transition: transform 1s cubic-bezier(0.85, 0, 0.15, 1);
}
/* Split edges using your brand green */
.vigo-panel-top { top: 0; border-bottom: 2px solid #8DC63F; box-shadow: 0 10px 30px rgba(0,0,0,0.6); }
.vigo-panel-bottom { bottom: 0; border-top: 2px solid #8DC63F; box-shadow: 0 -10px 30px rgba(0,0,0,0.6); }

/* The Split Animation */
body.loaded .vigo-panel-top { transform: translateY(-100%); }
body.loaded .vigo-panel-bottom { transform: translateY(100%); }
body.loaded .vigo-loader-content { opacity: 0; transform: scale(1.1); visibility: hidden; }
body.loaded #vigo-preloader { pointer-events: none; transition-delay: 1s; background: transparent !important; }

/* 3. Content wrapper */
.vigo-loader-content {
    position: relative; z-index: 3; display: flex; flex-direction: column; 
    align-items: center; gap: 30px; transition: all 0.5s ease;
}

.vigo-logo-wrapper { position: relative; overflow: hidden; padding: 15px; }

/* ========================================= */
/* EVEN BIGGER LOGO SIZING                   */
/* ========================================= */
.vigo-loader-logo {
    width: 700px !important; /* CRANKED UP TO 700px */
    max-width: 90vw !important; /* Prevents it from bleeding off phone screens */
    height: auto !important; 
    object-fit: contain !important; 
    display: block !important;
    filter: grayscale(100%) brightness(0.3);
    animation: colorReveal 2s ease-out forwards;
}

/* VIGO BRANDED Scanner Beam (Green & White) */
.vigo-scanner-beam {
    position: absolute; top: 0; left: -100%; width: 150px; height: 100%;
    background: linear-gradient(90deg, transparent, rgba(141, 198, 63, 0.4), rgba(141, 198, 63, 0.9), rgba(255, 255, 255, 0.8), transparent);
    filter: blur(8px);
    transform: skewX(-20deg); 
    animation: scanSweep 1.8s cubic-bezier(0.4, 0, 0.2, 1) infinite;
}

/* 4. Loading Info Row */
.loading-info { display: flex; align-items: center; gap: 15px; }

.vigo-loading-text {
    color: rgba(255,255,255,0.6) !important; font-size: 14px !important; 
    letter-spacing: 5px !important; text-transform: uppercase !important; 
    font-family: monospace !important; transition: color 0.3s;
}

.vigo-counter {
    color: #8DC63F; font-size: 20px; font-weight: bold; font-family: monospace;
    text-shadow: 0 0 10px rgba(141, 198, 63, 0.4); min-width: 50px; text-align: right;
}

/* Animations */
@keyframes scanSweep { 0% { left: -100%; } 100% { left: 200%; } }
@keyframes colorReveal { 
    0% { filter: grayscale(100%) brightness(0.3); transform: scale(0.98); } 
    100% { filter: grayscale(0%) brightness(1); transform: scale(1); filter: drop-shadow(0 0 20px rgba(141, 198, 63, 0.2)); } 
}
@keyframes slowSpin { 100% { transform: rotate(360deg); } }
</style>

<div id="vigo-preloader">

    <div class="vigo-panel-top"></div>
    <div class="vigo-panel-bottom"></div>
    
    <div class="vigo-loader-content">
        <div class="vigo-logo-wrapper">
            <img src="images/image.png" alt="RwandaPrint" class="vigo-loader-logo">
            <div class="vigo-scanner-beam"></div>
        </div>
        
        <div class="loading-info">
            <div class="vigo-loading-text">CALIBRATING PRESS...</div>
            <div class="vigo-counter" id="vigo-counter">0%</div>
        </div>
    </div>
</div>

<script>
    window.addEventListener('load', () => {
        const counter = document.getElementById('vigo-counter');
        const text = document.querySelector('.vigo-loading-text');
        let count = 0;
        
        // Fast digital number counter
        const interval = setInterval(() => {
            count += Math.floor(Math.random() * 6) + 2;
            
            if (count >= 100) {
                count = 100;
                clearInterval(interval);
                counter.innerText = '100%';
                counter.style.color = '#FFFFFF'; // Flashes pure white when ready
                text.innerText = 'SYSTEM READY.';
                text.style.color = '#8DC63F'; // Text flashes brand green
                
                // Split the screen
                setTimeout(() => {
                    document.body.classList.remove('loading-lock');
                    document.body.classList.add('loaded');
                }, 600); 
            } else {
                counter.innerText = count + '%';
            }
        }, 40);
    });
</script>