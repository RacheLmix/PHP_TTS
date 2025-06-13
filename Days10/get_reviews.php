<?php
include 'config.php';

$productId = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;

if ($productId > 0) {
    $sql = "SELECT username, rating, comment, image_url, created_at FROM reviews WHERE product_id = $productId ORDER BY created_at DESC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<div class='review-item'>";
    if (!empty($row['image_url'])) {
        echo "<img src='" . htmlspecialchars($row['image_url']) . "' alt='Review Image' style='max-width: 100px; height: auto; margin-top: 10px;'>";
    }
            echo "<p><strong>" . htmlspecialchars($row['username']) . "</strong> - Đánh giá: " . htmlspecialchars($row['rating']) . "/5</p>";
            echo "<p>" . nl2br(htmlspecialchars($row['comment'])) . "</p>";
            echo "<small>Ngày: " . htmlspecialchars($row['created_at']) . "</small>";
            echo "</div>";
        }
    } else {
        echo "<p>Chưa có đánh giá nào cho sản phẩm này.</p>";
    }
} else {
    echo "<p>ID sản phẩm không hợp lệ.</p>";
}

$conn->close();
?>