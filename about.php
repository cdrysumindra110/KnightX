<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - KnightX</title>
    <meta name="description" content="Learn about KnightX, your premier source for gaming electronics and game cards. Discover our mission, values, and the team behind your favorite gaming store.">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/images/favicon.png">
    
    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- GSAP for animations -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
</head>
<body class="dark-theme">
    <!-- Header -->
    <?php include 'includes/header.php'; ?>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1>About Us</h1>
            <nav class="breadcrumb">
                <a href="index.php">Home</a> / About Us
            </nav>
        </div>
    </section>

    <!-- Story Section -->
    <section class="story-section">
        <div class="container">
            <div class="story-grid">
                <div class="story-content">
                    <h2>Our Story</h2>
                    <p class="subtitle">The Gaming Journey That Started It All</p>
                    <p>KnightX was founded in 2018 by a group of passionate gamers who were frustrated with the lack of reliable sources for high-quality gaming peripherals and digital content. What began as a small online store operated from a garage has grown into one of the most trusted gaming equipment retailers in the industry.</p>
                    <p>Our founders' vision was simple: create a one-stop destination where gamers could find premium gaming gear and digital content without breaking the bank. By working directly with manufacturers and game publishers, we've been able to offer competitive prices without compromising on quality.</p>
                    <p>Today, KnightX serves thousands of customers worldwide, maintaining the same passion for gaming and commitment to customer satisfaction that inspired its creation.</p>
                    <div class="stats-container">
                        <div class="stat-item">
                            <span class="stat-number">50K+</span>
                            <span class="stat-label">Happy Customers</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">1000+</span>
                            <span class="stat-label">Products</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">15+</span>
                            <span class="stat-label">Brand Partners</span>
                        </div>
                    </div>
                </div>
                <div class="story-image">
                    <img src="assets/images/about/our-story.jpg" alt="KnightX Founders">
                </div>
            </div>
        </div>
    </section>

    <!-- Mission Section -->
    <section class="mission-section">
        <div class="container">
            <div class="mission-grid">
                <div class="mission-image">
                    <img src="assets/images/about/mission.jpg" alt="Our Mission">
                </div>
                <div class="mission-content">
                    <h2>Our Mission</h2>
                    <p class="subtitle">Empowering Gamers Worldwide</p>
                    <p>At KnightX, our mission is to enhance every gamer's experience by providing access to cutting-edge gaming technology and digital content at fair prices. We believe that everyone deserves the tools to perform at their best, whether they're casual players or aspiring professionals.</p>
                    <div class="values-container">
                        <div class="value-item">
                            <i class="fas fa-trophy"></i>
                            <h3>Quality</h3>
                            <p>We curate only the best gaming products that meet our rigorous standards.</p>
                        </div>
                        <div class="value-item">
                            <i class="fas fa-handshake"></i>
                            <h3>Integrity</h3>
                            <p>We operate with transparency and honesty in every transaction.</p>
                        </div>
                        <div class="value-item">
                            <i class="fas fa-users"></i>
                            <h3>Community</h3>
                            <p>We foster a supportive community of gamers who share our passion.</p>
                        </div>
                        <div class="value-item">
                            <i class="fas fa-rocket"></i>
                            <h3>Innovation</h3>
                            <p>We continuously seek new technologies to improve the gaming experience.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="team-section">
        <div class="container">
            <h2>Meet Our Team</h2>
            <p class="subtitle">The Passionate Gamers Behind KnightX</p>
            <div class="team-grid">
                <div class="team-member">
                    <div class="member-image">
                        <img src="assets/images/about/team-1.png" alt="Alex Knight - CEO">
                    </div>
                    <h3>Alex Knight</h3>
                    <p class="member-position">CEO & Founder</p>
                    <p class="member-bio">A lifelong gamer with a background in tech, Alex founded KnightX to revolutionize the gaming gear market.</p>
                    <div class="member-social">
                        <a href="#" class="social-link"><i class="fab fa-linkedin"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
                <div class="team-member">
                    <div class="member-image">
                        <img src="assets/images/about/team-2.jpg" alt="Sarah Chen - CTO">
                    </div>
                    <h3>Sarah Chen</h3>
                    <p class="member-position">CTO</p>
                    <p class="member-bio">With 15 years of experience in e-commerce development, Sarah ensures our platform delivers a seamless shopping experience.</p>
                    <div class="member-social">
                        <a href="#" class="social-link"><i class="fab fa-linkedin"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-github"></i></a>
                    </div>
                </div>
                <div class="team-member">
                    <div class="member-image">
                        <img src="assets/images/about/team-3.jpg" alt="Marcus Johnson - Product Director">
                    </div>
                    <h3>Marcus Johnson</h3>
                    <p class="member-position">Product Director</p>
                    <p class="member-bio">A former esports competitor, Marcus brings firsthand knowledge of what gamers need to perform at their best.</p>
                    <div class="member-social">
                        <a href="#" class="social-link"><i class="fab fa-linkedin"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-twitch"></i></a>
                    </div>
                </div>
                <div class="team-member">
                    <div class="member-image">
                        <img src="assets/images/about/team-4.jpg" alt="Emma Rodriguez - Customer Experience">
                    </div>
                    <h3>Emma Rodriguez</h3>
                    <p class="member-position">Customer Experience Manager</p>
                    <p class="member-bio">Emma ensures every customer interaction with KnightX exceeds expectations with her customer-first approach.</p>
                    <div class="member-social">
                        <a href="#" class="social-link"><i class="fab fa-linkedin"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Milestones Section -->
    <section class="timeline-section">
        <div class="container">
            <h2>Our Journey</h2>
            <p class="subtitle">Key Milestones That Shaped KnightX</p>
            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-date">2018</div>
                    <div class="timeline-content">
                        <h3>The Beginning</h3>
                        <p>KnightX was founded in a small garage with just 50 products and a dream to revolutionize gaming gear retail.</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-date">2019</div>
                    <div class="timeline-content">
                        <h3>First Office</h3>
                        <p>We moved to our first official office space and expanded our team to 10 passionate gamers.</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-date">2020</div>
                    <div class="timeline-content">
                        <h3>Going International</h3>
                        <p>KnightX began shipping products internationally, reaching gamers across Europe and Asia.</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-date">2021</div>
                    <div class="timeline-content">
                        <h3>Partnership Expansion</h3>
                        <p>We established direct partnerships with major gaming hardware manufacturers and game publishers.</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-date">2022</div>
                    <div class="timeline-content">
                        <h3>Mobile App Launch</h3>
                        <p>The KnightX mobile app was launched, making it easier for gamers to shop on the go.</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-date">2023</div>
                    <div class="timeline-content">
                        <h3>Today</h3>
                        <p>KnightX continues to grow, with a commitment to providing the best gaming products and experiences to our community.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials-section">
        <div class="container">
            <h2>What Our Customers Say</h2>
            <p class="subtitle">Real Feedback from the KnightX Community</p>
            <div class="testimonials-slider">
                <div class="testimonial-item">
                    <div class="testimonial-quote">
                        <i class="fas fa-quote-left"></i>
                        <p>KnightX completely transformed my gaming setup. Their product recommendations were spot-on, and the delivery was faster than expected. I won't shop anywhere else for my gaming needs!</p>
                        <i class="fas fa-quote-right"></i>
                    </div>
                    <div class="testimonial-author">
                        <img src="assets/images/about/testimonial-1.jpg" alt="David P.">
                        <div>
                            <h4>David P.</h4>
                            <p>Competitive FPS Player</p>
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="testimonial-item">
                    <div class="testimonial-quote">
                        <i class="fas fa-quote-left"></i>
                        <p>The customer service at KnightX is unmatched. When I had an issue with my order, their support team resolved it within hours. That kind of care for customers is rare these days.</p>
                        <i class="fas fa-quote-right"></i>
                    </div>
                    <div class="testimonial-author">
                        <img src="assets/images/about/testimonial-2.jpg" alt="Rebecca T.">
                        <div>
                            <h4>Rebecca T.</h4>
                            <p>Streaming Content Creator</p>
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="testimonial-item">
                    <div class="testimonial-quote">
                        <i class="fas fa-quote-left"></i>
                        <p>As a parent buying gaming equipment for my kids, I appreciated the detailed product descriptions and honest reviews on KnightX. Made my decision so much easier!</p>
                        <i class="fas fa-quote-right"></i>
                    </div>
                    <div class="testimonial-author">
                        <img src="assets/images/about/testimonial-3.jpg" alt="Michael K.">
                        <div>
                            <h4>Michael K.</h4>
                            <p>Parent & Casual Gamer</p>
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Partner Brands Section -->
    <section class="partners-section">
        <div class="container">
            <h2>Our Partners</h2>
            <p class="subtitle">Trusted Brands We Work With</p>
            <div class="partners-grid">
                <div class="partner-item">
                    <img src="assets/images/partners/partner-1.png" alt="Logitech">
                </div>
                <div class="partner-item">
                    <img src="assets/images/partners/partner-2.png" alt="Razer">
                </div>
                <div class="partner-item">
                    <img src="assets/images/partners/partner-3.png" alt="SteelSeries">
                </div>
                <div class="partner-item">
                    <img src="assets/images/partners/partner-4.png" alt="HyperX">
                </div>
                <div class="partner-item">
                    <img src="assets/images/partners/partner-5.png" alt="Corsair">
                </div>
                <div class="partner-item">
                    <img src="assets/images/partners/partner-6.png" alt="ASUS ROG">
                </div>
                <div class="partner-item">
                    <img src="assets/images/partners/partner-7.png" alt="MSI">
                </div>
                <div class="partner-item">
                    <img src="assets/images/partners/partner-8.png" alt="NVIDIA">
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2>Join the KnightX Community</h2>
                <p>Be the first to know about our latest products, exclusive deals, and gaming news.</p>
                <form class="newsletter-form" action="subscribe.php" method="POST">
                    <div class="form-group">
                        <input type="email" name="email" placeholder="Enter your email" required>
                        <button type="submit" class="subscribe-btn">Subscribe</button>
                    </div>
                </form>
                <div class="social-links">
                    <a href="#" class="social-link"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-discord"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-twitch"></i></a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script>
        // Testimonial slider functionality
        $(document).ready(function() {
            let currentSlide = 0;
            const testimonials = $('.testimonial-item');
            const totalSlides = testimonials.length;
            
            // Show only the first testimonial initially
            testimonials.hide();
            testimonials.eq(0).show();
            
            // Function to move to the next slide
            function nextSlide() {
                testimonials.eq(currentSlide).hide();
                currentSlide = (currentSlide + 1) % totalSlides;
                testimonials.eq(currentSlide).fadeIn();
            }
            
            // Auto-advance slides every 5 seconds
            setInterval(nextSlide, 5000);
            
            // Animation for counting stats
            const stats = $('.stat-number');
            let animated = false;
            
            // Animate the stats when they come into view
            $(window).scroll(function() {
                if (!animated && $(window).scrollTop() + $(window).height() > $('.stats-container').offset().top) {
                    animated = true;
                    stats.each(function() {
                        const $this = $(this);
                        const target = parseInt($this.text().replace(/,/g, '').replace(/\+/g, ''));
                        $this.prop('Counter', 0).animate({
                            Counter: target
                        }, {
                            duration: 2000,
                            easing: 'swing',
                            step: function(now) {
                                $this.text(Math.ceil(now).toLocaleString() + '+');
                            }
                        });
                    });
                }
            });
        });
    </script>
</body>
</html> 