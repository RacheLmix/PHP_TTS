<?php
require_once 'db.php';
$db = new Database();
$conn = $db->connect();

$stmt = $conn->prepare("
    SELECT o.id, o.customer_name, SUM(oi.quantity * oi.price_at_order_time) AS total
    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    GROUP BY o.id
    ORDER BY o.id DESC
");
$stmt->execute();
$totals = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Tổng Tiền Đơn Hàng</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
    <style>
        body { font-family: 'Segoe UI'; padding: 40px; background: #eef2f7; }
        h1 { font-size: 28px; color: #222; }
        table { background: white; width: 100%; border-collapse: collapse; margin-top: 20px; border-radius: 10px; box-shadow: 0 6px 12px rgba(0,0,0,0.1); }
        th, td { padding: 12px; border-bottom: 1px solid #ddd; text-align: center; }
        th { background: #007BFF; color: white; }
    </style>
</head>
<body>
<h1 id="title">💰 Tổng Tiền Từng Đơn Hàng</h1>

<table id="table">
    <thead><tr><th>ID Đơn</th><th>Khách hàng</th><th>Tổng tiền (VNĐ)</th></tr></thead>
    <tbody>
    <?php foreach ($totals as $t): ?>
        <tr>
            <td><?= $t['id'] ?></td>
            <td><?= $t['customer_name'] ?></td>
            <td><?= number_format($t['total'], 0, ',', '.') ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<script>
    anime({ targets: '#title', translateX: [-50, 0], opacity: [0, 1], duration: 900 });
    anime({ targets: '#table', scale: [0.95, 1], opacity: [0, 1], delay: 300, duration: 800 });
</script>
</body>
</html>
