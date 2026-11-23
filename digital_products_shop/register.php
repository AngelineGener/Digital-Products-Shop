<?php
require_once 'core/dbConfig.php';
require_once 'core/auth.php';
redirectIfLoggedIn();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="auth-wrap wide">
        <div class="hero">
            <h1>Create an Account</h1>
            <p>Fill in your details to register.</p>
        </div>

        <?php showFlash(); ?>

        <form class="app-form" action="core/handleForms.php" method="POST">
            <div class="form-grid">
                <div class="field">
                    <label>First Name</label>
                    <input type="text" name="first_name" required>
                </div>
                <div class="field">
                    <label>Last Name</label>
                    <input type="text" name="last_name" required>
                </div>
                <div class="field">
                    <label>Age (optional)</label>
                    <input type="number" name="age" min="1" max="120">
                </div>
                <div class="field">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>
                <div class="field full">
                    <label>Address (optional)</label>
                    <input type="text" name="address">
                </div>
                <div class="field full">
                    <label>Username</label>
                    <input type="text" name="username" required>
                </div>
                <div class="field">
                    <label>Password</label>
                    <input type="password" name="password" minlength="6" required>
                </div>
                <div class="field">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" minlength="6" required>
                </div>
            </div>
            <button type="submit" name="registerUser">Register</button>
        </form>
        <p class="auth-switch">Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>
