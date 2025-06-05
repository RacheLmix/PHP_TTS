<?php
include 'includes/header.php';
include 'includes/logger.php';
include 'includes/upload.php';

$toast = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'Không rõ hành động';
    $fileInfo = $_FILES['evidence'] ?? null;

    // Upload file nếu có
    $uploadedFile = '';
    if ($fileInfo && $fileInfo['error'] === 0) {
        $uploadResult = handleUpload($fileInfo);
        if ($uploadResult['success']) {
            $uploadedFile = $uploadResult['filename'];
            $toast = "Tệp đã được tải lên thành công!";
        } else {
            $toast = $uploadResult['error'];
        }
    }

    // Ghi log
    writeLog($action, $uploadedFile);
    $toast = "Hành động đã được ghi vào log.";
}
?>

<div class="container glass">
    <h2>Nhật ký hoạt động hệ thống</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="action" placeholder="Mô tả hành động..." required>
        <input type="file" name="evidence" accept=".jpg,.png,.pdf">
        <button type="submit">Ghi nhật ký</button>
    </form>
    <a class="button-link" href="view_log.php">Xem nhật ký theo ngày</a>
</div>

<div id="toast"><?= $toast ?></div>
<div class="spinner" id="spinner"></div>

<script src="assets/script.js"></script>
