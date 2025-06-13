<?php
include 'config.php';

$productId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($productId > 0) {
    $sql = "SELECT id, name, description, price, stock, image FROM products WHERE id = $productId";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo "<div class='product-detail-card'>";
        echo "<img src='" . htmlspecialchars($row['image']) . "' alt='" . htmlspecialchars($row['name']) . "'>";
        echo "<h3>" . htmlspecialchars($row['name']) . "</h3>";
        echo "<p><strong>Mô tả:</strong> " . nl2br(htmlspecialchars($row['description'])) . "</p>
";
        echo "<p><strong>Giá:</strong> " . number_format($row['price'], 2) . " VNĐ</p>
";
        echo "<p><strong>Tồn kho:</strong> " . $row['stock'] . "</p>
";
        echo "</div>";
    } else {
        echo "<p>Không tìm thấy sản phẩm.</p>";
    }
} else {
    echo "<p>ID sản phẩm không hợp lệ.</p>";
}

$conn->close();
?>