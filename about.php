<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

// Add this at the top of your about.php file
$base_url = "http://" . $_SERVER['HTTP_HOST'] . "/KnightX/";


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
    
    <style>
        /* About Page Specific Styles */
        .page-header {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('assets/images/about/header-bg.jpg');
            background-size: cover;
            background-position: center;
            padding: 120px 0 60px;
            text-align: center;
            position: relative;
        }

        .page-header h1 {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            color: var(--primary-color);
            text-shadow: 0 0 10px rgba(0, 255, 136, 0.3);
        }

        .story-section, .mission-section, .team-section, .timeline-section, .testimonials-section, .partners-section {
            padding: 80px 0;
            position: relative;
        }

        .story-grid, .mission-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            align-items: center;
        }

        .story-content h2, .mission-content h2 {
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
            color: var(--primary-color);
        }

        .subtitle {
            font-size: 1.2rem;
            color: var(--text-secondary);
            margin-bottom: 2rem;
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            margin-top: 3rem;
        }

        .stat-item {
            text-align: center;
            padding: 20px;
            background: var(--card-bg);
            border-radius: 10px;
            transition: transform 0.3s ease;
        }

        .stat-item:hover {
            transform: translateY(-5px);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            display: block;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: var(--text-secondary);
            font-size: 1rem;
        }

        .values-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 30px;
            margin-top: 3rem;
        }

        .value-item {
            text-align: center;
            padding: 30px;
            background: var(--card-bg);
            border-radius: 15px;
            transition: all 0.3s ease;
        }

        .value-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .value-item i {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .team-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 30px;
            margin-top: 3rem;
        }

        .team-member {
            background: var(--card-bg);
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .team-member:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
        }

        .member-image {
            position: relative;
            width: 100%;
            height: 0;
            padding-bottom: 100%; /* 1:1 aspect ratio */
            overflow: hidden;
            border-radius: 15px 15px 0 0;
        }

        .member-img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .team-member:hover .member-img {
            transform: scale(1.1);
        }

        .member-info {
            padding: 20px;
            text-align: center;
        }

        .member-position {
            color: var(--text-secondary);
            margin-bottom: 1rem;
        }

        .member-social {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 1rem;
        }

        .timeline {
            position: relative;
            max-width: 800px;
            margin: 3rem auto;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            width: 2px;
            height: 100%;
            background: var(--primary-color);
        }

        .timeline-item {
            margin-bottom: 50px;
            position: relative;
        }

        .timeline-date {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            background: var(--primary-color);
            color: var(--background-dark);
            padding: 10px 20px;
            border-radius: 20px;
            font-weight: 600;
        }

        .timeline-content {
            width: calc(50% - 30px);
            padding: 20px;
            background: var(--card-bg);
            border-radius: 10px;
            position: relative;
        }

        .timeline-item:nth-child(odd) .timeline-content {
            margin-left: auto;
        }

        .testimonials-slider {
            max-width: 1000px;
            margin: 3rem auto;
            position: relative;
        }

        .testimonial-item {
            background: var(--card-bg);
            padding: 40px;
            border-radius: 15px;
            margin: 20px;
            position: relative;
        }

        .testimonial-quote {
            font-size: 1.1rem;
            line-height: 1.8;
            margin-bottom: 2rem;
        }

        .testimonial-quote i {
            color: var(--primary-color);
            font-size: 1.5rem;
            margin: 0 10px;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .testimonial-author img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
        }

        .partners-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 30px;
            margin-top: 3rem;
        }

        .partner-item {
            background: var(--card-bg);
            padding: 30px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            height: 150px; /* Fixed height for consistency */
        }

        .partner-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .partner-img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            filter: grayscale(100%);
            transition: filter 0.3s ease;
        }

        .partner-item:hover .partner-img {
            filter: grayscale(0%);
        }

        @media (max-width: 1024px) {
            .story-grid, .mission-grid {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .team-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .partners-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 2.5rem;
            }

            .stats-container {
                grid-template-columns: 1fr;
            }

            .values-container {
                grid-template-columns: 1fr;
            }

            .team-grid {
                grid-template-columns: 1fr;
            }

            .partners-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .timeline::before {
                left: 30px;
            }

            .timeline-date {
                left: 30px;
                transform: none;
            }

            .timeline-content {
                width: calc(100% - 60px);
                margin-left: 60px !important;
            }

            .story-image, .mission-image {
                padding-bottom: 56.25%; /* 16:9 aspect ratio for mobile */
            }

            .member-image {
                padding-bottom: 100%; /* Keep 1:1 for team members */
            }

            .partner-item {
                height: 120px;
            }
        }

        @media (max-width: 480px) {
            .page-header h1 {
                font-size: 2rem;
            }

            .partners-grid {
                grid-template-columns: 1fr;
            }

            .partner-item {
                height: 100px;
            }
        }

        /* Image handling styles */
        .story-image, .mission-image {
            position: relative;
            width: 100%;
            height: 0;
            padding-bottom: 75%; /* 4:3 aspect ratio */
            overflow: hidden;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .story-img, .mission-img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .story-image:hover .story-img,
        .mission-image:hover .mission-img {
            transform: scale(1.05);
        }

        /* Image loading animation */
        .story-img, .mission-img, .member-img, .partner-img {
            opacity: 0;
            animation: fadeIn 0.5s ease forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        /* Image error handling */
        .story-img[src*="gaming-bg.jpg"],
        .mission-img[src*="gaming-bg.jpg"],
        .member-img[src*="gaming-bg.jpg"],
        .partner-img[src*="gaming-bg.jpg"] {
            filter: grayscale(50%) brightness(0.8);
        }

        /* Add loading placeholder */
        .story-image::before,
        .mission-image::before,
        .member-image::before,
        .partner-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, 
                var(--background-dark) 0%, 
                var(--background-light) 50%, 
                var(--background-dark) 100%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
            z-index: 1;
        }

        @keyframes loading {
            0% {
                background-position: 200% 0;
            }
            100% {
                background-position: -200% 0;
            }
        }

        /* Remove loading placeholder when image is loaded */
        .story-img.loaded ~ .story-image::before,
        .mission-img.loaded ~ .mission-image::before,
        .member-img.loaded ~ .member-image::before,
        .partner-img.loaded ~ .partner-item::before {
            display: none;
        }

        /* Add loading indicator styles */
        .story-image, .mission-image, .member-image, .partner-item {
            position: relative;
            background: var(--background-dark);
        }

        .story-image::before,
        .mission-image::before,
        .member-image::before,
        .partner-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, 
                var(--background-dark) 0%, 
                var(--background-light) 50%, 
                var(--background-dark) 100%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
            z-index: 1;
        }

        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        /* Remove loading indicator when image is loaded */
        img.loaded ~ .story-image::before,
        img.loaded ~ .mission-image::before,
        img.loaded ~ .member-image::before,
        img.loaded ~ .partner-item::before {
            display: none;
        }

        /* Ensure images maintain aspect ratio */
        .story-img, .mission-img, .member-img, .partner-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Add error state styling */
        img[src*="gaming-bg.jpg"] {
            filter: grayscale(50%) brightness(0.8);
        }
    </style>
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
                    <img src="./assets/images/about/our-story.jpg" 
                         alt="KnightX Founders" 
                         loading="lazy"
                         onerror="this.src='./assets/images/gaming-bg.jpg'"
                         class="story-img">
                </div>
            </div>
        </div>
    </section>

    <!-- Mission Section -->
    <section class="mission-section">
        <div class="container">
            <div class="mission-grid">
                <div class="mission-image">
                    <img src="./assets/images/about/mission.jpg" 
                         alt="Our Mission" 
                         loading="lazy"
                         onerror="this.src='./assets/images/gaming-bg.jpg'"
                         class="mission-img">
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
                        <img src="./assets/images/about/team-1.png" 
                             alt="Alex Knight - CEO" 
                             loading="lazy"
                             onerror="this.src='./assets/images/gaming-bg.jpg'"
                             class="member-img">
                    </div>
                    <div class="member-info">
                        <h3>Alex Knight</h3>
                        <p class="member-position">CEO & Founder</p>
                        <p class="member-bio">A lifelong gamer with a background in tech, Alex founded KnightX to revolutionize the gaming gear market.</p>
                        <div class="member-social">
                            <a href="#" class="social-link"><i class="fab fa-linkedin"></i></a>
                            <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                </div>
                <div class="team-member">
                    <div class="member-image">
                        <img src="./assets/images/about/team-2.jpg" 
                             alt="Sarah Chen - CTO" 
                             loading="lazy"
                             onerror="this.src='./assets/images/gaming-bg.jpg'"
                             class="member-img">
                    </div>
                    <div class="member-info">
                        <h3>Sarah Chen</h3>
                        <p class="member-position">CTO</p>
                        <p class="member-bio">With 15 years of experience in e-commerce development, Sarah ensures our platform delivers a seamless shopping experience.</p>
                        <div class="member-social">
                            <a href="#" class="social-link"><i class="fab fa-linkedin"></i></a>
                            <a href="#" class="social-link"><i class="fab fa-github"></i></a>
                        </div>
                    </div>
                </div>
                <div class="team-member">
                    <div class="member-image">
                        <img src="./assets/images/about/team-3.jpg" 
                             alt="Marcus Johnson - Product Director" 
                             loading="lazy"
                             onerror="this.src='./assets/images/gaming-bg.jpg'"
                             class="member-img">
                    </div>
                    <div class="member-info">
                        <h3>Marcus Johnson</h3>
                        <p class="member-position">Product Director</p>
                        <p class="member-bio">A former esports competitor, Marcus brings firsthand knowledge of what gamers need to perform at their best.</p>
                        <div class="member-social">
                            <a href="#" class="social-link"><i class="fab fa-linkedin"></i></a>
                            <a href="#" class="social-link"><i class="fab fa-twitch"></i></a>
                        </div>
                    </div>
                </div>
                <div class="team-member">
                    <div class="member-image">
                        <img src="./assets/images/about/team-4.jpg" 
                             alt="Emma Rodriguez - Customer Experience" 
                             loading="lazy"
                             onerror="this.src='./assets/images/gaming-bg.jpg'"
                             class="member-img">
                    </div>
                    <div class="member-info">
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
                    <img src="./assets/images/partners/partner-1.png" 
                         alt="Logitech" 
                         loading="lazy"
                         onerror="this.src='./assets/images/gaming-bg.jpg'"
                         class="partner-img">
                </div>
                <div class="partner-item">
                    <img src="./assets/images/partners/partner-2.png" 
                         alt="Razer" 
                         loading="lazy"
                         onerror="this.src='./assets/images/gaming-bg.jpg'"
                         class="partner-img">
                </div>
                <div class="partner-item">
                    <img src="./assets/images/partners/partner-3.png" 
                         alt="SteelSeries" 
                         loading="lazy"
                         onerror="this.src='./assets/images/gaming-bg.jpg'"
                         class="partner-img">
                </div>
                <div class="partner-item">
                    <img src="./assets/images/partners/partner-4.png" 
                         alt="HyperX" 
                         loading="lazy"
                         onerror="this.src='./assets/images/gaming-bg.jpg'"
                         class="partner-img">
                </div>
                <div class="partner-item">
                    <img src="./assets/images/partners/partner-5.png" 
                         alt="Corsair" 
                         loading="lazy"
                         onerror="this.src='./assets/images/gaming-bg.jpg'"
                         class="partner-img">
                </div>
                <div class="partner-item">
                    <img src="./assets/images/partners/partner-6.png" 
                         alt="ASUS ROG" 
                         loading="lazy"
                         onerror="this.src='./assets/images/gaming-bg.jpg'"
                         class="partner-img">
                </div>
                <div class="partner-item">
                    <img src="./assets/images/partners/partner-7.png" 
                         alt="MSI" 
                         loading="lazy"
                         onerror="this.src='./assets/images/gaming-bg.jpg'"
                         class="partner-img">
                </div>
                <div class="partner-item">
                    <img src="./assets/images/partners/partner-8.png" 
                         alt="NVIDIA" 
                         loading="lazy"
                         onerror="this.src='./assets/images/gaming-bg.jpg'"
                         class="partner-img">
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
        // Initialize GSAP animations
        gsap.registerPlugin(ScrollTrigger);

        // Animate page header
        gsap.from('.page-header h1', {
            duration: 1,
            y: 50,
            opacity: 0,
            ease: 'power3.out'
        });

        // Animate story section
        gsap.from('.story-content', {
            scrollTrigger: {
                trigger: '.story-section',
                start: 'top 80%'
            },
            duration: 1,
            x: -50,
            opacity: 0,
            ease: 'power3.out'
        });

        gsap.from('.story-image', {
            scrollTrigger: {
                trigger: '.story-section',
                start: 'top 80%'
            },
            duration: 1,
            x: 50,
            opacity: 0,
            ease: 'power3.out'
        });

        // Animate stats
        const stats = document.querySelectorAll('.stat-number');
        stats.forEach(stat => {
            const target = parseInt(stat.textContent);
            gsap.to(stat, {
                scrollTrigger: {
                    trigger: stat,
                    start: 'top 80%'
                },
                duration: 2,
                innerText: target,
                snap: { innerText: 1 },
                ease: 'power1.out'
            });
        });

        // Animate team members
        gsap.from('.team-member', {
            scrollTrigger: {
                trigger: '.team-section',
                start: 'top 80%'
            },
            duration: 0.8,
            y: 50,
            opacity: 0,
            stagger: 0.2,
            ease: 'power3.out'
        });

        // Animate timeline
        gsap.from('.timeline-item', {
            scrollTrigger: {
                trigger: '.timeline-section',
                start: 'top 80%'
            },
            duration: 0.8,
            y: 50,
            opacity: 0,
            stagger: 0.2,
            ease: 'power3.out'
        });

        // Animate testimonials
        gsap.from('.testimonial-item', {
            scrollTrigger: {
                trigger: '.testimonials-section',
                start: 'top 80%'
            },
            duration: 0.8,
            y: 50,
            opacity: 0,
            stagger: 0.2,
            ease: 'power3.out'
        });

        // Animate partners
        gsap.from('.partner-item', {
            scrollTrigger: {
                trigger: '.partners-section',
                start: 'top 80%'
            },
            duration: 0.8,
            y: 50,
            opacity: 0,
            stagger: 0.1,
            ease: 'power3.out'
        });

        // Image loading handling
        document.addEventListener('DOMContentLoaded', function() {
            const images = document.querySelectorAll('img');
            
            images.forEach(img => {
                // Log when image starts loading
                console.log('Loading image:', img.src);
                
                // Log when image loads successfully
                img.addEventListener('load', function() {
                    console.log('Image loaded successfully:', this.src);
                    this.classList.add('loaded');
                });
                
                // Log when image fails to load
                img.addEventListener('error', function() {
                    console.error('Failed to load image:', this.src);
                    // Try to load fallback image
                    if (this.src !== this.getAttribute('onerror').match(/src='([^']+)'/)[1]) {
                        this.src = this.getAttribute('onerror').match(/src='([^']+)'/)[1];
                    }
                });
            });
        });
    </script>
</body>
</html> 