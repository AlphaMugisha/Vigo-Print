// --- 5-SECOND PRELOADER LOGIC ---
document.addEventListener("DOMContentLoaded", () => {
    const preloader = document.getElementById('preloader');
    
    // SAFETY CHECK: Only run this if the preloader actually exists on the page
    if (preloader) {
        setTimeout(() => {
            preloader.classList.add('fade-out');
            document.body.classList.remove('loading-lock');
        }, 5000); 
    } else {
        // If there is no preloader (like on the contact page), just make sure the body can scroll
        document.body.classList.remove('loading-lock');
    }
});

// --- NAVBAR SCROLL EFFECT ---
window.addEventListener('scroll', () => {
    const header = document.getElementById('navbar');
    if (window.scrollY > 50) header.classList.add('scrolled');
    else header.classList.remove('scrolled');
});

// --- SCROLL REVEAL ANIMATIONS ---
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

// --- MOBILE MENU TOGGLE ---
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

// --- TOGGLE PORTFOLIO ITEMS ---
function togglePortfolio() {
    const hiddenItems = document.querySelectorAll('.extra-items');
    const btn = document.getElementById('loadMoreBtn');
    
    hiddenItems.forEach(item => {
        if(item.style.display === 'block') {
            item.style.display = 'none';
            btn.innerText = 'View More Projects';
        } else {
            item.style.display = 'block';
            item.classList.add('active'); // Instantly reveal them
            btn.innerText = 'Show Less';
        }
    });
}