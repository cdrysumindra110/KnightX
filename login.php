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

                <form id="login-form">
                    <div class="input-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" placeholder="Justin@ghostlamp.io">
                        <span class="checkmark">&#10004;</span>
                    </div>
                    <div class="input-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="********">
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

    <script src="assets/js/login.js"></script>
    <script type='text/javascript'>document.addEventListener('DOMContentLoaded', function () {window.setTimeout(document.querySelector('svg').classList.add('animated'),1000);})</script>
    
</body>
</html>