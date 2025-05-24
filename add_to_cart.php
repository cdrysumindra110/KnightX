<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);
    
    // Check if user is logged in
    if (!isLoggedIn()) {
        echo json_encode([
            'success' => false,
            'message' => 'Please login to add items to cart',
            'redirect' => 'login.php'
        ]);
        exit;
    }
    
    // Add to cart
    if (addToCart($product_id, 1)) {
        // Calculate new cart count
        $cart_count = 0;
        if (isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $qty) {
                $cart_count += $qty;
            }
        }
        
        echo json_encode([
            'success' => true,
            'message' => 'Product added to cart successfully!',
            'cart_count' => $cart_count
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to add product to cart. Please check stock availability.'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request'
    ]);
} 