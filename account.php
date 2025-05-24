<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

// Get user information
$user_id = $_SESSION['user_id'];
$user = getUserById($user_id);

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'update_profile':
                $data = [
                    'first_name' => sanitizeInput($_POST['first_name']),
                    'last_name' => sanitizeInput($_POST['last_name']),
                    'email' => sanitizeInput($_POST['email']),
                    'phone' => sanitizeInput($_POST['phone'])
                ];
                
                if (updateUserProfile($user_id, $data)) {
                    $_SESSION['success_message'] = "Profile updated successfully!";
                } else {
                    $_SESSION['error_message'] = "Failed to update profile.";
                }
                break;

            case 'update_address':
                $data = [
                    'address' => sanitizeInput($_POST['address']),
                    'city' => sanitizeInput($_POST['city']),
                    'state' => sanitizeInput($_POST['state']),
                    'postal_code' => sanitizeInput($_POST['postal_code']),
                    'country' => sanitizeInput($_POST['country'])
                ];
                
                if (updateUserAddress($user_id, $data)) {
                    $_SESSION['success_message'] = "Address updated successfully!";
                } else {
                    $_SESSION['error_message'] = "Failed to update address.";
                }
                break;

            case 'change_password':
                $current_password = $_POST['current_password'];
                $new_password = $_POST['new_password'];
                $confirm_password = $_POST['confirm_password'];

                if ($new_password !== $confirm_password) {
                    $_SESSION['error_message'] = "New passwords do not match.";
                } elseif (!verifyPassword($current_password, $user['password'])) {
                    $_SESSION['error_message'] = "Current password is incorrect.";
                } else {
                    if (updateUserPassword($user_id, $new_password)) {
                        $_SESSION['success_message'] = "Password updated successfully!";
                    } else {
                        $_SESSION['error_message'] = "Failed to update password.";
                    }
                }
                break;
        }
        
        // Redirect to prevent form resubmission
        header('Location: account.php');
        exit;
    }
}

// Get user's order history
$orders = getUserOrders($user_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account - KnightX</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/account.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="dark-theme">
    <?php include 'includes/header.php'; ?>

    <main class="container">
        <div class="account-page">
            <div class="account-header">
                <h1 class="page-title">My Account</h1>
                <p class="welcome-message">Welcome back, <?php echo htmlspecialchars($user['first_name']); ?>!</p>
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

            <div class="account-content">
                <div class="account-sidebar">
                    <nav class="account-nav">
                        <button class="nav-item active" data-tab="profile">
                            <i class="fas fa-user"></i> Profile Information
                        </button>
                        <button class="nav-item" data-tab="address">
                            <i class="fas fa-map-marker-alt"></i> Shipping Address
                        </button>
                        <button class="nav-item" data-tab="orders">
                            <i class="fas fa-shopping-bag"></i> Order History
                        </button>
                        <button class="nav-item" data-tab="security">
                            <i class="fas fa-lock"></i> Security
                        </button>
                    </nav>
                </div>

                <div class="account-main">
                    <!-- Profile Information Tab -->
                    <div class="tab-content active" id="profile">
                        <div class="tab-header">
                            <h2>Profile Information</h2>
                            <p>Update your personal information</p>
                        </div>
                        <form action="account.php" method="POST" class="account-form">
                            <input type="hidden" name="action" value="update_profile">
                            
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

                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="tel" id="phone" name="phone" 
                                       value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                        </form>
                    </div>

                    <!-- Shipping Address Tab -->
                    <div class="tab-content" id="address">
                        <div class="tab-header">
                            <h2>Shipping Address</h2>
                            <p>Update your shipping information</p>
                        </div>
                        <form action="account.php" method="POST" class="account-form">
                            <input type="hidden" name="action" value="update_address">
                            
                            <div class="form-group">
                                <label for="address">Street Address</label>
                                <input type="text" id="address" name="address" 
                                       value="<?php echo htmlspecialchars($user['address'] ?? ''); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="city">City</label>
                                <input type="text" id="city" name="city" 
                                       value="<?php echo htmlspecialchars($user['city'] ?? ''); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="state">State/Province</label>
                                <input type="text" id="state" name="state" 
                                       value="<?php echo htmlspecialchars($user['state'] ?? ''); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="postal_code">Postal Code</label>
                                <input type="text" id="postal_code" name="postal_code" 
                                       value="<?php echo htmlspecialchars($user['postal_code'] ?? ''); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="country">Country</label>
                                <select id="country" name="country" required>
                                    <option value="">Select Country</option>
                                    <option value="US" <?php echo ($user['country'] ?? '') === 'US' ? 'selected' : ''; ?>>United States</option>
                                    <option value="CA" <?php echo ($user['country'] ?? '') === 'CA' ? 'selected' : ''; ?>>Canada</option>
                                    <option value="UK" <?php echo ($user['country'] ?? '') === 'UK' ? 'selected' : ''; ?>>United Kingdom</option>
                                    <!-- Add more countries as needed -->
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                        </form>
                    </div>

                    <!-- Order History Tab -->
                    <div class="tab-content" id="orders">
                        <div class="tab-header">
                            <h2>Order History</h2>
                            <p>View your past orders</p>
                        </div>
                        
                        <?php if (empty($orders)): ?>
                            <div class="empty-state">
                                <i class="fas fa-shopping-bag"></i>
                                <h3>No Orders Yet</h3>
                                <p>You haven't placed any orders yet.</p>
                                <a href="products.php" class="btn btn-primary">Start Shopping</a>
                            </div>
                        <?php else: ?>
                            <div class="orders-list">
                                <?php foreach ($orders as $order): ?>
                                    <div class="order-card">
                                        <div class="order-header">
                                            <div class="order-info">
                                                <h3>Order #<?php echo $order['id']; ?></h3>
                                                <p class="order-date">
                                                    <?php echo date('F j, Y', strtotime($order['created_at'])); ?>
                                                </p>
                                            </div>
                                            <div class="order-status <?php echo strtolower($order['status']); ?>">
                                                <?php echo $order['status']; ?>
                                            </div>
                                        </div>
                                        <div class="order-details">
                                            <div class="order-items">
                                                <?php foreach ($order['items'] as $item): ?>
                                                    <div class="order-item">
                                                        <img src="assets/images/products/<?php echo htmlspecialchars($item['image']); ?>" 
                                                             alt="<?php echo htmlspecialchars($item['name']); ?>">
                                                        <div class="item-details">
                                                            <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                                                            <p>Quantity: <?php echo $item['quantity']; ?></p>
                                                            <p class="item-price">
                                                                <?php echo formatPrice($item['price'] * $item['quantity']); ?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                            <div class="order-total">
                                                <span>Total:</span>
                                                <span><?php echo formatPrice($order['total']); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Security Tab -->
                    <div class="tab-content" id="security">
                        <div class="tab-header">
                            <h2>Security</h2>
                            <p>Update your password</p>
                        </div>
                        <form action="account.php" method="POST" class="account-form">
                            <input type="hidden" name="action" value="change_password">
                            
                            <div class="form-group">
                                <label for="current_password">Current Password</label>
                                <input type="password" id="current_password" name="current_password" required>
                            </div>

                            <div class="form-group">
                                <label for="new_password">New Password</label>
                                <input type="password" id="new_password" name="new_password" required>
                            </div>

                            <div class="form-group">
                                <label for="confirm_password">Confirm New Password</label>
                                <input type="password" id="confirm_password" name="confirm_password" required>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-key"></i> Update Password
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tab switching functionality
        const tabButtons = document.querySelectorAll('.nav-item');
        const tabContents = document.querySelectorAll('.tab-content');

        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Remove active class from all buttons and contents
                tabButtons.forEach(btn => btn.classList.remove('active'));
                tabContents.forEach(content => content.classList.remove('active'));

                // Add active class to clicked button and corresponding content
                button.classList.add('active');
                const tabId = button.getAttribute('data-tab');
                document.getElementById(tabId).classList.add('active');
            });
        });

        // Form validation
        const forms = document.querySelectorAll('.account-form');
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!form.checkValidity()) {
                    e.preventDefault();
                    // Add your custom validation logic here
                }
            });
        });
    });
    </script>
</body>
</html> 