<?php
require_once 'db.php';
$db = new Database();
$conn = $db->connect();

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer = trim($_POST['customer_name']);
    $note = $_POST['note'] ?? null;

    if ($customer === '') {
        $error = "❌ Tên khách hàng không được để trống!";
    } else {
        $stmt = $conn->prepare("INSERT INTO orders (order_date, customer_name, note) VALUES (CURDATE(), ?, ?)");
        if ($stmt->execute([$customer, $note])) {
            $success = "✅ Tạo đơn hàng thành công!";
        } else {
            $error = "❌ Không thể tạo đơn hàng.";
        }
    }
}

$stmt = $conn->prepare("SELECT * FROM orders ORDER BY order_date DESC, id DESC");
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đơn Hàng - TechFactory</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            background: #f3f5f9;
            margin: 40px auto;
            max-width: 960px;
            padding: 20px;
            color: #333;
        }

        h1 {
            font-size: 32px;
            text-align: center;
            margin-bottom: 40px;
            color: #2c3e50;
        }

        .form {
            background: #ffffff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }

        input, textarea, button {
            width: 100%;
            padding: 12px 14px;
            margin-top: 12px;
            font-size: 15px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        input:focus, textarea:focus {
            outline: none;
            border-color: #007BFF;
            box-shadow: 0 0 0 3px rgba(0,123,255,0.2);
        }

        button {
            background-color: #007BFF;
            color: #fff;
            font-weight: bold;
            border: none;
            transition: background 0.3s ease;
            margin-top: 18px;
        }

        button:hover {
            background-color: #0056b3;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
            font-size: 15px;
        }

        .error {
            background-color: #f44336;
            color: #fff;
        }

        .success {
            background-color: #4CAF50;
            color: #fff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.08);
        }

        th, td {
            padding: 14px 16px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background-color: #007BFF;
            color: white;
            font-weight: 600;
        }

        tr:hover {
            background-color: #f2f6ff;
        }

        @media (max-width: 768px) {
            body { margin: 20px; }
            table, thead, tbody, th, td, tr {
                display: block;
            }

            thead {
                display: none;
            }

            tr {
                margin-bottom: 15px;
                border: 1px solid #ddd;
                border-radius: 10px;
                padding: 10px;
            }

            td {
                padding: 10px;
                position: relative;
                padding-left: 50%;
            }

            td:before {
                position: absolute;
                top: 10px;
                left: 15px;
                width: 45%;
                font-weight: bold;
                color: #555;
            }

            td:nth-of-type(1):before { content: "ID"; }
            td:nth-of-type(2):before { content: "Ngày"; }
            td:nth-of-type(3):before { content: "Khách hàng"; }
            td:nth-of-type(4):before { content: "Ghi chú"; }
        }
    </style>
</head>
<body>

<h1 id="title">📦 Quản lý đơn hàng</h1>

<?php if ($error): ?>
    <div class="alert error" id="errorBox"><?= $error ?></div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="alert success" id="successBox"><?= $success ?></div>
<?php endif; ?>

<form method="POST" class="form">
    <label for="customer_name">Tên khách hàng:</label>
    <input type="text" id="customer_name" name="customer_name" placeholder="Nguyễn Văn A..." required autofocus>

    <label for="note">Ghi chú:</label>
    <textarea id="note" name="note" rows="3" placeholder="Ghi chú đơn hàng (nếu có)..."></textarea>

    <button type="submit">📝 Tạo đơn hàng</button>
</form>

<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Ngày</th>
        <th>Khách hàng</th>
        <th>Ghi chú</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($orders as $order): ?>
        <tr>
            <td>#<?= $order['id'] ?></td>
            <td><?= $order['order_date'] ?></td>
            <td><?= htmlspecialchars($order['customer_name']) ?></td>
            <td><?= htmlspecialchars($order['note']) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<script>
    anime({
        targets: '#title',
        opacity: [0, 1],
        translateY: [-30, 0],
        duration: 900,
        easing: 'easeOutExpo'
    });

    anime({
        targets: '.form',
        opacity: [0, 1],
        scale: [0.95, 1],
        delay: 200,
        duration: 700,
        easing: 'easeOutBack'
    });

    anime({
        targets: 'table',
        opacity: [0, 1],
        translateY: [20, 0],
        delay: 400,
        duration: 800,
        easing: 'easeOutQuad'
    });

    <?php if ($error): ?>
    anime({
        targets: '#errorBox',
        translateX: [-20, 0],
        opacity: [0, 1],
        duration: 600,
        easing: 'easeOutExpo'
    });
    <?php endif; ?>

    <?php if ($success): ?>
    anime({
        targets: '#successBox',
        translateX: [20, 0],
        opacity: [0, 1],
        duration: 600,
        easing: 'easeOutExpo'
    });
    <?php endif; ?>
</script>
</body>
</html>
