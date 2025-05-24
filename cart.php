<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

// Check if user is logged in
if (!isLoggedIn()) {
    $_SESSION['redirect_after_login'] = 'cart.php';
    header('Location: login.php');
    exit;
}

// Handle cart actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'update':
                if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
                    $product_id = (int)$_POST['product_id'];
                    $quantity = (int)$_POST['quantity'];
                    updateCartQuantity($product_id, $quantity);
                }
                break;
            case 'remove':
                if (isset($_POST['product_id'])) {
                    $product_id = (int)$_POST['product_id'];
                    removeFromCart($product_id);
                }
                break;
            case 'clear':
                clearCart();
                break;
        }
    }
}

// Get cart items
$cart_items = getCartItems();
$cart_total = getCartTotal();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - KnightX</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/cart.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="dark-theme">
    <?php include 'includes/header.php'; ?>

    <main class="container">
        <div class="cart-page">
            <div class="cart-header">
                <h1 class="page-title">Your Shopping Cart</h1>
                <p class="cart-subtitle"><?php echo count($cart_items); ?> items in your cart</p>
            </div>

            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success">
                    <?php 
                    echo $_SESSION['success_message'];
                    unset($_SESSION['success_message']);
                    ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger">
                    <?php 
                    echo $_SESSION['error_message'];
                    unset($_SESSION['error_message']);
                    ?>
                </div>
            <?php endif; ?>

            <?php if (empty($cart_items)): ?>
                <div class="empty-cart">
                    <div class="empty-cart-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <h2>Your cart is empty</h2>
                    <p>Looks like you haven't added any items to your cart yet.</p>
                    <a href="products.php" class="btn btn-primary">
                        <i class="fas fa-shopping-bag"></i> Continue Shopping
                    </a>
                </div>
            <?php else: ?>
                <div class="cart-content">
                    <div class="cart-items">
                        <div class="cart-items-header">
                            <div class="header-product">Product</div>
                            <div class="header-price">Price</div>
                            <div class="header-quantity">Quantity</div>
                            <div class="header-total">Total</div>
                            <div class="header-actions"></div>
                        </div>
                        <?php foreach ($cart_items as $item): ?>
                            <div class="cart-item" data-product-id="<?php echo $item['product']['id']; ?>">
                                <div class="item-product">
                                    <div class="item-image">
                                        <img src="assets/images/products/<?php echo htmlspecialchars($item['product']['image']); ?>" 
                                             alt="<?php echo htmlspecialchars($item['product']['name']); ?>">
                                    </div>
                                    <div class="item-details">
                                        <h3><?php echo htmlspecialchars($item['product']['name']); ?></h3>
                                        <p class="item-category">
                                            <?php echo htmlspecialchars($item['product']['category_name']); ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="item-price">
                                    <?php echo formatPrice($item['product']['price']); ?>
                                </div>
                                <div class="item-quantity">
                                    <form action="cart.php" method="POST" class="quantity-form">
                                        <input type="hidden" name="action" value="update">
                                        <input type="hidden" name="product_id" value="<?php echo $item['product']['id']; ?>">
                                        <button type="button" class="quantity-btn minus">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" 
                                               min="1" max="<?php echo $item['product']['stock']; ?>" class="quantity-input">
                                        <button type="button" class="quantity-btn plus">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </form>
                                </div>
                                <div class="item-total">
                                    <?php echo formatPrice($item['product']['price'] * $item['quantity']); ?>
                                </div>
                                <div class="item-actions">
                                    <form action="cart.php" method="POST" class="remove-form">
                                        <input type="hidden" name="action" value="remove">
                                        <input type="hidden" name="product_id" value="<?php echo $item['product']['id']; ?>">
                                        <button type="submit" class="remove-btn" title="Remove item">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="cart-summary">
                        <div class="summary-header">
                            <h2>Order Summary</h2>
                        </div>
                        <div class="summary-content">
                            <div class="summary-row">
                                <span>Subtotal</span>
                                <span><?php echo formatPrice($cart_total); ?></span>
                            </div>
                            <div class="summary-row">
                                <span>Shipping</span>
                                <span>Calculated at checkout</span>
                            </div>
                            <div class="summary-row total">
                                <span>Total</span>
                                <span><?php echo formatPrice($cart_total); ?></span>
                            </div>
                            <div class="cart-actions">
                                <form action="cart.php" method="POST" class="clear-cart-form">
                                    <input type="hidden" name="action" value="clear">
                                    <button type="submit" class="btn btn-secondary">
                                        <i class="fas fa-trash"></i> Clear Cart
                                    </button>
                                </form>
                                <a href="checkout.php" class="btn btn-primary">
                                    <i class="fas fa-lock"></i> Proceed to Checkout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle quantity buttons
        const quantityForms = document.querySelectorAll('.quantity-form');
        quantityForms.forEach(form => {
            const minusBtn = form.querySelector('.minus');
            const plusBtn = form.querySelector('.plus');
            const input = form.querySelector('.quantity-input');

            minusBtn.addEventListener('click', () => {
                if (input.value > 1) {
                    input.value = parseInt(input.value) - 1;
                    form.submit();
                }
            });

            plusBtn.addEventListener('click', () => {
                if (input.value < parseInt(input.max)) {
                    input.value = parseInt(input.value) + 1;
                    form.submit();
                }
            });

            input.addEventListener('change', () => {
                form.submit();
            });
        });

        // Add animation to cart items
        const cartItems = document.querySelectorAll('.cart-item');
        cartItems.forEach((item, index) => {
            item.style.animationDelay = `${index * 0.1}s`;
            item.classList.add('fade-in');
        });
    });
    </script>
</body>
</html> 