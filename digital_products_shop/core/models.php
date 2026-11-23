<?php
/* ---------------- USERS ---------------- */
function getUserByUsername($pdo, $username) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    return $stmt->fetch();
}

function userExists($pdo, $username, $email) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    return $stmt->fetchColumn() > 0;
}

function createUser($pdo, $d) {
    $sql = "INSERT INTO users (first_name, last_name, age, address, email, username, password)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        $d['first_name'], $d['last_name'], $d['age'], $d['address'],
        $d['email'], $d['username'], password_hash($d['password'], PASSWORD_DEFAULT)
    ]);
}

/* ---------------- CATEGORIES (PARENT) ---------------- */
function getAllCategories($pdo, $search = '') {
    $sql = "SELECT c.*,
                   CONCAT(a.first_name, ' ', a.last_name) AS added_by_name,
                   CONCAT(u.first_name, ' ', u.last_name) AS updated_by_name
            FROM categories c
            JOIN users a ON c.added_by = a.user_id
            LEFT JOIN users u ON c.last_updated_by = u.user_id";
    $params = [];

    // SEARCH: match name, description, ID or the name of the user who added it
    if ($search !== '') {
        $sql .= " WHERE c.category_name LIKE ?
                     OR c.description LIKE ?
                     OR CAST(c.category_id AS CHAR) = ?
                     OR CONCAT(a.first_name, ' ', a.last_name) LIKE ?";
        $like = "%" . $search . "%";
        $params = [$like, $like, $search, $like];
    }

    $sql .= " ORDER BY c.category_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function getCategoryById($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE category_id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function insertCategory($pdo, $name, $description, $userId) {
    $stmt = $pdo->prepare("INSERT INTO categories (category_name, description, added_by) VALUES (?, ?, ?)");
    return $stmt->execute([$name, $description, $userId]);
}

function updateCategory($pdo, $id, $name, $description, $userId) {
    $stmt = $pdo->prepare("UPDATE categories
                           SET category_name = ?, description = ?, last_updated_by = ?, last_updated = NOW()
                           WHERE category_id = ?");
    return $stmt->execute([$name, $description, $userId, $id]);
}

function deleteCategory($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM categories WHERE category_id = ?");
    return $stmt->execute([$id]);
}

/* ---------------- PRODUCTS (CHILD) ---------------- */

// All ACTIVE products (items in the recycle bin are hidden)
function getAllProducts($pdo, $search = '') {
    $sql = "SELECT p.*, c.category_name,
                   CONCAT(a.first_name, ' ', a.last_name) AS added_by_name,
                   CONCAT(u.first_name, ' ', u.last_name) AS updated_by_name
            FROM products p
            JOIN categories c ON p.category_id = c.category_id
            JOIN users a ON p.added_by = a.user_id
            LEFT JOIN users u ON p.last_updated_by = u.user_id
            WHERE p.is_deleted = 0";
    $params = [];

    // SEARCH: match title, description, file type, category, ID or who added it
    if ($search !== '') {
        $sql .= " AND (p.title LIKE ?
                    OR p.description LIKE ?
                    OR p.file_type LIKE ?
                    OR c.category_name LIKE ?
                    OR CAST(p.product_id AS CHAR) = ?
                    OR CONCAT(a.first_name, ' ', a.last_name) LIKE ?)";
        $like = "%" . $search . "%";
        $params = [$like, $like, $like, $like, $search, $like];
    }

    $sql .= " ORDER BY p.product_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

// RECYCLE BIN: products that were deleted, with who deleted them and when
function getDeletedProducts($pdo) {
    $sql = "SELECT p.*, c.category_name,
                   CONCAT(d.first_name, ' ', d.last_name) AS deleted_by_name
            FROM products p
            JOIN categories c ON p.category_id = c.category_id
            LEFT JOIN users d ON p.deleted_by = d.user_id
            WHERE p.is_deleted = 1
            ORDER BY p.date_deleted DESC";
    return $pdo->query($sql)->fetchAll();
}

function getProductById($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function insertProduct($pdo, $d, $imagePath, $userId) {
    $sql = "INSERT INTO products (category_id, title, description, price, file_type, image_path, added_by)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$d['category_id'], $d['title'], $d['description'], $d['price'], $d['file_type'], $imagePath, $userId]);
}

// $imagePath = null means "keep the current image"
function updateProduct($pdo, $id, $d, $userId, $imagePath = null) {
    $sql = "UPDATE products
            SET category_id = ?, title = ?, description = ?, price = ?, file_type = ?,
                last_updated_by = ?, last_updated = NOW()";
    $params = [$d['category_id'], $d['title'], $d['description'], $d['price'], $d['file_type'], $userId];

    if ($imagePath !== null) {
        $sql .= ", image_path = ?";
        $params[] = $imagePath;
    }

    $sql .= " WHERE product_id = ?";
    $params[] = $id;

    $stmt = $pdo->prepare($sql);
    return $stmt->execute($params);
}

// DELETE = move to the recycle bin (record who deleted it and when)
function softDeleteProduct($pdo, $id, $userId) {
    $stmt = $pdo->prepare("UPDATE products
                           SET is_deleted = 1, deleted_by = ?, date_deleted = NOW()
                           WHERE product_id = ?");
    return $stmt->execute([$userId, $id]);
}

// RESTORE = bring it back to the products table
function restoreProduct($pdo, $id) {
    $stmt = $pdo->prepare("UPDATE products
                           SET is_deleted = 0, deleted_by = NULL, date_deleted = NULL
                           WHERE product_id = ?");
    return $stmt->execute([$id]);
}
