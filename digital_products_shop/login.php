<?php
require_once 'core/dbConfig.php';
require_once 'core/auth.php';
redirectIfLoggedIn();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="auth-wrap">
        <div class="hero">
            <h1>Digital Products Store</h1>
            <p>Please log in to continue.</p>
        </div>

        <?php
        if (isset($_GET['loggedout'])) {
            echo '<div class="alert alert-success">You have been logged out.</div>';
        }
        showFlash();
        ?>

        <form class="app-form" action="core/handleForms.php" method="POST">
            <div class="field">
                <label>Username</label>
                <input type="text" name="username" required autofocus>
            </div>
            <div class="field">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" name="loginUser">Login</button>
        </form>
        <p class="auth-switch">No account yet? <a href="register.php">Register here</a></p>
    </div>
</body>
</html>
