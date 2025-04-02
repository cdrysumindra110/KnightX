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
    <title>Order Invoice #<?php echo $order_id; ?></title>
    
    <!-- CSS -->
    <style>
        @media print {
            body {
                font-family: Arial, sans-serif;
                line-height: 1.6;
                color: #333;
                margin: 0;
                padding: 20px;
            }
            
            .invoice-header {
                text-align: center;
                margin-bottom: 30px;
            }
            
            .invoice-header img {
                max-width: 200px;
                margin-bottom: 10px;
            }
            
            .invoice-title {
                font-size: 24px;
                font-weight: bold;
                margin-bottom: 5px;
            }
            
            .invoice-subtitle {
                font-size: 16px;
                color: #666;
            }
            
            .invoice-info {
                display: flex;
                justify-content: space-between;
                margin-bottom: 30px;
            }
            
            .invoice-info div {
                flex: 1;
            }
            
            .invoice-info h3 {
                margin: 0 0 10px 0;
                font-size: 18px;
            }
            
            .invoice-info p {
                margin: 0;
                color: #666;
            }
            
            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 30px;
            }
            
            th, td {
                padding: 10px;
                text-align: left;
                border-bottom: 1px solid #ddd;
            }
            
            th {
                background-color: #f5f5f5;
                font-weight: bold;
            }
            
            .product-info {
                display: flex;
                align-items: center;
            }
            
            .product-info img {
                width: 50px;
                height: 50px;
                object-fit: cover;
                margin-right: 10px;
            }
            
            .totals {
                width: 300px;
                margin-left: auto;
            }
            
            .totals tr:last-child {
                border-top: 2px solid #333;
                font-weight: bold;
            }
            
            .totals td {
                padding: 5px 10px;
            }
            
            .totals td:first-child {
                text-align: right;
            }
            
            .footer {
                text-align: center;
                margin-top: 50px;
                font-size: 14px;
                color: #666;
            }
            
            .no-print {
                display: none;
            }
        }
        
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        
        .print-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <button class="print-button no-print" onclick="window.print()">Print Invoice</button>
    
    <div class="invoice-header">
        <img src="../assets/images/logo.png" alt="KnightX Logo">
        <div class="invoice-title">KnightX</div>
        <div class="invoice-subtitle">Your Premium Gaming Destination</div>
    </div>
    
    <div class="invoice-info">
        <div>
            <h3>Order Information</h3>
            <p>Order #<?php echo $order_id; ?></p>
            <p>Date: <?php echo date('M d, Y', strtotime($order['created_at'])); ?></p>
            <p>Status: <?php echo ucfirst($order['status']); ?></p>
        </div>
        <div>
            <h3>Customer Information</h3>
            <p><?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?></p>
            <p><?php echo htmlspecialchars($order['email']); ?></p>
            <p><?php echo htmlspecialchars($order['phone']); ?></p>
            <p>
                <?php echo htmlspecialchars($order['address']); ?><br>
                <?php echo htmlspecialchars($order['city'] . ', ' . $order['state'] . ' ' . $order['postal_code']); ?><br>
                <?php echo htmlspecialchars($order['country']); ?>
            </p>
        </div>
    </div>
    
    <table>
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
    </table>
    
    <table class="totals">
        <tr>
            <td>Subtotal:</td>
            <td>$<?php echo number_format($order['subtotal'], 2); ?></td>
        </tr>
        <tr>
            <td>Shipping:</td>
            <td>$<?php echo number_format($order['shipping_cost'], 2); ?></td>
        </tr>
        <tr>
            <td>Tax:</td>
            <td>$<?php echo number_format($order['tax'], 2); ?></td>
        </tr>
        <tr>
            <td>Total:</td>
            <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
        </tr>
    </table>
    
    <div class="footer">
        <p>Thank you for shopping with KnightX!</p>
        <p>For any questions or concerns, please contact our customer support.</p>
        <p>© <?php echo date('Y'); ?> KnightX. All rights reserved.</p>
    </div>
</body>
</html> 