<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Get statistics
$conn = getDBConnection();

// Total products
$query = "SELECT COUNT(*) as total FROM products";
$stmt = $conn->query($query);
$totalProducts = $stmt->fetch()['total'];

// Total categories
$query = "SELECT COUNT(*) as total FROM categories";
$stmt = $conn->query($query);
$totalCategories = $stmt->fetch()['total'];

// Total orders
$query = "SELECT COUNT(*) as total FROM orders";
$stmt = $conn->query($query);
$totalOrders = $stmt->fetch()['total'];

// Recent orders
$query = "SELECT o.*, CONCAT(u.first_name, ' ', u.last_name) as customer_name 
          FROM orders o 
          JOIN users u ON o.user_id = u.id 
          ORDER BY o.created_at DESC 
          LIMIT 5";
$stmt = $conn->query($query);
$recentOrders = $stmt->fetchAll();

// Low stock products
$query = "SELECT * FROM products WHERE stock <= 5 ORDER BY stock ASC LIMIT 5";
$stmt = $conn->query($query);
$lowStockProducts = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - KnightX</title>
    
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
            
Fatal error: Uncaught Error: Call to undefined function getCategories() in C:\xampp\htdocs\KnightX\products.php:37 Stack trace: #0 {main} thrown in C:\xampp\htdocs\KnightX\products.php on line 37
            <nav class="admin-nav">
                <ul>
                    <li>
                        <a href="index.php" class="active">
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
                <h1>Dashboard</h1>
                <div class="admin-user-info">
                    <span>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                </div>
            </header>

            <!-- Statistics Cards -->
            <div class="admin-stats-grid">
                <div class="admin-card">
                    <div class="admin-stat-card">
                        <i class="fas fa-box"></i>
                        <div class="stat-info">
                            <h3>Total Products</h3>
                            <p><?php echo $totalProducts; ?></p>
                        </div>
                    </div>
                </div>

                <div class="admin-card">
                    <div class="admin-stat-card">
                        <i class="fas fa-tags"></i>
                        <div class="stat-info">
                            <h3>Categories</h3>
                            <p><?php echo $totalCategories; ?></p>
                        </div>
                    </div>
                </div>

                <div class="admin-card">
                    <div class="admin-stat-card">
                        <i class="fas fa-shopping-cart"></i>
                        <div class="stat-info">
                            <h3>Total Orders</h3>
                            <p><?php echo $totalOrders; ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="admin-card">
                <h2>Recent Orders</h2>
                <div class="admin-table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentOrders as $order): ?>
                            <tr>
                                <td>#<?php echo $order['order_number']; ?></td>
                                <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                                <td><?php echo formatPrice($order['total_amount']); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo strtolower($order['order_status']); ?>">
                                        <?php echo ucfirst($order['order_status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Low Stock Products -->
            <div class="admin-card">
                <h2>Low Stock Products</h2>
                <div class="admin-table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Stock</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lowStockProducts as $product): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($product['name']); ?></td>
                                <td><?php echo getCategoryName($product['category_id']); ?></td>
                                <td><?php echo $product['stock']; ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo strtolower($product['status']); ?>">
                                        <?php echo ucfirst($product['status']); ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- JavaScript -->
    <script src="../assets/js/main.js"></script>
</body>
</html> 