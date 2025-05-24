<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<header class="main-header">
    <div class="header-top">
        <div class="container">
            <!-- <div class="contact-info">
                <a href="tel:+1234567890"><i class="fas fa-phone"></i> +1 (234) 567-890</a>
                <a href="mailto:support@knightx.com"><i class="fas fa-envelope"></i> support@knightx.com</a>
            </div>
            <div class="social-links">
                <a href="#" class="social-link"><i class="fab fa-facebook"></i></a>
                <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                <a href="#" class="social-link"><i class="fab fa-discord"></i></a>
            </div> -->
        </div>
    </div>

    <nav class="main-nav">
        <div class="container">
            <div class="nav-left">
                <div class="logo">
                    <a href="index.php">
                        <img src="assets/images/logo.png" alt="KnightX Logo">
                    </a>
                </div>
            </div>

            <div class="nav-center">
                <div class="search-container">
                    <form action="search.php" method="GET" class="search-form">
                        <input type="text" name="q" placeholder="Search products..." class="search-input">
                        <button type="submit" class="search-button">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>

            <div class="nav-right">
                <div class="nav-links">
                    <ul>
                        <li class="<?php echo $current_page == 'index.php' ? 'active' : ''; ?>">
                            <a href="index.php">Home</a>
                        </li>
                        <li class="<?php echo $current_page == 'products.php' ? 'active' : ''; ?>">
                            <a href="products.php">Products</a>
                        </li>
                        <li class="<?php echo $current_page == 'categories.php' ? 'active' : ''; ?>">
                            <a href="categories.php">Categories</a>
                        </li>
                        <li class="<?php echo $current_page == 'deals.php' ? 'active' : ''; ?>">
                            <a href="deals.php">Deals</a>
                        </li>
                        <li class="<?php echo $current_page == 'about.php' ? 'active' : ''; ?>">
                            <a href="about.php">About</a>
                        </li>
                        <li class="<?php echo $current_page == 'contact.php' ? 'active' : ''; ?>">
                            <a href="contact.php">Contact</a>
                        </li>
                        <li class="<?php echo $current_page == 'cart.php' ? 'active' : ''; ?>">
                            <a href="cart.php" class="cart-link">
                                <i class="fas fa-shopping-cart"></i>
                                <span class="cart-count">
                                    <?php
                                    $cart_count = 0;
                                    if (isset($_SESSION['cart'])) {
                                        foreach ($_SESSION['cart'] as $quantity) {
                                            $cart_count += $quantity;
                                        }
                                    }
                                    echo $cart_count;
                                    ?>
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- Toggle Button for Small Screens -->
                <button class="toggle-button" aria-label="Toggle Menu">
                    <i class="fas fa-bars"></i> <!-- Menu icon -->
                </button>

                <div class="user-actions">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="account.php" class="account-link">
                            <i class="fas fa-user"></i>
                        </a>

                        <a href="logout.php" class="logout-link">
                            <i class="fas fa-sign-out-alt"></i>
                        </a>
                    <?php else: ?>
                        <a href="login.php" class="login-link">
                            <i class="fas fa-sign-in-alt"></i>
                        </a>
                        <a href="signup.php" class="register-link">
                            <i class="fas fa-user-plus"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
</header>
<script>
    // JavaScript for the toggle functionality
    document.querySelector('.toggle-button').addEventListener('click', function() {
        // Toggle the 'active' class on the navbar links to show or hide the menu
        document.querySelector('.nav-links').classList.toggle('active');
    });
</script>