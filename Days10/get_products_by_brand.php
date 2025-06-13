<?php
include 'config.php';

if (isset($_GET['brand'])) {
    $brandName = $_GET['brand'];

    // Prepare and execute the SQL query to fetch products by brand
    // Assuming 'brand' is a column in your 'products' table
    $stmt = $conn->prepare("SELECT id, name, description, price, image FROM products WHERE brand = ?");
    $stmt->bind_param("s", $brandName);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<h3>Sản phẩm của thương hiệu: " . htmlspecialchars($brandName) . "</h3>";
        echo "<div class='product-list-by-brand'>";
        while ($row = $result->fetch_assoc()) {
            echo "<div class='product-item-by-brand' data-id='" . $row['id'] . "'>";
            echo "<h4>" . htmlspecialchars($row['name']) . "</h4>";
            echo "<img src='" . htmlspecialchars($row['image']) . "' alt='" . htmlspecialchars($row['name']) . "' style='width:100px; height:auto;'>";
            echo "<p>Giá: " . number_format($row['price'], 2) . " VNĐ</p>";
            echo "<p>Mô tả: " . htmlspecialchars($row['description']) . "</p>";
            echo "</div>";
        }
        echo "</div>";
    } else {
        echo "<p>Không tìm thấy sản phẩm nào cho thương hiệu này.</p>";
    }

    $stmt->close();
} else {
    echo "<p>Vui lòng cung cấp tên thương hiệu.</p>";
}

$conn->close();
?>