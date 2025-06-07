
<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: products.php');
    exit;
}

$product_id = (int)$_GET['id'];
$product = getProductById($product_id);

if (!$product) {
    header('Location: products.php');
    exit;
}

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// If product already in cart, increase quantity
if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] += 1;
} else {
    $_SESSION['cart'][$product_id] = [
        'id' => $product['id'],
        'name' => $product['name'],
        'image' => $product['image'],
        'price' => $product['sale_price'] ?? $product['price'],
        'quantity' => 1
    ];
}

header('Location: cart.php');
exit;
?>
