<?php
session_start();

// Tạo danh sách sách mẫu
$book_list = [
    ["title" => "Clean Code", "price" => 150000],
    ["title" => "Design Patterns", "price" => 200000],
    ["title" => "Refactoring", "price" => 180000]
];

// Đọc cookie nếu có
$email_cookie = $_COOKIE['customer_email'] ?? '';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validate và lọc dữ liệu
        $book_title = filter_input(INPUT_POST, 'book_title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $phone = filter_input(INPUT_POST, 'phone', FILTER_VALIDATE_REGEXP, [
            "options" => ["regexp" => "/^[0-9]{9,11}$/"]
        ]);
        $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);

        if (!$book_title || !$quantity || !$email || !$phone || !$address) {
            throw new Exception("Dữ liệu không hợp lệ. Vui lòng kiểm tra lại.");
        }

        // Ghi cookie
        setcookie('customer_email', $email, time() + (86400 * 7));

        // Tìm giá sách
        $price = 0;
        foreach ($book_list as $book) {
            if ($book['title'] === $book_title) {
                $price = $book['price'];
                break;
            }
        }

        // Lưu vào session
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $exists = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['title'] === $book_title) {
                $item['quantity'] += $quantity;
                $exists = true;
                break;
            }
        }
        if (!$exists) {
            $_SESSION['cart'][] = [
                "title" => $book_title,
                "quantity" => $quantity,
                "price" => $price
            ];
        }

        // Lưu vào file JSON
        $cart_data = [
            "customer_email" => $email,
            "products" => $_SESSION['cart'],
            "customer_phone" => $phone,
            "customer_address" => $address,
            "total_amount" => array_reduce($_SESSION['cart'], fn($sum, $item) => $sum + ($item['price'] * $item['quantity']), 0),
            "created_at" => date('Y-m-d H:i:s')
        ];

        if (!file_put_contents("cart_data.json", json_encode($cart_data, JSON_PRETTY_PRINT))) {
            throw new Exception("Không thể ghi vào file giỏ hàng.");
        }

        header('Location: confirm.php');
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage();
        file_put_contents("error_log.txt", "[" . date('Y-m-d H:i:s') . "] " . $error . "\n", FILE_APPEND);
    }
}
?>

<!-- HTML -->
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Giỏ hàng sách đơn giản</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container py-5">
<h2>🛒 Thêm sách vào giỏ hàng</h2>

<?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="post" class="mt-4">
    <div class="mb-3">
        <label>Sách:</label>
        <select name="book_title" class="form-control" required>
            <?php foreach ($book_list as $book): ?>
                <option value="<?= htmlspecialchars($book['title']) ?>"><?= $book['title'] ?> - <?= number_format($book['price']) ?>đ</option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label>Số lượng:</label>
        <input type="number" name="quantity" class="form-control" min="1" >
    </div>
    <div class="mb-3">
        <label>Email:</label>
        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($email_cookie) ?>" >
    </div>
    <div class="mb-3">
        <label>Số điện thoại:</label>
        <input type="text" name="phone" class="form-control" >
    </div>
    <div class="mb-3">
        <label>Địa chỉ:</label>
        <textarea name="address" class="form-control" ></textarea>
    </div>
    <button type="submit" class="btn btn-primary">➕ Thêm vào giỏ</button>
    <a href="confirm.php" class="btn btn-success">✅ Xác nhận đặt hàng</a>
    <a href="clear_cart.php" class="btn btn-danger">🗑️ Xoá giỏ hàng</a>
</form>
</body>
</html>
