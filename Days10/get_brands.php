<?php
header('Content-Type: text/html; charset=utf-8');

$categoryFilter = isset($_GET['category']) ? $_GET['category'] : '';

$xml = simplexml_load_file('brands.xml');

$output = '';
if ($xml) {
    $output .= '<div>'; // Start a container for brands
    foreach ($xml->category as $category) {
        if ($categoryFilter === '' || (string)$category['name'] === $categoryFilter) {
            foreach ($category->brand as $brand) {
                // Change to div with data-brand-name for click handling in index.php
                $output .= '<div class="brand-item" data-brand-name="' . htmlspecialchars((string)$brand) . '">' . htmlspecialchars((string)$brand) . '</div>';
            }
        }
    }
    $output .= '</div>'; // End container
} else {
    $output = '<p>Không thể tải dữ liệu thương hiệu.</p>';
}

echo $output;
?>