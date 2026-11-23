<?php
require_once 'core/dbConfig.php';
require_once 'core/auth.php';
require_once 'core/models.php';
requireLogin();

$deleted = getDeletedProducts($pdo);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Recycle Bin - Digital Products</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'nav.php'; ?>

    <div class="hero">
        <h1>Recycle Bin</h1>
        <p>Deleted products are kept here. You can see who deleted them and restore them.</p>
    </div>

    <?php showFlash(); ?>

    <h2>Deleted Products</h2>
    <div class="table-wrap">
    <table>
        <tr>
            <th>ID</th><th>Image</th><th>Category</th><th>Title</th><th>Price</th>
            <th>Deleted By</th><th>Date Deleted</th><th>Action</th>
        </tr>
        <?php foreach ($deleted as $p): ?>
        <tr>
            <td><?= $p['product_id'] ?></td>
            <td><img class="thumb" src="<?= htmlspecialchars($p['image_path']) ?>" alt="<?= htmlspecialchars($p['title']) ?>"></td>
            <td><?= htmlspecialchars($p['category_name']) ?></td>
            <td><?= htmlspecialchars($p['title']) ?></td>
            <td>₱<?= number_format($p['price'], 2) ?></td>
            <td><?= $p['deleted_by_name'] ? htmlspecialchars($p['deleted_by_name']) : '—' ?></td>
            <td><?= formatDate($p['date_deleted']) ?></td>
            <td>
                <form action="core/handleForms.php" method="POST" style="display:inline"
                      onsubmit="return confirm('Restore this product?');">
                    <input type="hidden" name="product_id" value="<?= $p['product_id'] ?>">
                    <button type="submit" name="restoreProduct">Restore</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($deleted)): ?>
        <tr><td colspan="8" style="text-align:center">The Recycle Bin is empty.</td></tr>
        <?php endif; ?>
    </table>
    </div>
</body>
</html>
