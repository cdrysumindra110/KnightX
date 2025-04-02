<footer class="main-footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-section">
                <h3>About KnightX</h3>
                <p>Your ultimate destination for gaming electronics and game cards. We offer the latest gaming peripherals, accessories, and digital gift cards for all major gaming platforms.</p>
                <div class="social-links">
                    <a href="#" class="social-link"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-discord"></i></a>
                </div>
            </div>

            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul class="footer-links">
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="contact.php">Contact</a></li>
                    <li><a href="faq.php">FAQ</a></li>
                    <li><a href="shipping.php">Shipping Info</a></li>
                    <li><a href="returns.php">Returns Policy</a></li>
                    <li><a href="privacy.php">Privacy Policy</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h3>Categories</h3>
                <ul class="footer-links">
                    <li><a href="products.php?category=keyboards">Gaming Keyboards</a></li>
                    <li><a href="products.php?category=mice">Gaming Mice</a></li>
                    <li><a href="products.php?category=headsets">Gaming Headsets</a></li>
                    <li><a href="products.php?category=monitors">Gaming Monitors</a></li>
                    <li><a href="products.php?category=gift-cards">Gift Cards</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h3>Newsletter</h3>
                <p>Subscribe to our newsletter for exclusive offers and gaming updates!</p>
                <form class="newsletter-form" action="subscribe.php" method="POST">
                    <div class="form-group">
                        <input type="email" name="email" placeholder="Enter your email" required>
                        <button type="submit" class="subscribe-btn">Subscribe</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="payment-methods">
                <img src="assets/images/payment/visa.png" alt="Visa">
                <img src="assets/images/payment/mastercard.png" alt="Mastercard">
                <img src="assets/images/payment/paypal.png" alt="PayPal">
                <img src="assets/images/payment/stripe.png" alt="Stripe">
                <img src="assets/images/payment/bitcoin.png" alt="Bitcoin">
            </div>
            <p class="copyright">&copy; <?php echo date('Y'); ?> KnightX. All rights reserved.</p>
        </div>
    </div>
</footer>

<!-- Back to Top Button -->
<button id="back-to-top" class="back-to-top">
    <i class="fas fa-arrow-up"></i>
</button>

<!-- Loading Overlay -->
<div id="loading-overlay" class="loading-overlay">
    <div class="spinner"></div>
</div> 