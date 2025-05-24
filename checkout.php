<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = 'checkout.php';
    header('Location: login.php');
    exit;
}

// Get cart items
$cart_items = getCartItems();
if (empty($cart_items)) {
    header('Location: cart.php');
    exit;
}

// Get user information
$user = getUser($_SESSION['user_id']);

// Calculate totals
$subtotal = getCartTotal();
$shipping_cost = calculateShippingCost($subtotal);
$tax_rate = getSetting('tax_rate');
$tax = $subtotal * ($tax_rate / 100);
$total = $subtotal + $shipping_cost + $tax;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payment_method = sanitizeInput($_POST['payment_method']);
    $shipping_address = [
        'first_name' => sanitizeInput($_POST['first_name']),
        'last_name' => sanitizeInput($_POST['last_name']),
        'email' => sanitizeInput($_POST['email']),
        'phone' => sanitizeInput($_POST['phone']),
        'address' => sanitizeInput($_POST['address']),
        'city' => sanitizeInput($_POST['city']),
        'state' => sanitizeInput($_POST['state']),
        'country' => sanitizeInput($_POST['country']),
        'postal_code' => sanitizeInput($_POST['postal_code'])
    ];

    // Create order
    $order_data = [
        'user_id' => $_SESSION['user_id'],
        'payment_method' => $payment_method,
        'shipping_address' => $shipping_address,
        'subtotal' => $subtotal,
        'shipping_cost' => $shipping_cost,
        'tax' => $tax,
        'total_amount' => $total
    ];

    $order_id = createOrder($order_data);
    
    if ($order_id) {
        // Process payment based on selected method
        if ($payment_method === 'esewa') {
            // Redirect to eSewa payment page
            $_SESSION['order_id'] = $order_id;
            header('Location: process-esewa.php');
            exit;
        } elseif ($payment_method === 'khalti') {
            // Redirect to Khalti payment page
            $_SESSION['order_id'] = $order_id;
            header('Location: process-khalti.php');
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - KnightX</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .payment-options {
            display: flex;
            gap: 20px;
            margin-top: 15px;
        }

        .payment-option {
            display: flex;
            align-items: center;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .payment-option:hover {
            border-color: var(--primary-color);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .payment-option input[type="radio"] {
            margin-right: 10px;
        }

        .payment-icon {
            margin-right: 10px;
        }

        .payment-icon img {
            height: 30px;
            width: auto;
        }

        .payment-label {
            font-weight: 500;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main class="container">
        <div class="checkout-page">
            <h1>Checkout</h1>

            <div class="checkout-grid">
                <!-- Shipping Information -->
                <div class="checkout-section">
                    <h2>Shipping Information</h2>
                    <form id="checkout-form" method="POST" class="checkout-form">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="first_name">First Name</label>
                                <input type="text" id="first_name" name="first_name" 
                                       value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="last_name">Last Name</label>
                                <input type="text" id="last_name" name="last_name" 
                                       value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input type="tel" id="phone" name="phone" 
                                       value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" id="address" name="address" 
                                   value="<?php echo htmlspecialchars($user['address']); ?>" required>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="city">City</label>
                                <input type="text" id="city" name="city" 
                                       value="<?php echo htmlspecialchars($user['city']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="state">State</label>
                                <input type="text" id="state" name="state" 
                                       value="<?php echo htmlspecialchars($user['state']); ?>" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="country">Country</label>
                                <input type="text" id="country" name="country" 
                                       value="<?php echo htmlspecialchars($user['country']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="postal_code">Postal Code</label>
                                <input type="text" id="postal_code" name="postal_code" 
                                       value="<?php echo htmlspecialchars($user['postal_code']); ?>" required>
                            </div>
                        </div>

                        <div class="payment-methods">
                            <h2>Payment Method</h2>
                            <div class="payment-options">
                                <label class="payment-option">
                                    <input type="radio" name="payment_method" value="esewa" checked>
                                    <span class="payment-icon">
                                        <img src="assets/images/payment/esewa.png" alt="eSewa">
                                    </span>
                                    <span class="payment-label">eSewa</span>
                                </label>
                                <label class="payment-option">
                                    <input type="radio" name="payment_method" value="khalti">
                                    <span class="payment-icon">
                                        <img src="assets/images/payment/khalti.png" alt="Khalti">
                                    </span>
                                    <span class="payment-label">Khalti</span>
                                </label>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Order Summary -->
                <div class="checkout-section">
                    <h2>Order Summary</h2>
                    <div class="order-summary">
                        <?php foreach ($cart_items as $item): ?>
                            <div class="summary-item">
                                <div class="item-info">
                                    <img src="assets/images/products/<?php echo htmlspecialchars($item['product']['image']); ?>" 
                                         alt="<?php echo htmlspecialchars($item['product']['name']); ?>">
                                    <div class="item-details">
                                        <h3><?php echo htmlspecialchars($item['product']['name']); ?></h3>
                                        <p>Quantity: <?php echo $item['quantity']; ?></p>
                                    </div>
                                </div>
                                <div class="item-price">
                                    <?php echo formatPrice($item['product']['price'] * $item['quantity']); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <div class="summary-totals">
                            <div class="summary-row">
                                <span>Subtotal</span>
                                <span><?php echo formatPrice($subtotal); ?></span>
                            </div>
                            <div class="summary-row">
                                <span>Shipping</span>
                                <span><?php echo formatPrice($shipping_cost); ?></span>
                            </div>
                            <div class="summary-row">
                                <span>Tax</span>
                                <span><?php echo formatPrice($tax); ?></span>
                            </div>
                            <div class="summary-row total">
                                <span>Total</span>
                                <span><?php echo formatPrice($total); ?></span>
                            </div>
                        </div>

                        <button type="submit" form="checkout-form" class="btn btn-primary btn-large">
                            <i class="fas fa-lock"></i> Place Order
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script src="assets/js/main.js"></script>
</body>
</html> 