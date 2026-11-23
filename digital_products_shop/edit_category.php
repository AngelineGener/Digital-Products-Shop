<?php
require_once 'core/dbConfig.php';
require_once 'core/auth.php';
require_once 'core/models.php';
requireLogin();

$category = getCategoryById($pdo, (int) ($_GET['id'] ?? 0));
if (!$category) { die("Category not found."); }
$search = trim($_GET['search'] ?? '');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit Category</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'nav.php'; ?>

    <h2>Edit Category #<?= $category['category_id'] ?></h2>
    <form class="app-form" action="core/handleForms.php" method="POST">
        <input type="hidden" name="category_id" value="<?= $category['category_id'] ?>">
        <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">
        <div class="form-grid">
            <div class="field full">
                <label>Category Name</label>
                <input type="text" name="category_name" value="<?= htmlspecialchars($category['category_name']) ?>" required>
            </div>
            <div class="field full">
                <label>Description</label>
                <textarea name="description" rows="3"><?= htmlspecialchars($category['description']) ?></textarea>
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" name="updateCategory">Save Changes</button>
            <a href="index.php<?= $search !== '' ? '?search=' . urlencode($search) : '' ?>">Cancel</a>
        </div>
    </form>
</body>
</html>
