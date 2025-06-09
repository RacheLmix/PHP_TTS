<?php
session_start();

// Đọc lại file JSON nếu có
$cartFile = 'cart_data.json';
$cartData = [];

if (file_exists($cartFile)) {
    $json = file_get_contents($cartFile);
    $cartData = json_decode($json, true); // Dạng mảng
}

// Kiểm tra tồn tại các phần tử trước khi truy cập
$email = isset($cartData['customer_email']) ? htmlspecialchars($cartData['customer_email']) : 'Chưa có';
$phone = isset($cartData['customer_phone']) ? htmlspecialchars($cartData['customer_phone']) : 'Chưa có';
$address = isset($cartData['customer_address']) ? htmlspecialchars($cartData['customer_address']) : 'Chưa có';
$products = isset($cartData['products']) ? $cartData['products'] : [];
$total = isset($cartData['total_amount']) ? $cartData['total_amount'] : 0;
$created_at = isset($cartData['created_at']) ? $cartData['created_at'] : date('Y-m-d H:i:s');
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xác nhận đơn hàng</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f2f2f2;
            margin: 0;
            padding: 20px;
        }

        .container {
            background: #fff;
            padding: 30px;
            max-width: 900px;
            margin: auto;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }

        h2 {
            color: #007bff;
            margin-bottom: 20px;
        }

        h3 {
            color: #555;
            margin-top: 30px;
        }

        p {
            margin: 5px 0;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }

        table th {
            background-color: #f8f9fa;
        }

        table tr:nth-child(even) {
            background-color: #fdfdfd;
        }

        .total-row td {
            font-weight: bold;
            background-color: #f1f1f1;
        }

        .empty {
            text-align: center;
            font-style: italic;
            color: #999;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>📦 Xác nhận đơn hàng</h2>

    <h3>Thông tin khách hàng:</h3>
    <p><strong>Email:</strong> <?= $email ?></p>
    <p><strong>Điện thoại:</strong> <?= $phone ?></p>
    <p><strong>Địa chỉ:</strong> <?= $address ?></p>
    <p><strong>Thời gian:</strong> <?= $created_at ?></p>

    <h3>Danh sách sách:</h3>
    <table>
        <thead>
        <tr>
            <th>Sách</th>
            <th>Đơn giá</th>
            <th>Số lượng</th>
            <th>Thành tiền</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($products)) : ?>
            <?php foreach ($products as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['title']) ?></td>
                    <td><?= number_format($item['price']) ?>đ</td>
                    <td><?= (int)$item['quantity'] ?></td>
                    <td><?= number_format($item['price'] * $item['quantity']) ?>đ</td>
                </tr>
            <?php endforeach; ?>
            <tr class="total-row">
                <td colspan="3">Tổng cộng:</td>
                <td><?= number_format($total) ?>đ</td>
            </tr>
        <?php else: ?>
            <tr><td colspan="4" class="empty">Không có sản phẩm nào trong giỏ hàng.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
