<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

$error = '';
$success = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];
    
    $conn = getDBConnection();
    $query = "SELECT * FROM users WHERE email = :email";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        
        // Check if there's a pending cart item
        if (isset($_SESSION['pending_cart_item'])) {
            $product_id = $_SESSION['pending_cart_item'];
            $quantity = isset($_SESSION['pending_cart_quantity']) ? $_SESSION['pending_cart_quantity'] : 1;
            addToCart($product_id, $quantity);
            unset($_SESSION['pending_cart_item']);
            unset($_SESSION['pending_cart_quantity']);
        }
        
        // Redirect to the intended page or cart
        $redirect = isset($_SESSION['redirect_after_login']) ? $_SESSION['redirect_after_login'] : 'index.php';
        unset($_SESSION['redirect_after_login']);
        
        header("Location: $redirect");
        exit;
    } else {
        $error = "Invalid email or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ghostlamp Login/Signup</title>
    <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>
    <div class="container">
        <div class="left-section">
            <a href="index.php">
                <img src="assets/images/logo.png" alt="KnightX" class="logo">
            </a>    
            <img src="assets/images/login-illustration.gif" alt="KnightX" class="illustration">
        </div>
        <div class="right-section">
            <div class="form-container">
                <h2 id="form-title">Welcome Back :)</h2>
                <p>To keep connected with us please login with your personal information by email address and password ⚠️</p>

                <form id="login-form" method="POST" action="login.php">
                    <div class="input-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" placeholder="Justin@ghostlamp.io" required>
                        <span class="checkmark">&#10004;</span>
                    </div>
                    <div class="input-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="********" required>
                    </div>
                    <div class="checkbox-group">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Remember Me</label>
                        <a href="forgot-password.php">Forgot Password?</a>
                    </div>
                    <button type="submit" class="login-button">Login Now</button>
                    <a href="signup.php">
                        <button type="button" class="create-account-button">Create Account</button>
                    </a>
                </form>

                <div class="social-login">
                    <p>Or you can join with</p>
                    <div class="social-icons">
                        <a href="#"><img src="google-icon.png" alt="Google"></a>
                        <a href="#"><img src="facebook-icon.png" alt="Facebook"></a>
                        <a href="#"><img src="twitter-icon.png" alt="Twitter"></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type='text/javascript'>document.addEventListener('DOMContentLoaded', function () {window.setTimeout(document.querySelector('svg').classList.add('animated'),1000);})</script>
    
</body>
</html>