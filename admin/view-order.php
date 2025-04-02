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

// Get order ID from URL
$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get order details with user information
$query = "SELECT o.*, u.username, u.email, u.first_name, u.last_name, u.phone, u.address, u.city, u.state, u.country, u.postal_code 
          FROM orders o 
          JOIN users u ON o.user_id = u.id 
          WHERE o.id = :id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':id', $order_id);
$stmt->execute();
$order = $stmt->fetch();

if (!$order) {
    header('Location: orders.php');
    exit;
}

// Get order items
$query = "SELECT oi.*, p.name as product_name, p.image 
          FROM order_items oi 
          JOIN products p ON oi.product_id = p.id 
          WHERE oi.order_id = :order_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':order_id', $order_id);
$stmt->execute();
$order_items = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Order #<?php echo $order_id; ?> - KnightX Admin</title>
    
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
                        <a href="orders.php" class="active">
                            <i class="fas fa-shopping-cart"></i> Orders
                        </a>
                    </li>
                    <li>
                        <a href="users.php">
                            <i class="fas fa-users"></i> Users
                        </a>
                    </li>
                    <li>
                        <a href="settings.php">
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
                <h1>Order #<?php echo $order_id; ?></h1>
                <div class="admin-header-actions">
                    <a href="print-order.php?id=<?php echo $order_id; ?>" class="admin-btn admin-btn-secondary" target="_blank">
                        <i class="fas fa-print"></i> Print Order
                    </a>
                    <a href="orders.php" class="admin-btn admin-btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Orders
                    </a>
                </div>
            </header>

            <?php echo $message; ?>

            <!-- Order Details -->
            <div class="admin-card">
                <div class="order-details">
                    <div class="order-section">
                        <h2>Order Information</h2>
                        <div class="info-grid">
                            <div class="info-item">
                                <label>Order Date</label>
                                <span><?php echo date('M d, Y H:i', strtotime($order['created_at'])); ?></span>
                            </div>
                            <div class="info-item">
                                <label>Order Status</label>
                                <span class="status-badge status-<?php echo strtolower($order['status']); ?>">
                                    <?php echo ucfirst($order['status']); ?>
                                </span>
                            </div>
                            <div class="info-item">
                                <label>Payment Method</label>
                                <span><?php echo ucfirst($order['payment_method']); ?></span>
                            </div>
                            <div class="info-item">
                                <label>Payment Status</label>
                                <span class="status-badge status-<?php echo strtolower($order['payment_status']); ?>">
                                    <?php echo ucfirst($order['payment_status']); ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="order-section">
                        <h2>Customer Information</h2>
                        <div class="info-grid">
                            <div class="info-item">
                                <label>Name</label>
                                <span><?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?></span>
                            </div>
                            <div class="info-item">
                                <label>Email</label>
                                <span><?php echo htmlspecialchars($order['email']); ?></span>
                            </div>
                            <div class="info-item">
                                <label>Phone</label>
                                <span><?php echo htmlspecialchars($order['phone']); ?></span>
                            </div>
                            <div class="info-item">
                                <label>Address</label>
                                <span>
                                    <?php echo htmlspecialchars($order['address']); ?><br>
                                    <?php echo htmlspecialchars($order['city'] . ', ' . $order['state'] . ' ' . $order['postal_code']); ?><br>
                                    <?php echo htmlspecialchars($order['country']); ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="order-section">
                        <h2>Order Items</h2>
                        <div class="table-responsive">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($order_items as $item): ?>
                                    <tr>
                                        <td>
                                            <div class="product-info">
                                                <img src="../assets/images/products/<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>">
                                                <span><?php echo htmlspecialchars($item['product_name']); ?></span>
                                            </div>
                                        </td>
                                        <td>$<?php echo number_format($item['price'], 2); ?></td>
                                        <td><?php echo $item['quantity']; ?></td>
                                        <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-right"><strong>Subtotal:</strong></td>
                                        <td>$<?php echo number_format($order['subtotal'], 2); ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-right"><strong>Shipping:</strong></td>
                                        <td>$<?php echo number_format($order['shipping_cost'], 2); ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-right"><strong>Tax:</strong></td>
                                        <td>$<?php echo number_format($order['tax'], 2); ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-right"><strong>Total:</strong></td>
                                        <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- JavaScript -->
    <script src="../assets/js/main.js"></script>
</body>
</html> 