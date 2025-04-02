-- Create database
CREATE DATABASE IF NOT EXISTS knightx_db;
USE knightx_db;

-- Users table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    phone VARCHAR(20),
    address TEXT,
    city VARCHAR(50),
    state VARCHAR(50),
    country VARCHAR(50),
    postal_code VARCHAR(20),
    avatar VARCHAR(255),
    role ENUM('user', 'admin') DEFAULT 'user',
    status ENUM('active', 'inactive', 'banned') DEFAULT 'active',
    email_verified BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Categories table
CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    description TEXT,
    parent_id INT,
    image VARCHAR(255),
    icon VARCHAR(50),
    is_featured BOOLEAN DEFAULT FALSE,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Products table
CREATE TABLE products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT,
    short_description VARCHAR(255),
    price DECIMAL(10,2) NOT NULL,
    sale_price DECIMAL(10,2),
    stock INT NOT NULL DEFAULT 0,
    category_id INT,
    image VARCHAR(255),
    gallery TEXT,
    specifications TEXT,
    features TEXT,
    rating DECIMAL(3,2) DEFAULT 0,
    review_count INT DEFAULT 0,
    is_featured BOOLEAN DEFAULT FALSE,
    is_new BOOLEAN DEFAULT TRUE,
    is_bestseller BOOLEAN DEFAULT FALSE,
    status ENUM('active', 'inactive', 'out_of_stock', 'discontinued') DEFAULT 'active',
    meta_title VARCHAR(255),
    meta_description TEXT,
    meta_keywords VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Orders table
CREATE TABLE orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    order_number VARCHAR(50) UNIQUE NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    shipping_address TEXT NOT NULL,
    billing_address TEXT NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    payment_status ENUM('pending', 'processing', 'completed', 'failed', 'refunded') DEFAULT 'pending',
    order_status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded') DEFAULT 'pending',
    shipping_method VARCHAR(50),
    tracking_number VARCHAR(100),
    notes TEXT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Order items table
CREATE TABLE order_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Reviews table
CREATE TABLE reviews (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    title VARCHAR(255),
    comment TEXT,
    images TEXT,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Cart table
CREATE TABLE cart (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    session_id VARCHAR(100),
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Wishlist table
CREATE TABLE wishlist (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY unique_wishlist (user_id, product_id)
);

-- Newsletter subscribers table
CREATE TABLE newsletter_subscribers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(100) UNIQUE NOT NULL,
    status ENUM('active', 'unsubscribed') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create settings table
CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    `key` VARCHAR(255) NOT NULL UNIQUE,
    `value` TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default settings
INSERT INTO settings (`key`, `value`) VALUES
('site_name', 'KnightX'),
('site_description', 'Your Premium Gaming Destination'),
('contact_email', 'support@knightx.com'),
('contact_phone', '+1 (555) 123-4567'),
('address', '123 Gaming Street'),
('city', 'Gaming City'),
('state', 'GC'),
('country', 'United States'),
('postal_code', '12345'),
('shipping_cost', '5.00'),
('free_shipping_threshold', '50.00'),
('tax_rate', '8.00'),
('tax_enabled', '1'),
('stripe_public_key', ''),
('stripe_secret_key', ''),
('paypal_client_id', ''),
('paypal_secret', ''),
('paypal_mode', 'sandbox')
ON DUPLICATE KEY UPDATE `value` = VALUES(`value`);

-- Insert admin user
INSERT INTO users (username, email, password, first_name, last_name, role, status, email_verified) VALUES
('admin', 'admin@knightx.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin', 'User', 'admin', 'active', 1);

-- Insert sample categories
INSERT INTO categories (name, slug, description, icon, is_featured) VALUES
('Gaming Keyboards', 'gaming-keyboards', 'High-performance mechanical gaming keyboards', 'keyboard', 1),
('Gaming Mice', 'gaming-mice', 'Precision gaming mice with customizable buttons', 'mouse', 1),
('Gaming Headsets', 'gaming-headsets', 'Immersive gaming audio with crystal-clear communication', 'headset', 1),
('Gaming Monitors', 'gaming-monitors', 'High-refresh rate gaming displays', 'desktop', 1),
('Gift Cards', 'gift-cards', 'Digital gift cards for various gaming platforms', 'gift', 1);

-- Insert sample products
INSERT INTO products (name, slug, description, short_description, price, category_id, image, is_featured, is_new, is_bestseller) VALUES
('Razer BlackWidow V3 Pro', 'razer-blackwidow-v3-pro', 'Wireless mechanical gaming keyboard with RGB lighting and premium build quality', 'Premium wireless mechanical keyboard', 229.99, 1, 'assets/images/products/keyboards/razer-blackwidow-v3-pro.jpg', 1, 1, 1),
('Logitech G Pro X Superlight', 'logitech-g-pro-x-superlight', 'Ultra-lightweight wireless gaming mouse with HERO sensor', 'Ultra-lightweight wireless mouse', 149.99, 2, 'assets/images/products/mice/logitech-g-pro-x-superlight.jpg', 1, 1, 1),
('SteelSeries Arctis Pro', 'steelseries-arctis-pro', 'Premium gaming headset with Hi-Res audio and DTS surround', 'Premium gaming headset', 199.99, 3, 'assets/images/products/headsets/steelseries-arctis-pro.jpg', 1, 1, 1),
('ASUS ROG Swift PG279QM', 'asus-rog-swift-pg279qm', '27-inch 4K HDR gaming monitor with 144Hz refresh rate', '4K HDR gaming monitor', 999.99, 4, 'assets/images/products/monitors/asus-rog-swift-pg279qm.jpg', 1, 1, 1),
('Steam Gift Card $50', 'steam-gift-card-50', 'Digital gift card for Steam platform', 'Steam gift card', 50.00, 5, 'assets/images/products/gift-cards/steam-50.jpg', 1, 1, 1); 