<?php
require_once 'db.php';
$db = new Database();
$conn = $db->connect();

$error = '';
$success = '';

// Them 5 sp
//$sampleProducts = [
//    ['product_name' => 'Laptop XYZ', 'unit_price' => 15000000, 'stock_quantity' => 10],
//    ['product_name' => 'Chuột Không Dây', 'unit_price' => 350000, 'stock_quantity' => 50],
//    ['product_name' => 'Bàn Phím Cơ', 'unit_price' => 1200000, 'stock_quantity' => 30],
//    ['product_name' => 'Màn Hình 24 inch', 'unit_price' => 3000000, 'stock_quantity' => 20],
//    ['product_name' => 'Ổ cứng SSD 512GB', 'unit_price' => 2500000, 'stock_quantity' => 25],
//];
//
//$stmt = $conn->prepare("INSERT INTO products (product_name, unit_price, stock_quantity) VALUES (?, ?, ?)");
//
//foreach ($sampleProducts as $product) {
//    $stmt->execute([$product['product_name'], $product['unit_price'], $product['stock_quantity']]);
//}

// Them 1 sp Prepared Statement

//$productName = "Tai nghe Bluetooth";
//$unitPrice = 890000;
//$stockQty = 40;
//
//$stmt = $conn->prepare("INSERT INTO products (product_name, unit_price, stock_quantity) VALUES (?, ?, ?)");
//$stmt->execute([$productName, $unitPrice, $stockQty]);
//
//echo "ID của sản phẩm vừa thêm là: " . $conn->lastInsertId();


// Xử lý thêm sản phẩm
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_product'])) {
    $name = $_POST['product_name'];
    $price = $_POST['unit_price'];
    $stock = $_POST['stock_quantity'];

    if (empty($name) || $price < 0 || $stock < 0) {
        $error = "Thông tin không hợp lệ!";
    } else {
        $stmt = $conn->prepare("INSERT INTO products (product_name, unit_price, stock_quantity) VALUES (?, ?, ?)");
        if ($stmt->execute([$name, $price, $stock])) {
            $success = "Đã thêm sản phẩm thành công! ID mới: " . $conn->lastInsertId();
        } else {
            $error = "Lỗi khi thêm sản phẩm.";
        }
    }
}

// Xử lý xóa sản phẩm
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $success = "Đã xóa sản phẩm!";
}

// Xử lý cập nhật sản phẩm
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_product'])) {
    $id = $_POST['id'];
    $name = $_POST['product_name'];
    $price = $_POST['unit_price'];
    $stock = $_POST['stock_quantity'];

    $stmt = $conn->prepare("UPDATE products SET product_name = ?, unit_price = ?, stock_quantity = ? WHERE id = ?");
    if ($stmt->execute([$name, $price, $stock, $id])) {
        $success = "Cập nhật thành công!";
    } else {
        $error = "Cập nhật thất bại.";
    }
}

// Lọc dữ liệu
$filterQuery = "SELECT * FROM products WHERE 1";
$params = [];

if (!empty($_GET['filter_name'])) {
    $filterQuery .= " AND product_name LIKE ?";
    $params[] = "%" . $_GET['filter_name'] . "%";
}
if (!empty($_GET['filter_price'])) {
    $filterQuery .= " AND unit_price >= ?";
    $params[] = $_GET['filter_price'];
}
if (!empty($_GET['filter_stock'])) {
    $filterQuery .= " AND stock_quantity >= ?";
    $params[] = $_GET['filter_stock'];
}

$filterQuery .= " ORDER BY created_at DESC";
$stmt = $conn->prepare($filterQuery);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản Lý Sản Phẩm</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f5f7fa;
            padding: 40px;
            margin: 0;
            animation: fadeIn 0.8s ease;
        }

        .container {
            max-width: 1000px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #2a9d8f;
            margin-bottom: 20px;
        }

        .form input, .form button {
            padding: 10px;
            margin: 5px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        .form button {
            background-color: #2a9d8f;
            color: #fff;
            cursor: pointer;
            border: none;
        }

        .form button:hover {
            background-color: #21867a;
        }

        .filter-form {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
            margin: 30px 0 10px;
            padding: 15px;
            background: #f0f0f0;
            border-radius: 8px;
            align-items: center;
        }

        .filter-form input[type="text"],
        .filter-form input[type="number"] {
            flex: 1 1 200px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }

        .filter-form button {
            padding: 10px 20px;
            background-color: #2a9d8f;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .filter-form button:hover {
            background-color: #21867a;
        }

        .filter-form a {
            color: #dc3545;
            text-decoration: none;
            font-weight: bold;
            font-size: 14px;
            margin-left: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }

        table th {
            background-color: #2a9d8f;
            color: white;
        }

        .alert {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 6px;
            font-weight: bold;
        }

        .alert.success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert.error {
            background-color: #f8d7da;
            color: #721c24;
        }

        button[type="submit"] {
            font-size: 16px;
        }

        a {
            color: red;
            text-decoration: none;
            font-size: 18px;
            margin-left: 10px;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
<div class="container">
    <h1>📦 Quản Lý Sản Phẩm</h1>

    <?php if ($error): ?>
        <div class="alert error" id="errorBox"><?= $error ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert success" id="successBox"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST" class="form" id="addForm">
        <input type="text" name="product_name" placeholder="Tên sản phẩm" required>
        <input type="number" name="unit_price" placeholder="Giá (VNĐ)" step="0.01" required>
        <input type="number" name="stock_quantity" placeholder="Tồn kho" required>
        <button type="submit" name="add_product">➕ Thêm sản phẩm</button>
    </form>

    <form method="GET" class="filter-form">
        <input type="text" name="filter_name" placeholder="Lọc theo tên" value="<?= $_GET['filter_name'] ?? '' ?>">
        <input type="number" name="filter_price" placeholder="Giá từ" step="0.01" value="<?= $_GET['filter_price'] ?? '' ?>">
        <input type="number" name="filter_stock" placeholder="Tồn kho từ" value="<?= $_GET['filter_stock'] ?? '' ?>">
        <button type="submit">🔍 Lọc</button>
        <a href="products.php">🧹 Xóa lọc</a>
    </form>

    <table id="productTable">
        <thead>
        <tr>
            <th>ID</th>
            <th>Tên SP</th>
            <th>Giá</th>
            <th>Tồn kho</th>
            <th>Ngày tạo</th>
            <th>Hành động</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($products as $product): ?>
            <tr>
                <form method="POST">
                    <td><?= $product['id'] ?></td>
                    <td><input type="text" name="product_name" value="<?= $product['product_name'] ?>"></td>
                    <td><input type="number" name="unit_price" step="0.01" value="<?= $product['unit_price'] ?>"></td>
                    <td><input type="number" name="stock_quantity" value="<?= $product['stock_quantity'] ?>"></td>
                    <td><?= $product['created_at'] ?></td>
                    <td>
                        <input type="hidden" name="id" value="<?= $product['id'] ?>">
                        <button type="submit" name="update_product">💾</button>
                        <a href="?delete=<?= $product['id'] ?>" onclick="return confirm('Bạn chắc chắn xóa?')">🗑️</a>
                    </td>
                </form>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Anime.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
<script>
    anime({
        targets: '#productTable tbody tr',
        translateY: [30, 0],
        opacity: [0, 1],
        delay: anime.stagger(100),
        duration: 600,
        easing: 'easeOutExpo'
    });

    anime({
        targets: '#addForm',
        translateX: [-50, 0],
        opacity: [0, 1],
        duration: 800,
        easing: 'easeOutBack'
    });

    anime({
        targets: '.alert',
        scale: [0.9, 1],
        opacity: [0, 1],
        duration: 700,
        easing: 'easeOutElastic(1, .8)'
    });
</script>
</body>
</html>
