<?php
require_once 'db.php';
$db = new Database();
$conn = $db->connect();

$error = '';
$success = '';

$stmt = $conn->prepare("SELECT id, product_name FROM products");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $conn->prepare("SELECT id FROM orders ORDER BY id DESC");
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = $_POST['order_id'];
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    $stmt = $conn->prepare("SELECT stock_quantity, unit_price FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();

    if (!$product || $product['stock_quantity'] < $quantity) {
        $error = "Không đủ tồn kho!";
    } else {
        $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price_at_order_time) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$order_id, $product_id, $quantity, $product['unit_price']])) {
            $stmt = $conn->prepare("UPDATE products SET stock_quantity = stock_quantity - ? WHERE id = ?");
            $stmt->execute([$quantity, $product_id]);
            $success = "Đã thêm vào đơn hàng!";
        } else {
            $error = "Lỗi khi thêm vào đơn hàng.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Thêm SP vào Đơn Hàng</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
    <style>
        body { font-family: Arial; background: #f4f7fc; padding: 40px; }
        h1 { color: #333; }
        .form { background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        select, input, button { margin: 10px 0; padding: 10px; width: 100%; border-radius: 5px; border: 1px solid #ccc; }
        button { background: #007BFF; color: white; font-weight: bold; }
        .alert { padding: 10px; border-radius: 5px; margin-top: 10px; }
        .error { background: #e74c3c; color: white; }
        .success { background: #2ecc71; color: white; }
    </style>
</head>
<body>
<h1 id="title">📥 Thêm Sản Phẩm vào Đơn Hàng</h1>

<?php if ($error): ?><div class="alert error" id="errorBox"><?= $error ?></div><?php endif; ?>
<?php if ($success): ?><div class="alert success" id="successBox"><?= $success ?></div><?php endif; ?>

<form method="POST" class="form">
    <label>Chọn đơn hàng:</label>
    <select name="order_id" required>
        <option disabled selected>-- Chọn --</option>
        <?php foreach ($orders as $o): ?>
            <option value="<?= $o['id'] ?>">#<?= $o['id'] ?></option>
        <?php endforeach; ?>
    </select>

    <label>Chọn sản phẩm:</label>
    <select name="product_id" required>
        <option disabled selected>-- Chọn --</option>
        <?php foreach ($products as $p): ?>
            <option value="<?= $p['id'] ?>"><?= $p['product_name'] ?></option>
        <?php endforeach; ?>
    </select>

    <label>Số lượng:</label>
    <input type="number" name="quantity" required min="1">

    <button type="submit">➕ Thêm</button>
</form>

<script>
    anime({ targets: '#title', translateY: [-30, 0], opacity: [0, 1], duration: 800 });
    anime({ targets: '.form', scale: [0.9, 1], opacity: [0, 1], delay: 300, duration: 700 });
    <?php if ($error): ?> anime({ targets: '#errorBox', translateX: [-20, 0], opacity: [0, 1], duration: 500 }); <?php endif; ?>
    <?php if ($success): ?> anime({ targets: '#successBox', translateX: [20, 0], opacity: [0, 1], duration: 500 }); <?php endif; ?>
</script>
</body>
</html>
