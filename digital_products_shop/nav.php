<nav>
    <div class="nav-links">
        <a href="index.php">Categories</a>
        <a href="products.php">Products</a>
        <a href="recycle_bin.php">Recycle Bin</a>
    </div>
    <div class="nav-user">
        Logged in as <strong><?= htmlspecialchars($_SESSION['full_name']) ?></strong>
        <a href="logout.php" class="logout-btn" onclick="return confirm('Are you sure you want to log out?');">Logout</a>
    </div>
</nav>
