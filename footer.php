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

    <script>
        function togglePortfolio() {
            const extraItems = document.querySelectorAll('.extra-items');
            const btn = document.getElementById('loadMoreBtn');
            let isHidden = extraItems[0].classList.contains('hidden-work');

            extraItems.forEach(item => {
                if (isHidden) {
                    item.classList.remove('hidden-work');
                } else {
                    item.classList.add('hidden-work');
                }
            });

            if (isHidden) {
                btn.innerHTML = 'View Less Projects';
            } else {
                btn.innerHTML = 'View More Projects';
                document.getElementById('portfolio').scrollIntoView({ behavior: 'smooth' });
            }
        }
    </script>
</body>
</html>