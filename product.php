<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

// Get product by slug
$slug = isset($_GET['slug']) ? sanitizeInput($_GET['slug']) : '';
$product = getProductBySlug($slug);

if (!$product) {
    header('Location: products.php');
    exit;
}

// Optionally, get reviews, related products, etc.
// $reviews = getProductReviews($product['id']);
// $related = getRelatedProducts($product['category_id'], $product['id']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($product['name']); ?> - KnightX</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php include 'includes/header.php'; ?>

<main class="container">
    <div class="product-details-page">
        <div class="product-details">
            <div class="product-gallery">
                <img src="assets/images/products/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
            </div>
            <div class="product-info">
                <h1><?php echo htmlspecialchars($product['name']); ?></h1>
                <div class="product-category">
                    Category: <?php echo htmlspecialchars($product['category_name']); ?>
                </div>
                <div class="product-price">
                    <?php if ($product['sale_price']): ?>
                        <span class="sale-price"><?php echo formatPrice($product['sale_price']); ?></span>
                        <span class="original-price"><?php echo formatPrice($product['price']); ?></span>
                    <?php else: ?>
                        <span class="price"><?php echo formatPrice($product['price']); ?></span>
                    <?php endif; ?>
                </div>
                <div class="product-description">
                    <?php echo nl2br(htmlspecialchars($product['description'])); ?>
                </div>
                <div class="product-stock">
                    <?php if ($product['stock'] > 0): ?>
                        <span class="in-stock">In Stock (<?php echo $product['stock']; ?>)</span>
                    <?php else: ?>
                        <span class="out-of-stock">Out of Stock</span>
                    <?php endif; ?>
                </div>
                <form action="cart.php" method="POST" class="add-to-cart-form">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <button type="submit" class="btn btn-success" <?php if ($product['stock'] <= 0) echo 'disabled'; ?>>
                        Add to Cart
                    </button>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
</body>
</html> 