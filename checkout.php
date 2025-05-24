<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
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
$user_id = $_SESSION['user_id'];
$user = getUser($user_id);
if (!$user) {
    session_destroy();
    header('Location: login.php');
    exit;
}

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
        /* Checkout Page Styles */
        .checkout-page {
            padding: 40px 0;
        }

        .checkout-page h1 {
            font-size: 2.5rem;
            color: var(--text-color);
            margin-bottom: 30px;
            text-align: center;
            position: relative;
            padding-bottom: 15px;
        }

        .checkout-page h1::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: var(--primary-color);
            border-radius: 2px;
        }

        .checkout-grid {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .checkout-section {
            background: var(--background-light);
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        }

        .checkout-section h2 {
            font-size: 1.5rem;
            color: var(--text-color);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--border-color);
        }

        /* Form Styles */
        .checkout-form .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .checkout-form .form-group {
            margin-bottom: 20px;
        }

        .checkout-form label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-color);
            font-weight: 500;
        }

        .checkout-form input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: var(--background-dark);
            color: var(--text-color);
            transition: all 0.3s ease;
        }

        .checkout-form input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(0, 255, 136, 0.1);
            outline: none;
        }

        /* Payment Methods */
        .payment-methods {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid var(--border-color);
        }

        .payment-options {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 15px;
        }

        .payment-option {
            position: relative;
            display: flex;
            align-items: center;
            padding: 15px;
            border: 2px solid var(--border-color);
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .payment-option:hover {
            border-color: var(--primary-color);
            transform: translateY(-2px);
        }

        .payment-option input[type="radio"] {
            position: absolute;
            opacity: 0;
        }

        .payment-option input[type="radio"]:checked + .payment-icon {
            border-color: var(--primary-color);
            background: rgba(0, 255, 136, 0.1);
        }

        .payment-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .payment-icon img {
            height: 30px;
            width: auto;
            object-fit: contain;
        }

        .payment-label {
            margin-left: 10px;
            font-weight: 500;
            color: var(--text-color);
        }

        /* Order Summary */
        .order-summary {
            background: var(--background-dark);
            border-radius: 10px;
            padding: 20px;
        }

        .summary-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 0;
            border-bottom: 1px solid var(--border-color);
        }

        .item-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .item-info img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }

        .item-details h3 {
            font-size: 1rem;
            margin-bottom: 5px;
            color: var(--text-color);
        }

        .item-details p {
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        .item-price {
            font-weight: 600;
            color: var(--text-color);
        }

        .summary-totals {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid var(--border-color);
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            color: var(--text-color);
        }

        .summary-row.total {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid var(--border-color);
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--primary-color);
        }

        /* Place Order Button */
        .btn-primary.btn-large {
            width: 100%;
            padding: 15px;
            margin-top: 20px;
            font-size: 1.1rem;
            background: var(--primary-color);
            color: var(--background-dark);
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-primary.btn-large:hover {
            background: var(--primary-color-dark);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 255, 136, 0.2);
        }

        .btn-primary.btn-large i {
            font-size: 1.2rem;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .checkout-grid {
                grid-template-columns: 1fr;
            }
            
            .checkout-section {
                margin-bottom: 30px;
            }
        }

        @media (max-width: 768px) {
            .checkout-form .form-row {
                grid-template-columns: 1fr;
            }
            
            .payment-options {
                grid-template-columns: 1fr;
            }
            
            .checkout-page h1 {
                font-size: 2rem;
            }
        }

        /* Loading State */
        .btn-primary.btn-large.loading {
            position: relative;
            pointer-events: none;
            opacity: 0.8;
        }

        .btn-primary.btn-large.loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            border: 2px solid var(--background-dark);
            border-top-color: transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Error States */
        .form-group.error input {
            border-color: var(--error-color);
        }

        .form-group.error .error-message {
            color: var(--error-color);
            font-size: 0.9rem;
            margin-top: 5px;
        }

        /* Success States */
        .form-group.success input {
            border-color: var(--success-color);
        }

        /* Custom Checkbox Style */
        .payment-option input[type="radio"]:checked + .payment-icon::before {
            content: '✓';
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--primary-color);
            color: var(--background-dark);
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
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