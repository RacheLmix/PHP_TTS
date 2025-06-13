<?php
include 'config.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'] ?? null;
    $username = $_POST['username'] ?? null;
    $rating = $_POST['rating'] ?? null;
    $comment = $_POST['comment'] ?? null;

    if ($product_id && $username && $rating && $comment) {
        // Validate inputs
        $product_id = (int)$product_id;
        $rating = (int)$rating;
        $username = htmlspecialchars($username);
        $comment = htmlspecialchars($comment);

        if ($rating < 1 || $rating > 5) {
            $response['message'] = 'Đánh giá phải từ 1 đến 5 sao.';
        } else {
            try {
                $image_url = NULL;
if (isset($_FILES['review_image']) && $_FILES['review_image']['error'] == UPLOAD_ERR_OK) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["review_image"]["name"]);
    if (move_uploaded_file($_FILES["review_image"]["tmp_name"], $target_file)) {
        $image_url = $target_file;
    }
}

$stmt = $conn->prepare("INSERT INTO reviews (product_id, username, rating, comment, image_url) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("isiss", $product_id, $username, $rating, $comment, $image_url);

                if ($stmt->execute()) {
                    $response['success'] = true;
                    $response['message'] = 'Đánh giá đã được gửi thành công.';
                } else {
                    $response['message'] = 'Lỗi khi thêm đánh giá vào cơ sở dữ liệu: ' . $stmt->error;
                }
                $stmt->close();
            } catch (mysqli_sql_exception $e) {
                $response['message'] = 'Lỗi cơ sở dữ liệu: ' . $e->getMessage();
            }
        }
    } else {
        $response['message'] = 'Vui lòng điền đầy đủ thông tin đánh giá.';
    }
} else {
    $response['message'] = 'Phương thức yêu cầu không hợp lệ.';
}

$conn->close();
echo json_encode($response);
?>