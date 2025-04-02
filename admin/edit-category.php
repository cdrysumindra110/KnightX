<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$conn = getDBConnection();
$message = '';
$error = '';

// Get category ID from URL
$category_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get category data
$query = "SELECT * FROM categories WHERE id = :id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':id', $category_id);
$stmt->execute();
$category = $stmt->fetch();

if (!$category) {
    header('Location: categories.php');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitizeInput($_POST['name']);
    $slug = createSlug($name);

    // Validate input
    if (empty($name)) {
        $error = 'Please enter a category name.';
    } else {
        // Check if category name already exists (excluding current category)
        $query = "SELECT COUNT(*) as count FROM categories WHERE name = :name AND id != :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':id', $category_id);
        $stmt->execute();
        
        if ($stmt->fetch()['count'] > 0) {
            $error = 'Category name already exists.';
        } else {
            // Update category
            $query = "UPDATE categories SET name = :name, slug = :slug WHERE id = :id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':slug', $slug);
            $stmt->bindParam(':id', $category_id);
            
            if ($stmt->execute()) {
                $message = '<div class="alert alert-success">Category updated successfully!</div>';
                // Refresh category data
                $query = "SELECT * FROM categories WHERE id = :id";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(':id', $category_id);
                $stmt->execute();
                $category = $stmt->fetch();
            } else {
                $error = 'Error updating category.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category - KnightX Admin</title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="admin-sidebar-header">
                <img src="../assets/images/logo.png" alt="KnightX Logo" class="admin-logo">
                <h2>Admin Panel</h2>
            </div>
            
            <nav class="admin-nav">
                <ul>
                    <li>
                        <a href="index.php">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="products.php">
                            <i class="fas fa-box"></i> Products
                        </a>
                    </li>
                    <li>
                        <a href="categories.php" class="active">
                            <i class="fas fa-tags"></i> Categories
                        </a>
                    </li>
                    <li>
                        <a href="orders.php">
                            <i class="fas fa-shopping-cart"></i> Orders
                        </a>
                    </li>
                    <li>
                        <a href="users.php">
                            <i class="fas fa-users"></i> Users
                        </a>
                    </li>
                    <li>
                        <a href="settings.php">
                            <i class="fas fa-cog"></i> Settings
                        </a>
                    </li>
                    <li>
                        <a href="logout.php">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="admin-content">
            <header class="admin-header">
                <h1>Edit Category</h1>
                <a href="categories.php" class="admin-btn">
                    <i class="fas fa-arrow-left"></i> Back to Categories
                </a>
            </header>

            <?php echo $message; ?>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <div class="admin-card">
                <form method="POST" class="admin-form">
                    <div class="form-group">
                        <label for="name">Category Name *</label>
                        <input type="text" id="name" name="name" required 
                               value="<?php echo htmlspecialchars($category['name']); ?>">
                    </div>

                    <div class="form-group">
                        <label for="slug">Slug</label>
                        <input type="text" id="slug" value="<?php echo htmlspecialchars($category['slug']); ?>" readonly>
                        <small class="form-text">Slug is automatically generated from the category name.</small>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="admin-btn admin-btn-primary">
                            <i class="fas fa-save"></i> Update Category
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <!-- JavaScript -->
    <script src="../assets/js/main.js"></script>
</body>
</html> 