<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

// Get category slug from URL
$slug = isset($_GET['slug']) ? sanitizeInput($_GET['slug']) : '';
$category = $slug ? getCategoryBySlug($slug) : false;

if (!$category) {
    // Invalid or missing category
    header('Location: products.php');
    exit;
}

$products = getProductsByCategory($category['id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($category['name']); ?> - Category | KnightX</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php include 'includes/header.php'; ?>

<main class="container">
    <div class="category-header" style="margin: 40px 0 30px;">
        <h1 style="font-size:2.2rem;"><?php echo htmlspecialchars($category['name']); ?></h1>
        <?php if (!empty($category['description'])): ?>
            <p style="color: var(--text-secondary); font-size:1.1rem;"><?php echo htmlspecialchars($category['description']); ?></p>
        <?php endif; ?>
    </div>

    <?php if (empty($products)): ?>
        <div class="empty-cart" style="text-align:center;">
            <i class="fas fa-box-open" style="font-size:48px;color:var(--text-secondary);"></i>
            <h2>No products found in this category.</h2>
            <a href="products.php" class="btn btn-primary" style="margin-top:20px;">Browse All Products</a>
        </div>
    <?php else: ?>
        <div class="products-grid">
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <div class="product-image">
                        <img src="assets/images/products/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <?php if ($product['sale_price']): ?>
                            <span class="sale-badge">Sale</span>
                        <?php endif; ?>
                    </div>
                    <div class="product-info">
                        <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                        <div class="product-category">
                            <a href="category.php?slug=<?php echo $product['category_slug']; ?>">
                                <?php echo htmlspecialchars($product['category_name']); ?>
                            </a>
                        </div>
                        <div class="product-price">
                            <?php if ($product['sale_price']): ?>
                                <span class="sale-price"><?php echo formatPrice($product['sale_price']); ?></span>
                                <span class="original-price"><?php echo formatPrice($product['price']); ?></span>
                            <?php else: ?>
                                <span class="price"><?php echo formatPrice($product['price']); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="product-rating">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star <?php echo $i <= $product['rating'] ? 'active' : ''; ?>"></i>
                            <?php endfor; ?>
                            <span class="review-count">(<?php echo $product['review_count']; ?>)</span>
                        </div>
                        <div class="product-actions">
                            <a href="product.php?slug=<?php echo $product['slug']; ?>" class="btn btn-primary">
                                View Details
                            </a>
                            <form action="cart.php" method="POST" style="margin-top: 8px;">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <button type="submit" class="btn btn-success">Add to Cart</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<?php include 'includes/footer.php'; ?>
</body>
</html> 