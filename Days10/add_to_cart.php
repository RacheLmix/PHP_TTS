<?php
session_start();

$response = ['success' => false, 'cartCount' => 0];

if (isset($_POST['product_id'])) {
    $productId = intval($_POST['product_id']);

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Add product to cart or increment quantity
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]++;
    } else {
        $_SESSION['cart'][$productId] = 1;
    }

    $response['success'] = true;
    $response['cartCount'] = array_sum($_SESSION['cart']);
}

header('Content-Type: application/json');
echo json_encode($response);
?>