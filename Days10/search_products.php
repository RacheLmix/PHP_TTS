<?php
include 'config.php';

$query = isset($_GET['query']) ? $_GET['query'] : '';

$output = '';
if (strlen($query) > 0) {
    $searchQuery = "%" . $conn->real_escape_string($query) . "%";
    $sql = "SELECT id, name, price, image FROM products WHERE name LIKE '$searchQuery' LIMIT 10";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $output .= '<div class="search-results-list">';
        while($row = $result->fetch_assoc()) {
            $output .= '<div class="search-result-item">';
            $output .= '<img src="' . htmlspecialchars($row['image']) . '" alt="' . htmlspecialchars($row['name']) . '">';
            $output .= '<span>' . htmlspecialchars($row['name']) . ' - ' . number_format($row['price'], 2) . ' VNĐ</span>';
            $output .= '</div>';
        }
        $output .= '</div>';
    } else {
        $output .= '<p>Không tìm thấy sản phẩm nào.</p>';
    }
}

echo $output;

$conn->close();
?>