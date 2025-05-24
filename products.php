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

// Get price filters
$min_price = isset($_GET['min_price']) ? (float)$_GET['min_price'] : null;
$max_price = isset($_GET['max_price']) ? (float)$_GET['max_price'] : null;

// Build base query
$conn = getDBConnection();
$where = [];
$params = [];
$order = 'p.created_at DESC';

if ($category) {
    $where[] = 'p.category_id = :category_id';
    $params[':category_id'] = $category['id'];
}
if ($min_price !== null && $min_price >= 0) {
    $where[] = '(p.sale_price IS NOT NULL AND p.sale_price >= :min_price OR p.sale_price IS NULL AND p.price >= :min_price)';
    $params[':min_price'] = $min_price;
}
if ($max_price !== null && $max_price > 0) {
    $where[] = '(p.sale_price IS NOT NULL AND p.sale_price <= :max_price OR p.sale_price IS NULL AND p.price <= :max_price)';
    $params[':max_price'] = $max_price;
}
switch ($sort) {
    case 'price_low':
        $order = 'COALESCE(p.sale_price, p.price) ASC';
        break;
    case 'price_high':
        $order = 'COALESCE(p.sale_price, p.price) DESC';
        break;
    case 'rating':
        $order = 'p.rating DESC, p.review_count DESC';
        break;
    default:
        $order = 'p.created_at DESC';
}

$where_sql = $where ? 'WHERE ' . implode(' AND ', $where) . ' AND p.status = "active"' : 'WHERE p.status = "active"';
$query = "SELECT p.*, c.name as category_name, c.slug as category_slug 
          FROM products p 
          LEFT JOIN categories c ON p.category_id = c.id 
          $where_sql
          ORDER BY $order
          LIMIT :limit OFFSET :offset";
$stmt = $conn->prepare($query);
foreach ($params as $key => $val) {
    $stmt->bindValue($key, $val);
}
$stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$products = $stmt->fetchAll();

// For total count (for pagination)
$count_query = "SELECT COUNT(*) FROM products p $where_sql";
$count_stmt = $conn->prepare($count_query);
foreach ($params as $key => $val) {
    $count_stmt->bindValue($key, $val);
}
$count_stmt->execute();
$total_products = $count_stmt->fetchColumn();

// Get all categories for filter
$categories = getCategories();

// Calculate total pages
$total_pages = ceil($total_products / $per_page);

// Add to Cart functionality
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
    
    // Validate quantity
    if ($quantity < 1) {
        $_SESSION['error_message'] = "Invalid quantity.";
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }
    
    // Check if user is logged in
    if (!isLoggedIn()) {
        // Store the product ID in session for redirect after login
        $_SESSION['redirect_after_login'] = 'cart.php';
        $_SESSION['pending_cart_item'] = $product_id;
        $_SESSION['pending_cart_quantity'] = $quantity;
        
        // Redirect to login page
        header("Location: login.php");
        exit;
    }
    
    // User is logged in, add to cart
    if (addToCart($product_id, $quantity)) {
        $_SESSION['success_message'] = "Product added to cart successfully!";
        header("Location: cart.php");
    } else {
        $_SESSION['error_message'] = "Failed to add product to cart. Please check stock availability.";
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }
    exit;
}
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
    <link rel="stylesheet" href="assets/css/products.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

</head>
<body class="dark-theme">
    <!-- Header -->
    <?php include 'includes/header.php'; ?>

    <main class="container">
        <div class="products-header">
            <div class="filter-container">
                <form method="GET" class="products-filters">
                    <div class="filter-group">
                        <label for="category">Category:</label>
                        <select id="category" name="category" class="filter-select">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['slug']; ?>" 
                                        <?php echo $category && $category['id'] == $cat['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['name']); ?> 
                                    (<?php echo $cat['product_count']; ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="sort">Sort by:</label>
                        <select id="sort" name="sort" class="filter-select">
                            <?php foreach ($sort_options as $value => $label): ?>
                                <option value="<?php echo $value; ?>" 
                                        <?php echo $sort === $value ? 'selected' : ''; ?>>
                                    <?php echo $label; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="min_price">Min Price:</label>
                        <input type="number" name="min_price" id="min_price" 
                               value="<?php echo isset($_GET['min_price']) ? (int)$_GET['min_price'] : ''; ?>" 
                               min="0" class="filter-input">
                        
                        <label for="max_price">Max Price:</label>
                        <input type="number" name="max_price" id="max_price" 
                               value="<?php echo isset($_GET['max_price']) ? (int)$_GET['max_price'] : ''; ?>" 
                               min="0" class="filter-input">
                    </div>

                    <button type="submit" class="btn btn-primary filter-submit">Apply Filters</button>
                </form>
            </div>

            <div class="products-content">
                <h1><?php echo $category ? $category['name'] : 'All Products'; ?></h1>
                
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
                                        <button type="button" class="btn btn-success add-to-cart-btn" 
                                                data-product-id="<?php echo $product['id']; ?>"
                                                <?php echo $product['stock'] <= 0 ? 'disabled' : ''; ?>>
                                            <?php echo $product['stock'] <= 0 ? 'Out of Stock' : 'Add to Cart'; ?>
                                        </button>
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
            </div>
        </div>
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
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');
        
        addToCartButtons.forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                
                // Disable button while processing
                this.disabled = true;
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
                
                // Create form data
                const formData = new FormData();
                formData.append('product_id', productId);
                
                fetch('add_to_cart.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update cart count
                        const cartCount = document.querySelector('.cart-count');
                        if (cartCount) {
                            cartCount.textContent = data.cart_count;
                        }
                        
                        // Show success message
                        const notification = document.createElement('div');
                        notification.className = 'notification success';
                        notification.textContent = data.message;
                        document.body.appendChild(notification);
                        
                        // Add animation to cart icon
                        const cartIcon = document.querySelector('.cart-link');
                        cartIcon.classList.add('bounce');
                        setTimeout(() => cartIcon.classList.remove('bounce'), 1000);
                        
                        // Remove notification after 3 seconds
                        setTimeout(() => {
                            notification.remove();
                        }, 3000);
                    } else {
                        if (data.redirect) {
                            // Redirect to login page
                            window.location.href = data.redirect;
                        } else {
                            // Show error message
                            const notification = document.createElement('div');
                            notification.className = 'notification error';
                            notification.textContent = data.message;
                            document.body.appendChild(notification);
                            setTimeout(() => {
                                notification.remove();
                            }, 3000);
                        }
                    }
                })
                .catch(error => {
                    const notification = document.createElement('div');
                    notification.className = 'notification error';
                    notification.textContent = 'An error occurred. Please try again.';
                    document.body.appendChild(notification);
                    setTimeout(() => {
                        notification.remove();
                    }, 3000);
                })
                .finally(() => {
                    // Re-enable button
                    this.disabled = false;
                    this.innerHTML = 'Add to Cart';
                });
            });
        });
    });
    </script>
</body>
</html> 