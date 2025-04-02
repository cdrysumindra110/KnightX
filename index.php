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
    <title>KnightX - Gaming Electronics & Game Cards</title>
    <meta name="description" content="Shop the latest gaming electronics and game cards at KnightX. Find gaming keyboards, mice, headsets, monitors, and gift cards for PlayStation, Xbox, and Steam.">
    
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

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1 class="neon-text">Welcome to KnightX</h1>
            <p class="hero-subtitle">Your Ultimate Gaming Gear Destination</p>
            <a href="#featured-products" class="cta-button">Explore Products</a>
        </div>
        <div class="hero-overlay"></div>
    </section>

    <!-- Featured Products -->
    <section id="featured-products" class="featured-products">
        <h2 class="section-title">Featured Products</h2>
        <div class="product-slider">
            <!-- Products will be loaded dynamically via AJAX -->
        </div>
    </section>

    <!-- Categories -->
    <section class="categories">
        <h2 class="section-title">Shop by Category</h2>
        <div class="category-grid">
            <!-- Categories will be loaded dynamically -->
        </div>
    </section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/main.js"></script>
    <!-- <script src="assets/js/animations.js"></script> -->

</body>
</html>
