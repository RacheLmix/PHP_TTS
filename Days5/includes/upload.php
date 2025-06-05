<?php
function handleUpload($file) {
    $uploadDir = 'uploads/';
    if (!file_exists($uploadDir)) mkdir($uploadDir);

    $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
    if (!in_array($file['type'], $allowedTypes)) {
        return ['success' => false, 'error' => 'Định dạng không hợp lệ.'];
    }

    if ($file['size'] > 2 * 1024 * 1024) {
        return ['success' => false, 'error' => 'Tệp quá lớn. Tối đa 2MB.'];
    }

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $newName = 'upload_' . time() . '.' . $ext;
    $dest = $uploadDir . $newName;

    if (move_uploaded_file($file['tmp_name'], $dest)) {
        return ['success' => true, 'filename' => $newName];
    } else {
        return ['success' => false, 'error' => 'Lỗi khi upload file.'];
    }
}
