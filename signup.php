<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ghostlamp Signup/Login</title>
    <link rel="stylesheet" href="assets/css/signup.css">
</head>
<body>
    <div class="container">
        <div class="right-section">
            <div class="form-container">
                <h2 id="form-title">Create Account</h2>
                <p>Please fill in the information below to create your account.</p>

                <form id="signup-form">
                    <div class="input-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" placeholder="Enter your username">
                        <span class="checkmark">&#10004;</span>
                    </div>
                    <div class="input-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" placeholder="user@gmail.com">
                        <span class="checkmark">&#10004;</span>
                    </div>
                    <div class="input-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="********">
                    </div>
                    <div class="input-group">
                        <label for="confirmPassword">Confirm Password</label>
                        <input type="password" id="confirmPassword" name="confirmPassword" placeholder="********">
                    </div>
                    <button type="submit" class="signup-button">Sign Up</button>
                    <a href="login.php">
                        <button type="button" class="login-button">Login</button>
                    </a>
                </form>

                <div class="social-signup">
                    <p>Or sign up with</p>
                    <div class="social-icons">
                        <a href="#"><img src="google-icon.png" alt="Google"></a>
                        <a href="#"><img src="facebook-icon.png" alt="Facebook"></a>
                        <a href="#"><img src="twitter-icon.png" alt="Twitter"></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="left-section">
            <a href="index.php">
                <img src="assets/images/logo.png" alt="KnightX" class="logo">
            </a>    
            <img src="assets/images/signup-illustration.gif" alt="KnightX" class="illustration">
        </div>
    </div>

    <script src="assets/js/signup.js"></script>
</body>
</html>