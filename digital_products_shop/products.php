<?php
require_once 'core/dbConfig.php';
require_once 'core/auth.php';
require_once 'core/models.php';
requireLogin();

$categories = getAllCategories($pdo);
$search     = trim($_GET['search'] ?? '');
$products   = getAllProducts($pdo, $search);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Products - Digital Products</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'nav.php'; ?>

    <div class="hero">
        <h1>Products</h1>
        <p>Products (child entity). Each product belongs to one category.</p>
    </div>

    <?php showFlash(); ?>

    <h2>Add Product</h2>
    <?php if (empty($categories)): ?>
        <div class="alert alert-error">Add a category first before adding products.</div>
    <?php else: ?>
    <form class="app-form" action="core/handleForms.php" method="POST" enctype="multipart/form-data">
        <div class="form-grid">
            <div class="field">
                <label>Category</label>
                <select name="category_id" required>
                    <option value="">-- Select category --</option>
                    <?php foreach ($categories as $c): ?>
                        <option value="<?= $c['category_id'] ?>"><?= htmlspecialchars($c['category_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="field">
                <label>Title</label>
                <input type="text" name="title" required>
            </div>
            <div class="field">
                <label>Price</label>
                <div class="peso-input"><span>₱</span><input type="number" step="0.01" min="0" name="price" placeholder="0.00" required></div>
            </div>
            <div class="field">
                <label>File Type</label>
                <input type="text" name="file_type" placeholder="PDF, Canva, Notion...">
            </div>
            <div class="field full">
                <label>Product Image</label>
                <input type="file" name="image" accept="image/png, image/jpeg, image/gif, image/webp" required>
            </div>
            <div class="field full">
                <label>Description</label>
                <textarea name="description" rows="3"></textarea>
            </div>
        </div>
        <button type="submit" name="insertProduct">Add Product</button>
    </form>
    <?php endif; ?>

    <h2><?= $search !== '' ? 'Search Results' : 'All Products' ?></h2>

    <!-- SEARCH -->
    <form class="search-form" method="GET" action="products.php">
        <input type="text" name="search" placeholder="Search by title, category, file type, ID or who added it..."
               value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Search</button>
        <?php if ($search !== ''): ?>
            <a href="products.php" class="clear-link">Clear</a>
        <?php endif; ?>
    </form>
    <?php if ($search !== ''): ?>
        <p class="result-info"><?= count($products) ?> result(s) for “<?= htmlspecialchars($search) ?>”</p>
    <?php endif; ?>
    <div class="table-wrap">
    <table>
        <tr>
            <th>ID</th><th>Image</th><th>Category</th><th>Title</th><th>Price</th><th>File Type</th>
            <th>Added By</th><th>Date Added</th>
            <th>Last Updated By</th><th>Last Updated</th><th>Actions</th>
        </tr>
        <?php foreach ($products as $p): ?>
        <tr>
            <td><?= $p['product_id'] ?></td>
            <td><img class="thumb" src="<?= htmlspecialchars($p['image_path']) ?>" alt="<?= htmlspecialchars($p['title']) ?>"></td>
            <td><?= htmlspecialchars($p['category_name']) ?></td>
            <td><?= htmlspecialchars($p['title']) ?></td>
            <td>₱<?= number_format($p['price'], 2) ?></td>
            <td><?= htmlspecialchars($p['file_type']) ?></td>
            <td><?= htmlspecialchars($p['added_by_name']) ?></td>
            <td><?= formatDate($p['date_added']) ?></td>
            <td><?= $p['updated_by_name'] ? htmlspecialchars($p['updated_by_name']) : '—' ?></td>
            <td><?= formatDate($p['last_updated']) ?></td>
            <td>
                <a href="edit_product.php?id=<?= $p['product_id'] ?>&search=<?= urlencode($search) ?>">Edit</a>
                <form action="core/handleForms.php" method="POST" style="display:inline"
                      onsubmit="return confirm('Move this product to the Recycle Bin?');">
                    <input type="hidden" name="product_id" value="<?= $p['product_id'] ?>">
                    <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">
                    <button type="submit" name="deleteProduct">Delete</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($products)): ?>
        <tr><td colspan="11" style="text-align:center"><?= $search !== '' ? 'No matching products found.' : 'No products yet.' ?></td></tr>
        <?php endif; ?>
    </table>
    </div>
</body>
</html>
