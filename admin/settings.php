<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$conn = getDBConnection();
$message = '';

// Get current settings
$query = "SELECT * FROM settings";
$stmt = $conn->query($query);
$settings = [];
while ($row = $stmt->fetch()) {
    $settings[$row['key']] = $row['value'];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $success = true;
    
    // Update general settings
    $general_settings = [
        'site_name' => sanitizeInput($_POST['site_name']),
        'site_description' => sanitizeInput($_POST['site_description']),
        'contact_email' => sanitizeInput($_POST['contact_email']),
        'contact_phone' => sanitizeInput($_POST['contact_phone']),
        'address' => sanitizeInput($_POST['address']),
        'city' => sanitizeInput($_POST['city']),
        'state' => sanitizeInput($_POST['state']),
        'country' => sanitizeInput($_POST['country']),
        'postal_code' => sanitizeInput($_POST['postal_code'])
    ];
    
    foreach ($general_settings as $key => $value) {
        if (!updateSetting($key, $value)) {
            $success = false;
            break;
        }
    }
    
    // Update shipping settings
    $shipping_settings = [
        'shipping_cost' => (float)$_POST['shipping_cost'],
        'free_shipping_threshold' => (float)$_POST['free_shipping_threshold']
    ];
    
    foreach ($shipping_settings as $key => $value) {
        if (!updateSetting($key, $value)) {
            $success = false;
            break;
        }
    }
    
    // Update tax settings
    $tax_settings = [
        'tax_rate' => (float)$_POST['tax_rate'],
        'tax_enabled' => isset($_POST['tax_enabled']) ? 1 : 0
    ];
    
    foreach ($tax_settings as $key => $value) {
        if (!updateSetting($key, $value)) {
            $success = false;
            break;
        }
    }
    
    // Update payment settings
    $payment_settings = [
        'stripe_public_key' => sanitizeInput($_POST['stripe_public_key']),
        'stripe_secret_key' => sanitizeInput($_POST['stripe_secret_key']),
        'paypal_client_id' => sanitizeInput($_POST['paypal_client_id']),
        'paypal_secret' => sanitizeInput($_POST['paypal_secret']),
        'paypal_mode' => sanitizeInput($_POST['paypal_mode'])
    ];
    
    foreach ($payment_settings as $key => $value) {
        if (!updateSetting($key, $value)) {
            $success = false;
            break;
        }
    }
    
    if ($success) {
        $message = '<div class="alert alert-success">Settings updated successfully!</div>';
        // Refresh settings
        $query = "SELECT * FROM settings";
        $stmt = $conn->query($query);
        $settings = [];
        while ($row = $stmt->fetch()) {
            $settings[$row['key']] = $row['value'];
        }
    } else {
        $message = '<div class="alert alert-danger">Error updating settings.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - KnightX Admin</title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="admin-sidebar-header">
                <img src="../assets/images/logo.png" alt="KnightX Logo" class="admin-logo">
                <h2>Admin Panel</h2>
            </div>
            
            <nav class="admin-nav">
                <ul>
                    <li>
                        <a href="index.php">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="products.php">
                            <i class="fas fa-box"></i> Products
                        </a>
                    </li>
                    <li>
                        <a href="categories.php">
                            <i class="fas fa-tags"></i> Categories
                        </a>
                    </li>
                    <li>
                        <a href="orders.php">
                            <i class="fas fa-shopping-cart"></i> Orders
                        </a>
                    </li>
                    <li>
                        <a href="users.php">
                            <i class="fas fa-users"></i> Users
                        </a>
                    </li>
                    <li>
                        <a href="settings.php" class="active">
                            <i class="fas fa-cog"></i> Settings
                        </a>
                    </li>
                    <li>
                        <a href="logout.php">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="admin-content">
            <header class="admin-header">
                <h1>Settings</h1>
            </header>

            <?php echo $message; ?>

            <!-- Settings Form -->
            <div class="admin-card">
                <form method="POST" class="admin-form">
                    <!-- General Settings -->
                    <div class="settings-section">
                        <h2>General Settings</h2>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="site_name">Site Name</label>
                                <input type="text" id="site_name" name="site_name" value="<?php echo htmlspecialchars($settings['site_name'] ?? ''); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="site_description">Site Description</label>
                                <input type="text" id="site_description" name="site_description" value="<?php echo htmlspecialchars($settings['site_description'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="contact_email">Contact Email</label>
                                <input type="email" id="contact_email" name="contact_email" value="<?php echo htmlspecialchars($settings['contact_email'] ?? ''); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="contact_phone">Contact Phone</label>
                                <input type="tel" id="contact_phone" name="contact_phone" value="<?php echo htmlspecialchars($settings['contact_phone'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="address">Address</label>
                                <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($settings['address'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="city">City</label>
                                <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($settings['city'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="state">State</label>
                                <input type="text" id="state" name="state" value="<?php echo htmlspecialchars($settings['state'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="country">Country</label>
                                <input type="text" id="country" name="country" value="<?php echo htmlspecialchars($settings['country'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="postal_code">Postal Code</label>
                            <input type="text" id="postal_code" name="postal_code" value="<?php echo htmlspecialchars($settings['postal_code'] ?? ''); ?>">
                        </div>
                    </div>

                    <!-- Shipping Settings -->
                    <div class="settings-section">
                        <h2>Shipping Settings</h2>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="shipping_cost">Shipping Cost</label>
                                <input type="number" id="shipping_cost" name="shipping_cost" step="0.01" value="<?php echo htmlspecialchars($settings['shipping_cost'] ?? '0.00'); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="free_shipping_threshold">Free Shipping Threshold</label>
                                <input type="number" id="free_shipping_threshold" name="free_shipping_threshold" step="0.01" value="<?php echo htmlspecialchars($settings['free_shipping_threshold'] ?? '0.00'); ?>" required>
                            </div>
                        </div>
                    </div>

                    <!-- Tax Settings -->
                    <div class="settings-section">
                        <h2>Tax Settings</h2>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="tax_rate">Tax Rate (%)</label>
                                <input type="number" id="tax_rate" name="tax_rate" step="0.01" value="<?php echo htmlspecialchars($settings['tax_rate'] ?? '0.00'); ?>" required>
                            </div>
                            <div class="form-group">
                                <label class="checkbox-label">
                                    <input type="checkbox" id="tax_enabled" name="tax_enabled" <?php echo ($settings['tax_enabled'] ?? '0') == '1' ? 'checked' : ''; ?>>
                                    Enable Tax
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Settings -->
                    <div class="settings-section">
                        <h2>Payment Settings</h2>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="stripe_public_key">Stripe Public Key</label>
                                <input type="text" id="stripe_public_key" name="stripe_public_key" value="<?php echo htmlspecialchars($settings['stripe_public_key'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="stripe_secret_key">Stripe Secret Key</label>
                                <input type="password" id="stripe_secret_key" name="stripe_secret_key" value="<?php echo htmlspecialchars($settings['stripe_secret_key'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="paypal_client_id">PayPal Client ID</label>
                                <input type="text" id="paypal_client_id" name="paypal_client_id" value="<?php echo htmlspecialchars($settings['paypal_client_id'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="paypal_secret">PayPal Secret</label>
                                <input type="password" id="paypal_secret" name="paypal_secret" value="<?php echo htmlspecialchars($settings['paypal_secret'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="paypal_mode">PayPal Mode</label>
                            <select id="paypal_mode" name="paypal_mode">
                                <option value="sandbox" <?php echo ($settings['paypal_mode'] ?? '') === 'sandbox' ? 'selected' : ''; ?>>Sandbox</option>
                                <option value="live" <?php echo ($settings['paypal_mode'] ?? '') === 'live' ? 'selected' : ''; ?>>Live</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="admin-btn admin-btn-primary">
                            <i class="fas fa-save"></i> Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <!-- JavaScript -->
    <script src="../assets/js/main.js"></script>
</body>
</html> 