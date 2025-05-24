<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

$categories = getCategories();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Categories | KnightX</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<?php include 'includes/header.php'; ?>

<main class="container">
    <div class="categories-header" style="margin: 40px 0 30px;">
        <h1 style="font-size:2.2rem;">All Categories</h1>
        <p style="color: var(--text-secondary); font-size:1.1rem;">Browse our product categories below.</p>
    </div>

    <?php if (empty($categories)): ?>
        <div class="empty-cart" style="text-align:center;">
            <i class="fas fa-box-open" style="font-size:48px;color:var(--text-secondary);"></i>
            <h2>No categories found.</h2>
        </div>
    <?php else: ?>
        <div class="category-grid">
            <?php foreach ($categories as $category): ?>
                <div class="category-card">
                    <div class="category-image">
                        <img src="assets/images/categories/<?php echo htmlspecialchars($category['image'] ?? 'default.png'); ?>" alt="<?php echo htmlspecialchars($category['name']); ?>">
                    </div>
                    <div class="category-overlay">
                        <div>
                            <h2 class="category-title"><?php echo htmlspecialchars($category['name']); ?></h2>
                            <?php if (!empty($category['description'])): ?>
                                <p style="color: #fff;"><?php echo htmlspecialchars($category['description']); ?></p>
                            <?php endif; ?>
                            <div style="margin: 10px 0;">
                                <span class="badge" style="background: var(--primary-color); color: #fff; padding: 5px 12px; border-radius: 12px;">
                                    <?php echo $category['product_count']; ?> Products
                                </span>
                            </div>
                            <a href="category.php?slug=<?php echo $category['slug']; ?>" class="btn btn-primary" style="margin-top:10px;">
                                <i class="fas fa-arrow-right"></i> View Products
                            </a>
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