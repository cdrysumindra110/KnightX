<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

// Get category filter
$category_slug = isset($_GET['category']) ? sanitizeInput($_GET['category']) : null;
$category = $category_slug ? getCategoryBySlug($category_slug) : null;

// Get sorting option
$sort = isset($_GET['sort']) ? sanitizeInput($_GET['sort']) : 'newest';
$sort_options = [
    'newest' => 'Newest First',
    'price_low' => 'Price: Low to High',
    'price_high' => 'Price: High to Low',
    'rating' => 'Highest Rated'
];

// Get pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 12;
$offset = ($page - 1) * $per_page;

// Get products based on filters
$products = [];
$total_products = 0;

if ($category) {
    $products = getProductsByCategory($category['id'], $per_page, $offset);
    $total_products = count(getProductsByCategory($category['id'], PHP_INT_MAX, 0));
} else {
    $products = getFeaturedProducts($per_page);
    $total_products = count(getFeaturedProducts(PHP_INT_MAX));
}

// Get all categories for filter
$categories = getCategories();

// Calculate total pages
$total_pages = ceil($total_products / $per_page);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $category ? $category['name'] . ' - ' : ''; ?>Products - KnightX</title>
    <meta name="description" content="Browse our collection of gaming electronics and accessories at KnightX. <?php echo $category ? $category['description'] : 'Find the perfect gaming gear for your setup.'; ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/images/favicon.png">
    
    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="dark-theme">
    <!-- Header -->
    <?php include 'includes/header.php'; ?>

    <main class="container">
        <div class="products-header">
            <h1><?php echo $category ? $category['name'] : 'All Products'; ?></h1>
            
            <div class="products-filters">
                <div class="category-filter">
                    <label for="category">Category:</label>
                    <select id="category" onchange="window.location.href=this.value">
                        <option value="products.php">All Categories</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="products.php?category=<?php echo $cat['slug']; ?>" 
                                    <?php echo $category && $category['id'] == $cat['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['name']); ?> 
                                (<?php echo $cat['product_count']; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="sort-filter">
                    <label for="sort">Sort by:</label>
                    <select id="sort" onchange="window.location.href=this.value">
                        <?php foreach ($sort_options as $value => $label): ?>
                            <option value="?sort=<?php echo $value; ?><?php echo $category ? '&category=' . $category['slug'] : ''; ?>" 
                                    <?php echo $sort === $value ? 'selected' : ''; ?>>
                                <?php echo $label; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <?php if (empty($products)): ?>
            <div class="no-products">
                <p>No products found in this category.</p>
            </div>
        <?php else: ?>
            <div class="products-grid">
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <div class="product-image">
                            <img src="assets/images/products/<?php echo $product['image']; ?>" 
                                 alt="<?php echo htmlspecialchars($product['name']); ?>">
                            <?php if ($product['sale_price']): ?>
                                <span class="sale-badge">Sale</span>
                            <?php endif; ?>
                        </div>
                        <div class="product-info">
                            <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                            <div class="product-category">
                                <a href="products.php?category=<?php echo $product['category_slug']; ?>">
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
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="?page=<?php echo $i; ?><?php echo $category ? '&category=' . $category['slug'] : ''; ?>" 
                           class="<?php echo $page === $i ? 'active' : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </main>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script>
        // Filter and sort functionality
        document.getElementById('category').addEventListener('change', function() {
            const category = this.value;
            const sort = document.getElementById('sort').value;
            window.location.href = `products.php?category=${category}&sort=${sort}`;
        });

        document.getElementById('sort').addEventListener('change', function() {
            const sort = this.value;
            const category = document.getElementById('category').value;
            window.location.href = `products.php?category=${category}&sort=${sort}`;
        });
    </script>
</body>
</html> 