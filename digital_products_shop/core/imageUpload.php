<?php
function uploadProductImage($file) {
    
    if (!$file || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return ['path' => null, 'error' => null];
    }
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['path' => null, 'error' => "Image upload failed. Please try again."];
    }
    if ($file['size'] > 2 * 1024 * 1024) {
        return ['path' => null, 'error' => "Image must be 2MB or smaller."];
    }

    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp'];
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime  = $finfo->file($file['tmp_name']);
    if (!isset($allowed[$mime])) {
        return ['path' => null, 'error' => "Only JPG, PNG, GIF or WEBP images are allowed."];
    }

    $dir = __DIR__ . '/../uploads/';
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }

    $name = 'product_' . uniqid() . '.' . $allowed[$mime];
    if (!move_uploaded_file($file['tmp_name'], $dir . $name)) {
        return ['path' => null, 'error' => "Could not save the image. Check the uploads folder."];
    }
    return ['path' => 'uploads/' . $name, 'error' => null];
}

function deleteImageFile($path) {
    if ($path && strpos($path, 'uploads/product_') === 0) {
        $full = __DIR__ . '/../' . $path;
        if (is_file($full)) {
            unlink($full);
        }
    }
}
