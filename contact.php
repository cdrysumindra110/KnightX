<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

// Process form submission
$message_sent = false;
$message_error = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Form validation
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    // Basic validation
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $message_error = "Please fill in all required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message_error = "Please enter a valid email address.";
    } else {
        // In a real application, you would send an email here
        // For demo purposes, we'll just set a success message
        $message_sent = true;
        
        // Optional: Save to database
        // $sql = "INSERT INTO contacts (name, email, subject, message, created_at) VALUES (?, ?, ?, ?, NOW())";
        // $stmt = $conn->prepare($sql);
        // $stmt->bind_param("ssss", $name, $email, $subject, $message);
        // $stmt->execute();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - KnightX</title>
    <meta name="description" content="Contact KnightX for questions about our gaming electronics and game cards. We're here to help!">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/images/favicon.png">
    
    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/contact.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- GSAP for animations -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>

    <style>
        /* Ensure proper spacing with fixed navbar */
        .contact-page-header {
            padding-top: 120px;
        }
        
        /* Ensure container width is consistent */
        .container {
            width: 100%;
            /* max-width: 1200px; */
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* Ensure main content width */
        .main-content {
            width: 100%;
            overflow-x: hidden;
        }
    </style>
</head>
<body class="dark-theme">
    <!-- Header -->
    <?php include 'includes/header.php'; ?>

    <div class="main-content">
        <!-- Page Header -->
        <section class="contact-page-header">
            <div class="container">
                <h1>Contact Us</h1>
                <nav class="breadcrumb">
                    <a href="index.php">Home</a> / Contact Us
                </nav>
            </div>
        </section>

        <!-- Contact Section -->
        <section class="contact-section">
            <div class="container">
                <?php if ($message_sent): ?>
                    <div class="alert success">
                        <i class="fas fa-check-circle"></i> Your message has been sent successfully! We'll get back to you soon.
                    </div>
                <?php elseif ($message_error): ?>
                    <div class="alert error">
                        <i class="fas fa-exclamation-circle"></i> <?php echo $message_error; ?>
                    </div>
                <?php endif; ?>

                <!-- Contact Highlights -->
                <div class="contact-highlights-wrapper">
                    <div class="contact-highlights">
                        <div class="highlight-item">
                            <div class="highlight-icon">
                                <i class="fas fa-headset"></i>
                            </div>
                            <div class="highlight-content">
                                <h3>24/7 Support</h3>
                                <p>Our gaming experts are ready to help</p>
                            </div>
                        </div>
                        <div class="highlight-item">
                            <div class="highlight-icon">
                                <i class="fas fa-truck-fast"></i>
                            </div>
                            <div class="highlight-content">
                                <h3>Fast Shipping</h3>
                                <p>Free delivery on orders over $100</p>
                            </div>
                        </div>
                        <div class="highlight-item">
                            <div class="highlight-icon">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div class="highlight-content">
                                <h3>Secure Payments</h3>
                                <p>100% secure payment processing</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="contact-grid">
                    <div class="contact-info">
                        <h2>Get in Touch</h2>
                        <p>We're here to answer any questions you may have about our gaming products or services. Reach out to us and we'll respond as soon as we can.</p>
                        
                        <div class="info-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <div>
                                <h3>Address</h3>
                                <p>123 Gaming Street, Tech City<br>CA 94107, United States</p>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <i class="fas fa-phone"></i>
                            <div>
                                <h3>Phone</h3>
                                <p><a href="tel:+1234567890">+1 (234) 567-890</a></p>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <i class="fas fa-envelope"></i>
                            <div>
                                <h3>Email</h3>
                                <p><a href="mailto:support@knightx.com">support@knightx.com</a></p>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <i class="fas fa-clock"></i>
                            <div>
                                <h3>Working Hours</h3>
                                <p>Monday - Friday: 9am to 5pm<br>Saturday: 10am to 3pm<br>Sunday: Closed</p>
                            </div>
                        </div>
                        
                        <div class="social-media">
                            <h3>Follow Us</h3>
                            <div class="social-icons">
                                <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                                <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                                <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                                <a href="#" class="social-icon"><i class="fab fa-discord"></i></a>
                                <a href="#" class="social-icon"><i class="fab fa-twitch"></i></a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="contact-form-container">
                        <div class="form-header">
                            <div class="form-icon">
                                <i class="fas fa-paper-plane"></i>
                            </div>
                            <h2>Send Us a Message</h2>
                        </div>
                        <form class="contact-form" action="contact.php" method="POST">
                            <div class="form-group">
                                <label for="name"><i class="fas fa-user"></i> Your Name *</label>
                                <input type="text" id="name" name="name" placeholder="Enter your full name" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="email"><i class="fas fa-envelope"></i> Your Email *</label>
                                <input type="email" id="email" name="email" placeholder="Enter your email address" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="subject"><i class="fas fa-tag"></i> Subject *</label>
                                <input type="text" id="subject" name="subject" placeholder="What is this regarding?" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="message"><i class="fas fa-comment-alt"></i> Message *</label>
                                <textarea id="message" name="message" rows="5" placeholder="How can we help you?" required></textarea>
                            </div>
                            
                            <button type="submit" class="submit-btn">
                                <span>Send Message</span>
                                <i class="fas fa-arrow-right"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <!-- Map Section -->
        <section class="map-section">
            <div class="container">
                <div class="section-header">
                    <h2>Find Us</h2>
                    <p class="section-subtext">Visit our gaming headquarters</p>
                </div>
                <div class="map-container">
                    <!-- Replace with your Google Maps embed code -->
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d423286.27405770525!2d-118.69192047471653!3d34.02016130653294!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x80c2c75ddc27da13%3A0xe22fdf6f254608f4!2sLos%20Angeles%2C%20CA%2C%20USA!5e0!3m2!1sen!2s!4v1647822854149!5m2!1sen!2s" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
        </section>


        <!-- Newsletter Section -->
        <section class="newsletter-section">
            <div class="container">
                <div class="newsletter-container">
                    <div class="newsletter-content">
                        <h2>Subscribe to Our Newsletter</h2>
                        <p>Get the latest gaming news, exclusive deals, and special offers delivered directly to your inbox.</p>
                    </div>
                    <form class="newsletter-form" action="subscribe.php" method="POST">
                        <div class="form-group">
                            <input type="email" name="email" placeholder="Enter your email address" required>
                            <button type="submit" class="subscribe-btn">Subscribe <i class="fas fa-paper-plane"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize GSAP
            gsap.registerPlugin(ScrollTrigger);

            // Contact highlights animation
            const highlightItems = document.querySelectorAll('.highlight-item');
            
            // Set initial state
            gsap.set(highlightItems, {
                opacity: 1,
                visibility: 'visible'
            });

            // Create the animation
            gsap.from(highlightItems, {
                duration: 0.8,
                y: 50,
                opacity: 0,
                stagger: 0.2,
                ease: 'power3.out',
                scrollTrigger: {
                    trigger: '.contact-highlights',
                    start: 'top 80%',
                    toggleActions: 'play none none none'
                }
            });

            // Contact form and info animation
            gsap.from('.contact-info', {
                duration: 1,
                x: -50,
                opacity: 0,
                ease: 'power3.out',
                scrollTrigger: {
                    trigger: '.contact-grid',
                    start: 'top 70%',
                    toggleActions: 'play none none none'
                }
            });

            gsap.from('.contact-form-container', {
                duration: 1,
                x: 50,
                opacity: 0,
                ease: 'power3.out',
                scrollTrigger: {
                    trigger: '.contact-grid',
                    start: 'top 70%',
                    toggleActions: 'play none none none'
                }
            });

            // Map animation
            gsap.from('.map-container', {
                duration: 1,
                y: 50,
                opacity: 0,
                ease: 'power3.out',
                scrollTrigger: {
                    trigger: '.map-section',
                    start: 'top 80%',
                    toggleActions: 'play none none none'
                }
            });

            // Newsletter animation
            gsap.from('.newsletter-container', {
                duration: 1,
                y: 30,
                opacity: 0,
                ease: 'power3.out',
                scrollTrigger: {
                    trigger: '.newsletter-section',
                    start: 'top 80%',
                    toggleActions: 'play none none none'
                }
            });
        });
    </script>
</body>
</html> 