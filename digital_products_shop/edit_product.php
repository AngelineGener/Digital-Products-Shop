<?php
require_once 'core/dbConfig.php';
require_once 'core/auth.php';
require_once 'core/models.php';
requireLogin();

$product = getProductById($pdo, (int) ($_GET['id'] ?? 0));
if (!$product) { die("Product not found."); }
$search = trim($_GET['search'] ?? '');
$categories = getAllCategories($pdo);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'nav.php'; ?>

    <?php showFlash(); ?>
    <h2>Edit Product #<?= $product['product_id'] ?></h2>
    <form class="app-form" action="core/handleForms.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
        <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">
        <div class="form-grid">
            <div class="field">
                <label>Category</label>
                <select name="category_id" required>
                    <?php foreach ($categories as $c): ?>
                        <option value="<?= $c['category_id'] ?>" <?= $c['category_id'] == $product['category_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['category_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="field">
                <label>Title</label>
                <input type="text" name="title" value="<?= htmlspecialchars($product['title']) ?>" required>
            </div>
            <div class="field">
                <label>Price</label>
                <div class="peso-input"><span>₱</span><input type="number" step="0.01" min="0" name="price" value="<?= $product['price'] ?>" required></div>
            </div>
            <div class="field">
                <label>File Type</label>
                <input type="text" name="file_type" value="<?= htmlspecialchars($product['file_type']) ?>">
            </div>
            <div class="field full">
                <label>Product Image</label>
                <img class="preview" src="<?= htmlspecialchars($product['image_path']) ?>" alt="Current image">
                <input type="file" name="image" accept="image/png, image/jpeg, image/gif, image/webp">
                <small>Leave empty to keep the current image.</small>
            </div>
            <div class="field full">
                <label>Description</label>
                <textarea name="description" rows="3"><?= htmlspecialchars($product['description']) ?></textarea>
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" name="updateProduct">Save Changes</button>
            <a href="products.php<?= $search !== '' ? '?search=' . urlencode($search) : '' ?>">Cancel</a>
        </div>
    </form>
</body>
</html>
