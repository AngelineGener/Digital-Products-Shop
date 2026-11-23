<?php
function sessionUserIsValid($pdo) {
    if (!isset($_SESSION['user_id'])) {
        return false;
    }
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetchColumn() > 0;
}

function requireLogin() {
    global $pdo;
    if (!sessionUserIsValid($pdo)) {
        $_SESSION = [];
        header("Location: login.php");
        exit;
    }
}

function redirectIfLoggedIn() {
    if (isset($_SESSION['user_id'])) {
        header("Location: index.php");
        exit;
    }
}

function showFlash() {
    if (isset($_SESSION['success'])) {
        echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['success']) . '</div>';
        unset($_SESSION['success']);
    }
    if (isset($_SESSION['error'])) {
        echo '<div class="alert alert-error">' . htmlspecialchars($_SESSION['error']) . '</div>';
        unset($_SESSION['error']);
    }
}

function formatDate($date) {
    return $date ? date('M d, Y h:i A', strtotime($date)) : '—';
}
