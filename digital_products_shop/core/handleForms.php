<?php
require_once 'dbConfig.php';
require_once 'models.php';
require_once 'imageUpload.php';

function go($page) {
    header("Location: ../" . $page);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    go('index.php');
}


// REGISTER
if (isset($_POST['registerUser'])) {
    $data = [
        'first_name' => trim($_POST['first_name']),
        'last_name'  => trim($_POST['last_name']),
        'age'        => ($_POST['age'] !== '') ? (int) $_POST['age'] : null,
        'address'    => ($_POST['address'] !== '') ? trim($_POST['address']) : null,
        'email'      => trim($_POST['email']),
        'username'   => trim($_POST['username']),
        'password'   => $_POST['password'],
    ];

    if (strlen($data['password']) < 6) {
        $_SESSION['error'] = "Password must be at least 6 characters.";
        go('register.php');
    }
    if ($data['password'] !== $_POST['confirm_password']) {
        $_SESSION['error'] = "Passwords do not match.";
        go('register.php');
    }
    if (userExists($pdo, $data['username'], $data['email'])) {
        $_SESSION['error'] = "That username or email is already registered.";
        go('register.php');
    }

    createUser($pdo, $data);
    $_SESSION['success'] = "Registration successful! You can now log in.";
    go('login.php');
}

if (isset($_POST['loginUser'])) {
    $user = getUserByUsername($pdo, trim($_POST['username']));

    if ($user && password_verify($_POST['password'], $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id']   = $user['user_id'];
        $_SESSION['full_name'] = $user['first_name'] . ' ' . $user['last_name'];
        $_SESSION['username']  = $user['username'];
        go('index.php');
    }

    $_SESSION['error'] = "Invalid username or password.";
    go('login.php');
}

$stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id'] ?? 0]);
if ($stmt->fetchColumn() == 0) {
    $_SESSION = [];
    go('login.php');
}
$uid = $_SESSION['user_id'];

$back = (isset($_POST['search']) && $_POST['search'] !== '')
        ? '?search=' . urlencode($_POST['search'])
        : '';

if (isset($_POST['insertCategory'])) {
    insertCategory($pdo, trim($_POST['category_name']), trim($_POST['description']), $uid);
    $_SESSION['success'] = "Category added.";
    go('index.php' . $back);
}

if (isset($_POST['updateCategory'])) {
    updateCategory($pdo, (int) $_POST['category_id'], trim($_POST['category_name']), trim($_POST['description']), $uid);
    $_SESSION['success'] = "Category updated.";
    go('index.php' . $back);
}

if (isset($_POST['deleteCategory'])) {
    deleteCategory($pdo, (int) $_POST['category_id']);
    $_SESSION['success'] = "Category deleted.";
    go('index.php' . $back);
}

function collectProductData() {
    return [
        'category_id' => (int) $_POST['category_id'],
        'title'       => trim($_POST['title']),
        'description' => trim($_POST['description']),
        'price'       => (float) $_POST['price'],
        'file_type'   => trim($_POST['file_type']),
    ];
}

if (isset($_POST['insertProduct'])) {
    $img = uploadProductImage($_FILES['image'] ?? null);
    if ($img['error'] || !$img['path']) {
        $_SESSION['error'] = $img['error'] ?: "Please attach an image for the product.";
        go('products.php');
    }
    insertProduct($pdo, collectProductData(), $img['path'], $uid);
    $_SESSION['success'] = "Product added.";
    go('products.php' . $back);
}

if (isset($_POST['updateProduct'])) {
    $productId = (int) $_POST['product_id'];
    $img = uploadProductImage($_FILES['image'] ?? null);
    if ($img['error']) {
        $_SESSION['error'] = $img['error'];
        go('edit_product.php?id=' . $productId);
    }
    $old = getProductById($pdo, $productId);
    updateProduct($pdo, $productId, collectProductData(), $uid, $img['path']);
    if ($img['path'] && $old) {
        deleteImageFile($old['image_path']);   // remove the replaced image
    }
    $_SESSION['success'] = "Product updated.";
    go('products.php' . $back);
}

if (isset($_POST['deleteProduct'])) {
    softDeleteProduct($pdo, (int) $_POST['product_id'], $uid);
    $_SESSION['success'] = "Product moved to the Recycle Bin.";
    go('products.php' . $back);
}

if (isset($_POST['restoreProduct'])) {
    restoreProduct($pdo, (int) $_POST['product_id']);
    $_SESSION['success'] = "Product restored.";
    go('recycle_bin.php');
}

go('index.php');
