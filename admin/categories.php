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

// Handle category deletion
if (isset($_POST['delete_category'])) {
    $category_id = (int)$_POST['category_id'];
    
    // Check if category has products
    $query = "SELECT COUNT(*) as count FROM products WHERE category_id = :category_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':category_id', $category_id);
    $stmt->execute();
    $product_count = $stmt->fetch()['count'];
    
    if ($product_count > 0) {
        $message = '<div class="alert alert-danger">Cannot delete category with existing products.</div>';
    } else {
        $query = "DELETE FROM categories WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $category_id);
        
        if ($stmt->execute()) {
            $message = '<div class="alert alert-success">Category deleted successfully!</div>';
        } else {
            $message = '<div class="alert alert-danger">Error deleting category.</div>';
        }
    }
}

// Handle category addition
if (isset($_POST['add_category'])) {
    $name = sanitizeInput($_POST['name']);
    $slug = createSlug($name);
    
    // Check if category name already exists
    $query = "SELECT COUNT(*) as count FROM categories WHERE name = :name";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':name', $name);
    $stmt->execute();
    
    if ($stmt->fetch()['count'] > 0) {
        $message = '<div class="alert alert-danger">Category name already exists.</div>';
    } else {
        $query = "INSERT INTO categories (name, slug) VALUES (:name, :slug)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':slug', $slug);
        
        if ($stmt->execute()) {
            $message = '<div class="alert alert-success">Category added successfully!</div>';
        } else {
            $message = '<div class="alert alert-danger">Error adding category.</div>';
        }
    }
}

// Get all categories with product counts
$query = "SELECT c.*, COUNT(p.id) as product_count 
          FROM categories c 
          LEFT JOIN products p ON c.id = p.category_id 
          GROUP BY c.id 
          ORDER BY c.name ASC";
$stmt = $conn->query($query);
$categories = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories - KnightX Admin</title>
    
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
                <h1>Manage Categories</h1>
            </header>

            <?php echo $message; ?>

            <!-- Add Category Form -->
            <div class="admin-card">
                <h2>Add New Category</h2>
                <form method="POST" class="admin-form">
                    <div class="form-group">
                        <label for="name">Category Name *</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-actions">
                        <button type="submit" name="add_category" class="admin-btn admin-btn-primary">
                            <i class="fas fa-plus"></i> Add Category
                        </button>
                    </div>
                </form>
            </div>

            <!-- Categories Table -->
            <div class="admin-card">
                <h2>Existing Categories</h2>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Products</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $category): ?>
                            <tr>
                                <td>#<?php echo $category['id']; ?></td>
                                <td><?php echo htmlspecialchars($category['name']); ?></td>
                                <td><?php echo htmlspecialchars($category['slug']); ?></td>
                                <td><?php echo $category['product_count']; ?></td>
                                <td>
                                    <div class="admin-actions">
                                        <a href="edit-category.php?id=<?php echo $category['id']; ?>" 
                                           class="admin-btn admin-btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if ($category['product_count'] == 0): ?>
                                            <form method="POST" class="delete-form" 
                                                  onsubmit="return confirm('Are you sure you want to delete this category?');">
                                                <input type="hidden" name="category_id" value="<?php echo $category['id']; ?>">
                                                <button type="submit" name="delete_category" class="admin-btn admin-btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- JavaScript -->
    <script src="../assets/js/main.js"></script>
</body>
</html> 