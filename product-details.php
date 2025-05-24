<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

// Get product ID from URL
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get product details
$product = getProduct($product_id);

// If product not found, redirect to products page
if (!$product) {
    header('Location: products.php');
    exit;
}

// Get related products
$related_products = getRelatedProducts($product['category_id'], $product['id'], 4);

// Get product reviews
$reviews = getProductReviews($product['id']);
$average_rating = calculateAverageRating($reviews);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - KnightX</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main class="container">
        <div class="product-details-page">
            <!-- Breadcrumb -->
            <div class="breadcrumb">
                <a href="index.php">Home</a>
                <i class="fas fa-chevron-right"></i>
                <a href="products.php">Products</a>
                <i class="fas fa-chevron-right"></i>
                <a href="products.php?category=<?php echo $product['category_slug']; ?>">
                    <?php echo htmlspecialchars($product['category_name']); ?>
                </a>
                <i class="fas fa-chevron-right"></i>
                <span><?php echo htmlspecialchars($product['name']); ?></span>
            </div>

            <div class="product-details">
                <!-- Product Images -->
                <div class="product-gallery">
                    <div class="main-image">
                        <img src="assets/images/products/<?php echo htmlspecialchars($product['image']); ?>" 
                             alt="<?php echo htmlspecialchars($product['name']); ?>">
                    </div>
                    <?php if (!empty($product['gallery'])): ?>
                        <div class="thumbnail-images">
                            <?php foreach ($product['gallery'] as $image): ?>
                                <div class="thumbnail">
                                    <img src="assets/images/products/<?php echo htmlspecialchars($image); ?>" 
                                         alt="<?php echo htmlspecialchars($product['name']); ?>">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Product Info -->
                <div class="product-info">
                    <h1 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h1>
                    
                    <div class="product-meta">
                        <div class="product-rating">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star <?php echo $i <= $average_rating ? 'active' : ''; ?>"></i>
                            <?php endfor; ?>
                            <span class="review-count">(<?php echo count($reviews); ?> reviews)</span>
                        </div>
                        <div class="product-sku">
                            SKU: <?php echo htmlspecialchars($product['sku']); ?>
                        </div>
                    </div>

                    <div class="product-price">
                        <?php if ($product['sale_price']): ?>
                            <span class="sale-price"><?php echo formatPrice($product['sale_price']); ?></span>
                            <span class="original-price"><?php echo formatPrice($product['price']); ?></span>
                            <span class="discount-badge">
                                <?php 
                                $discount = (($product['price'] - $product['sale_price']) / $product['price']) * 100;
                                echo round($discount) . '% OFF';
                                ?>
                            </span>
                        <?php else: ?>
                            <span class="price"><?php echo formatPrice($product['price']); ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="product-description">
                        <?php echo nl2br(htmlspecialchars($product['description'])); ?>
                    </div>

                    <div class="product-features">
                        <h3>Key Features</h3>
                        <ul>
                            <?php foreach (explode("\n", $product['features']) as $feature): ?>
                                <li><i class="fas fa-check"></i> <?php echo htmlspecialchars($feature); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <div class="product-stock">
                        <span class="stock-status <?php echo $product['stock'] > 0 ? 'in-stock' : 'out-of-stock'; ?>">
                            <i class="fas <?php echo $product['stock'] > 0 ? 'fa-check-circle' : 'fa-times-circle'; ?>"></i>
                            <?php echo $product['stock'] > 0 ? 'In Stock' : 'Out of Stock'; ?>
                        </span>
                        <?php if ($product['stock'] > 0): ?>
                            <span class="stock-quantity">(<?php echo $product['stock']; ?> available)</span>
                        <?php endif; ?>
                    </div>

                    <?php if ($product['stock'] > 0): ?>
                        <form action="cart.php" method="POST" class="add-to-cart-form">
                            <div class="quantity-selector">
                                <button type="button" class="quantity-btn minus">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" name="quantity" value="1" min="1" max="<?php echo $product['stock']; ?>" 
                                       class="quantity-input">
                                <button type="button" class="quantity-btn plus">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <button type="submit" class="btn btn-primary btn-large">
                                <i class="fas fa-shopping-cart"></i> Add to Cart
                            </button>
                        </form>
                    <?php endif; ?>

                    <div class="product-actions">
                        <button class="btn btn-secondary" onclick="addToWishlist(<?php echo $product['id']; ?>)">
                            <i class="fas fa-heart"></i> Add to Wishlist
                        </button>
                        <button class="btn btn-secondary" onclick="shareProduct()">
                            <i class="fas fa-share-alt"></i> Share
                        </button>
                    </div>
                </div>
            </div>

            <!-- Product Tabs -->
            <div class="product-tabs">
                <div class="tabs-header">
                    <button class="tab-btn active" data-tab="description">Description</button>
                    <button class="tab-btn" data-tab="specifications">Specifications</button>
                    <button class="tab-btn" data-tab="reviews">Reviews</button>
                </div>

                <div class="tabs-content">
                    <div class="tab-pane active" id="description">
                        <div class="product-full-description">
                            <?php echo nl2br(htmlspecialchars($product['full_description'])); ?>
                        </div>
                    </div>

                    <div class="tab-pane" id="specifications">
                        <table class="specifications-table">
                            <?php foreach ($product['specifications'] as $key => $value): ?>
                                <tr>
                                    <th><?php echo htmlspecialchars($key); ?></th>
                                    <td><?php echo htmlspecialchars($value); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>

                    <div class="tab-pane" id="reviews">
                        <div class="reviews-section">
                            <div class="reviews-summary">
                                <div class="average-rating">
                                    <h3>Customer Reviews</h3>
                                    <div class="rating-number"><?php echo number_format($average_rating, 1); ?></div>
                                    <div class="rating-stars">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="fas fa-star <?php echo $i <= $average_rating ? 'active' : ''; ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                    <div class="total-reviews">
                                        Based on <?php echo count($reviews); ?> reviews
                                    </div>
                                </div>
                            </div>

                            <div class="reviews-list">
                                <?php foreach ($reviews as $review): ?>
                                    <div class="review-item">
                                        <div class="review-header">
                                            <div class="reviewer-info">
                                                <span class="reviewer-name"><?php echo htmlspecialchars($review['user_name']); ?></span>
                                                <div class="review-rating">
                                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                                        <i class="fas fa-star <?php echo $i <= $review['rating'] ? 'active' : ''; ?>"></i>
                                                    <?php endfor; ?>
                                                </div>
                                            </div>
                                            <span class="review-date">
                                                <?php echo date('F j, Y', strtotime($review['created_at'])); ?>
                                            </span>
                                        </div>
                                        <div class="review-content">
                                            <?php echo nl2br(htmlspecialchars($review['comment'])); ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Prod 