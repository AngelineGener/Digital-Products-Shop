<?php
require_once 'core/dbConfig.php';
require_once 'core/auth.php';
require_once 'core/models.php';
requireLogin();   // not logged in -> redirected to login.php

$search = trim($_GET['search'] ?? '');
$categories = getAllCategories($pdo, $search);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Categories - Digital Products</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'nav.php'; ?>

    <div class="hero">
        <h1>Digital Products Store</h1>
        <p>Categories (parent entity). Each category can have many products.</p>
    </div>

    <?php showFlash(); ?>

    <h2>Add Category</h2>
    <form class="app-form" action="core/handleForms.php" method="POST">
        <div class="form-grid">
            <div class="field full">
                <label>Category Name</label>
                <input type="text" name="category_name" required>
            </div>
            <div class="field full">
                <label>Description</label>
                <textarea name="description" rows="3"></textarea>
            </div>
        </div>
        <button type="submit" name="insertCategory">Add Category</button>
    </form>

    <h2><?= $search !== '' ? 'Search Results' : 'All Categories' ?></h2>

    <!-- SEARCH -->
    <form class="search-form" method="GET" action="index.php">
        <input type="text" name="search" placeholder="Search by name, description, ID or who added it..."
               value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Search</button>
        <?php if ($search !== ''): ?>
            <a href="index.php" class="clear-link">Clear</a>
        <?php endif; ?>
    </form>
    <?php if ($search !== ''): ?>
        <p class="result-info"><?= count($categories) ?> result(s) for “<?= htmlspecialchars($search) ?>”</p>
    <?php endif; ?>
    <div class="table-wrap">
    <table>
        <tr>
            <th>ID</th><th>Name</th><th>Description</th>
            <th>Added By</th><th>Date Added</th>
            <th>Last Updated By</th><th>Last Updated</th><th>Actions</th>
        </tr>
        <?php foreach ($categories as $c): ?>
        <tr>
            <td><?= $c['category_id'] ?></td>
            <td><?= htmlspecialchars($c['category_name']) ?></td>
            <td><?= htmlspecialchars($c['description']) ?></td>
            <td><?= htmlspecialchars($c['added_by_name']) ?></td>
            <td><?= formatDate($c['date_added']) ?></td>
            <td><?= $c['updated_by_name'] ? htmlspecialchars($c['updated_by_name']) : '—' ?></td>
            <td><?= formatDate($c['last_updated']) ?></td>
            <td>
                <a href="edit_category.php?id=<?= $c['category_id'] ?>&search=<?= urlencode($search) ?>">Edit</a>
                <form action="core/handleForms.php" method="POST" style="display:inline"
                      onsubmit="return confirm('Delete this category and ALL its products?');">
                    <input type="hidden" name="category_id" value="<?= $c['category_id'] ?>">
                    <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">
                    <button type="submit" name="deleteCategory">Delete</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($categories)): ?>
        <tr><td colspan="8" style="text-align:center"><?= $search !== '' ? 'No matching categories found.' : 'No categories yet.' ?></td></tr>
        <?php endif; ?>
    </table>
    </div>
</body>
</html>
