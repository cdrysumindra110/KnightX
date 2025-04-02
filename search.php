<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

header('Content-Type: application/json');

if (!isset($_GET['q'])) {
    echo json_encode([]);
    exit;
}

$query = sanitizeInput($_GET['q']);
$products = searchProducts($query);

// Format product data for JSON response
$formatted_products = array_map(function($product) {
    return [
        'id' => $product['id'],
        'name' => $product['name'],
        'price' => formatPrice($product['price']),
        'image' => $product['image'],
        'category' => $product['category_name'],
        'category_slug' => $product['category_slug']
    ];
}, $products);

echo json_encode($formatted_products); 