<?php
// Database connection
function getDBConnection() {
    static $conn = null;
    if ($conn === null) {
        require_once __DIR__ . '/../config/database.php';
        $database = new Database();
        $conn = $database->getConnection();
    }
    return $conn;
}

/**
 * Sanitize user input
 * 
 * @param string $input The input to sanitize
 * @return string The sanitized input
 */
function sanitizeInput($input) {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}

// Generate random string
function generateRandomString($length = 10) {
    return bin2hex(random_bytes($length));
}

// Generate order number
function generateOrderNumber() {
    return 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
}

/**
 * Format price with currency symbol
 * 
 * @param float $price The price to format
 * @return string The formatted price
 */
function formatPrice($price) {
    return '$' . number_format($price, 2);
}

/**
 * Get product by ID
 * 
 * @param int $id The product ID
 * @return array|false The product data or false if not found
 */
function getProduct($id) {
    $conn = getDBConnection();
    $query = "SELECT p.*, c.name as category_name, c.slug as category_slug 
              FROM products p 
              LEFT JOIN categories c ON p.category_id = c.id 
              WHERE p.id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch();
}

/**
 * Get products by category
 * 
 * @param int $category_id The category ID
 * @param int $limit Optional limit of products to return
 * @return array Array of products
 */
function getProductsByCategory($category_id, $limit = null) {
    $conn = getDBConnection();
    $query = "SELECT p.*, c.name as category_name, c.slug as category_slug 
              FROM products p 
              LEFT JOIN categories c ON p.category_id = c.id 
              WHERE p.category_id = :category_id 
              AND p.status = 'active' 
              ORDER BY p.created_at DESC";
    if ($limit) {
        $query .= " LIMIT :limit";
    }
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':category_id', $category_id);
    if ($limit) {
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    }
    $stmt->execute();
    return $stmt->fetchAll();
}

// Get featured products
function getFeaturedProducts($limit = 8) {
    $conn = getDBConnection();
    $query = "SELECT p.*, c.name as category_name, c.slug as category_slug 
              FROM products p 
              LEFT JOIN categories c ON p.category_id = c.id 
              WHERE p.status = 'active' 
              ORDER BY p.rating DESC, p.review_count DESC 
              LIMIT :limit";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

/**
 * Search products
 * 
 * @param string $query The search query
 * @return array Array of matching products
 */
function searchProducts($query) {
    $conn = getDBConnection();
    $search = "%{$query}%";
    $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug 
            FROM products p 
            LEFT JOIN categories c ON p.category_id = c.id 
            WHERE p.status = 'active' 
            AND (p.name LIKE :search OR p.description LIKE :search) 
            ORDER BY p.created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':search', $search);
    $stmt->execute();
    return $stmt->fetchAll();
}

// Get user by ID
function getUser($id) {
    $conn = getDBConnection();
    $query = "SELECT * FROM users WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch();
}

// Get user by email
function getUserByEmail($email) {
    $conn = getDBConnection();
    $query = "SELECT * FROM users WHERE email = :email";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    return $stmt->fetch();
}

// Create user
function createUser($data) {
    $conn = getDBConnection();
    $query = "INSERT INTO users (username, email, password, first_name, last_name) 
              VALUES (:username, :email, :password, :first_name, :last_name)";
    $stmt = $conn->prepare($query);
    
    $password = password_hash($data['password'], PASSWORD_DEFAULT);
    
    $stmt->bindParam(':username', $data['username']);
    $stmt->bindParam(':email', $data['email']);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':first_name', $data['first_name']);
    $stmt->bindParam(':last_name', $data['last_name']);
    
    return $stmt->execute();
}

// Update user
function updateUser($user_id, $data) {
    $conn = getDBConnection();
    
    $query = "UPDATE users SET 
              username = :username,
              email = :email,
              first_name = :first_name,
              last_name = :last_name,
              phone = :phone,
              address = :address,
              city = :city,
              state = :state,
              country = :country,
              postal_code = :postal_code
              WHERE id = :id";
              
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':username', $data['username']);
    $stmt->bindParam(':email', $data['email']);
    $stmt->bindParam(':first_name', $data['first_name']);
    $stmt->bindParam(':last_name', $data['last_name']);
    $stmt->bindParam(':phone', $data['phone']);
    $stmt->bindParam(':address', $data['address']);
    $stmt->bindParam(':city', $data['city']);
    $stmt->bindParam(':state', $data['state']);
    $stmt->bindParam(':country', $data['country']);
    $stmt->bindParam(':postal_code', $data['postal_code']);
    $stmt->bindParam(':id', $user_id);
    
    return $stmt->execute();
}

/**
 * Get cart items
 * 
 * @return array Array of cart items
 */
function getCartItems() {
    if (!isset($_SESSION['cart'])) {
        return [];
    }
    $items = [];
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $product = getProduct($product_id);
        if ($product) {
            $items[] = [
                'product' => $product,
                'quantity' => $quantity
            ];
        }
    }
    return $items;
}

/**
 * Calculate cart total
 * 
 * @return float The cart total
 */
function getCartTotal() {
    $items = getCartItems();
    $total = 0;
    foreach ($items as $item) {
        $total += $item['product']['price'] * $item['quantity'];
    }
    return $total;
}

/**
 * Add item to cart
 * 
 * @param int $product_id The product ID
 * @param int $quantity The quantity to add
 * @return bool True if successful, false otherwise
 */
function addToCart($product_id, $quantity = 1) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    $product = getProduct($product_id);
    if (!$product) {
        return false;
    }
    
    // Check if product is in stock
    if ($product['stock'] <= 0) {
        return false;
    }
    
    // Check if adding quantity would exceed stock
    $current_quantity = isset($_SESSION['cart'][$product_id]) ? $_SESSION['cart'][$product_id] : 0;
    if (($current_quantity + $quantity) > $product['stock']) {
        return false;
    }
    
    // Add or update quantity
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
    
    return true;
}

/**
 * Update cart item quantity
 * 
 * @param int $product_id The product ID
 * @param int $quantity The new quantity
 * @return bool True if successful, false otherwise
 */
function updateCartQuantity($product_id, $quantity) {
    if (!isset($_SESSION['cart'][$product_id])) {
        return false;
    }
    
    $product = getProduct($product_id);
    if (!$product || $product['stock'] < $quantity) {
        return false;
    }
    
    if ($quantity <= 0) {
        removeFromCart($product_id);
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
    
    return true;
}

/**
 * Remove item from cart
 * 
 * @param int $product_id The product ID
 * @return bool True if successful, false otherwise
 */
function removeFromCart($product_id) {
    if (!isset($_SESSION['cart'][$product_id])) {
        return false;
    }
    
    unset($_SESSION['cart'][$product_id]);
    return true;
}

/**
 * Clear cart
 * 
 * @return void
 */
function clearCart() {
    $_SESSION['cart'] = [];
}

/**
 * Check if user is logged in
 * 
 * @return bool True if logged in, false otherwise
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Get current user data
 * 
 * @return array|false The user data or false if not logged in
 */
function getCurrentUser() {
    if (!isLoggedIn()) {
        return false;
    }
    
    $conn = getDBConnection();
    $query = "SELECT * FROM users WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $_SESSION['user_id']);
    $stmt->execute();
    return $stmt->fetch();
}

/**
 * Get user orders
 * 
 * @param int $user_id The user ID
 * @return array Array of orders
 */
function getUserOrders($user_id) {
    $conn = getDBConnection();
    $query = "SELECT * FROM orders WHERE user_id = :user_id ORDER BY created_at DESC";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    return $stmt->fetchAll();
}

/**
 * Get order details
 * 
 * @param int $order_id The order ID
 * @return array|false The order data or false if not found
 */
function getOrderDetails($order_id) {
    $conn = getDBConnection();
    $query = "SELECT o.*, u.name as user_name, u.email as user_email 
              FROM orders o 
              JOIN users u ON o.user_id = u.id 
              WHERE o.id = :order_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':order_id', $order_id);
    $stmt->execute();
    return $stmt->fetch();
}

/**
 * Get order items
 * 
 * @param int $order_id The order ID
 * @return array Array of order items
 */
function getOrderItems($order_id) {
    $conn = getDBConnection();
    $query = "SELECT oi.*, p.name as product_name, p.image as product_image 
              FROM order_items oi 
              JOIN products p ON oi.product_id = p.id 
              WHERE oi.order_id = :order_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':order_id', $order_id);
    $stmt->execute();
    return $stmt->fetchAll();
}

// Add review
function addReview($data) {
    $conn = getDBConnection();
    $query = "INSERT INTO reviews (user_id, product_id, rating, comment) 
              VALUES (:user_id, :product_id, :rating, :comment)";
    $stmt = $conn->prepare($query);
    
    $stmt->bindParam(':user_id', $data['user_id']);
    $stmt->bindParam(':product_id', $data['product_id']);
    $stmt->bindParam(':rating', $data['rating']);
    $stmt->bindParam(':comment', $data['comment']);
    
    if ($stmt->execute()) {
        // Update product rating
        updateProductRating($data['product_id']);
        return true;
    }
    return false;
}

// Update product rating
function updateProductRating($product_id) {
    $conn = getDBConnection();
    $query = "UPDATE products p 
              SET rating = (
                  SELECT AVG(rating) 
                  FROM reviews 
                  WHERE product_id = :product_id 
                  AND status = 'approved'
              ),
              review_count = (
                  SELECT COUNT(*) 
                  FROM reviews 
                  WHERE product_id = :product_id 
                  AND status = 'approved'
              )
              WHERE p.id = :product_id";
              
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':product_id', $product_id);
    return $stmt->execute();
}

/**
 * Subscribe email to newsletter
 * 
 * @param string $email The email to subscribe
 * @return bool True if successful, false otherwise
 */
function subscribeNewsletter($email) {
    $conn = getDBConnection();
    $query = "INSERT INTO newsletter_subscribers (email, subscribed_at) VALUES (:email, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $email);
    return $stmt->execute();
}

/**
 * Check if email is subscribed to newsletter
 * 
 * @param string $email The email to check
 * @return bool True if subscribed, false otherwise
 */
function isSubscribed($email) {
    $conn = getDBConnection();
    $query = "SELECT COUNT(*) as count FROM newsletter_subscribers WHERE email = :email";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    return $stmt->fetch()['count'] > 0;
}

/**
 * Get all categories
 * 
 * @return array Array of categories
 */
function getCategories() {
    $conn = getDBConnection();
    $query = "SELECT c.*, 
              (SELECT COUNT(*) FROM products WHERE category_id = c.id) as product_count 
              FROM categories c 
              WHERE c.status = 'active' 
              ORDER BY c.name ASC";
    $stmt = $conn->query($query);
    return $stmt->fetchAll();
}

/**
 * Get category by ID
 * 
 * @param int $id The category ID
 * @return array|false The category data or false if not found
 */
function getCategory($id) {
    $conn = getDBConnection();
    $query = "SELECT * FROM categories WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch();
}

// Get category by slug
function getCategoryBySlug($slug) {
    $conn = getDBConnection();
    $query = "SELECT * FROM categories WHERE slug = :slug";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':slug', $slug);
    $stmt->execute();
    return $stmt->fetch();
}

function getCategoryName($category_id) {
    $conn = getDBConnection();
    
    $query = "SELECT name FROM categories WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $category_id);
    $stmt->execute();
    
    $result = $stmt->fetch();
    return $result ? $result['name'] : null;
}

/**
 * Update order status
 */
function updateOrderStatus($order_id, $status) {
    $conn = getDBConnection();
    
    $query = "UPDATE orders SET status = :status WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':id', $order_id);
    
    return $stmt->execute();
}

/**
 * Update setting value
 */
function updateSetting($key, $value) {
    $conn = getDBConnection();
    
    $query = "INSERT INTO settings (`key`, `value`) 
              VALUES (:key, :value) 
              ON DUPLICATE KEY UPDATE `value` = :value";
              
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':key', $key);
    $stmt->bindParam(':value', $value);
    
    return $stmt->execute();
}

/**
 * Get setting value
 */
function getSetting($key) {
    $conn = getDBConnection();
    
    $query = "SELECT value FROM settings WHERE `key` = :key";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':key', $key);
    $stmt->execute();
    
    $result = $stmt->fetch();
    return $result ? $result['value'] : null;
}

/**
 * Get all settings
 */
function getAllSettings() {
    $conn = getDBConnection();
    
    $query = "SELECT * FROM settings";
    $stmt = $conn->query($query);
    
    $settings = [];
    while ($row = $stmt->fetch()) {
        $settings[$row['key']] = $row['value'];
    }
    
    return $settings;
}

/**
 * Create URL-friendly slug
 */
function createSlug($string) {
    // Convert to lowercase
    $string = strtolower($string);
    
    // Replace spaces with hyphens
    $string = str_replace(' ', '-', $string);
    
    // Remove special characters
    $string = preg_replace('/[^a-z0-9-]/', '', $string);
    
    // Remove multiple consecutive hyphens
    $string = preg_replace('/-+/', '-', $string);
    
    // Remove leading and trailing hyphens
    $string = trim($string, '-');
    
    return $string;
}

/**
 * Format currency
 */
function formatCurrency($amount) {
    return '$' . number_format($amount, 2);
}

/**
 * Get order statistics
 */
function getOrderStatistics() {
    $conn = getDBConnection();
    
    $stats = [
        'total_orders' => 0,
        'total_revenue' => 0,
        'pending_orders' => 0,
        'processing_orders' => 0,
        'shipped_orders' => 0,
        'delivered_orders' => 0,
        'cancelled_orders' => 0
    ];
    
    // Get total orders and revenue
    $query = "SELECT COUNT(*) as total, SUM(total_amount) as revenue FROM orders";
    $stmt = $conn->query($query);
    $result = $stmt->fetch();
    $stats['total_orders'] = $result['total'];
    $stats['total_revenue'] = $result['revenue'] ?? 0;
    
    // Get orders by status
    $query = "SELECT status, COUNT(*) as count FROM orders GROUP BY status";
    $stmt = $conn->query($query);
    while ($row = $stmt->fetch()) {
        $key = $row['status'] . '_orders';
        if (isset($stats[$key])) {
            $stats[$key] = $row['count'];
        }
    }
    
    return $stats;
}

/**
 * Get user statistics
 */
function getUserStatistics() {
    $conn = getDBConnection();
    
    $stats = [
        'total_users' => 0,
        'active_users' => 0,
        'inactive_users' => 0,
        'admin_users' => 0
    ];
    
    // Get total users
    $query = "SELECT COUNT(*) as total FROM users";
    $stmt = $conn->query($query);
    $result = $stmt->fetch();
    $stats['total_users'] = $result['total'];
    
    // Get users by status and role
    $query = "SELECT status, role, COUNT(*) as count FROM users GROUP BY status, role";
    $stmt = $conn->query($query);
    while ($row = $stmt->fetch()) {
        if ($row['status'] === 'active') {
            $stats['active_users'] = $row['count'];
        } elseif ($row['status'] === 'inactive') {
            $stats['inactive_users'] = $row['count'];
        }
        if ($row['role'] === 'admin') {
            $stats['admin_users'] = $row['count'];
        }
    }
    
    return $stats;
}

/**
 * Get product statistics
 */
function getProductStatistics() {
    $conn = getDBConnection();
    
    $stats = [
        'total_products' => 0,
        'active_products' => 0,
        'inactive_products' => 0,
        'low_stock_products' => 0,
        'out_of_stock_products' => 0
    ];
    
    // Get total products
    $query = "SELECT COUNT(*) as total FROM products";
    $stmt = $conn->query($query);
    $result = $stmt->fetch();
    $stats['total_products'] = $result['total'];
    
    // Get products by status
    $query = "SELECT status, COUNT(*) as count FROM products GROUP BY status";
    $stmt = $conn->query($query);
    while ($row = $stmt->fetch()) {
        if ($row['status'] === 'active') {
            $stats['active_products'] = $row['count'];
        } elseif ($row['status'] === 'inactive') {
            $stats['inactive_products'] = $row['count'];
        }
    }
    
    // Get low stock and out of stock products
    $query = "SELECT 
              COUNT(*) as low_stock,
              (SELECT COUNT(*) FROM products WHERE stock = 0) as out_of_stock
              FROM products WHERE stock <= 5 AND stock > 0";
    $stmt = $conn->query($query);
    $result = $stmt->fetch();
    $stats['low_stock_products'] = $result['low_stock'];
    $stats['out_of_stock_products'] = $result['out_of_stock'];
    
    return $stats;
}

function getProductBySlug($slug) {
    $conn = getDBConnection();
    $query = "SELECT p.*, c.name as category_name, c.slug as category_slug 
              FROM products p 
              LEFT JOIN categories c ON p.category_id = c.id 
              WHERE p.slug = :slug";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':slug', $slug);
    $stmt->execute();
    return $stmt->fetch();
}

function getUserById($user_id) {
    global $conn;
    $user_id = (int)$user_id;
    
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_assoc();
}

function updateUserProfile($user_id, $data) {
    global $conn;
    $user_id = (int)$user_id;
    
    $sql = "UPDATE users SET 
            first_name = ?,
            last_name = ?,
            email = ?,
            phone = ?
            WHERE id = ?";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", 
        $data['first_name'],
        $data['last_name'],
        $data['email'],
        $data['phone'],
        $user_id
    );
    
    return $stmt->execute();
}

function updateUserAddress($user_id, $data) {
    global $conn;
    $user_id = (int)$user_id;
    
    $sql = "UPDATE users SET 
            address = ?,
            city = ?,
            state = ?,
            postal_code = ?,
            country = ?
            WHERE id = ?";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", 
        $data['address'],
        $data['city'],
        $data['state'],
        $data['postal_code'],
        $data['country'],
        $user_id
    );
    
    return $stmt->execute();
}

function updateUserPassword($user_id, $new_password) {
    global $conn;
    $user_id = (int)$user_id;
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    
    $sql = "UPDATE users SET password = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $hashed_password, $user_id);
    
    return $stmt->execute();
}

function verifyPassword($password, $hashed_password) {
    return password_verify($password, $hashed_password);
} 