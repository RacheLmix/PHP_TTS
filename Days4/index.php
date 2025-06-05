<?php
session_start();

// --- RESET TOTALS BEFORE SUM ---
$GLOBALS['total_income'] = 0;
$GLOBALS['total_expense'] = 0;

// Tạo session lưu giao dịch nếu chưa có
if (!isset($_SESSION['transactions'])) {
    $_SESSION['transactions'] = [];
}

$sensitive_keywords = ['nợ xấu', 'vay nóng'];

$errors = [];
$warnings = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['transaction_name'] ?? '');
    $amount = $_POST['amount'] ?? '';
    $type = $_POST['type'] ?? '';
    $note = $_POST['note'] ?? '';
    $date = $_POST['date'] ?? '';

    if (!preg_match('/^[a-zA-Z0-9\s]+$/u', $name)) {
        $errors[] = "Tên giao dịch không hợp lệ (không chứa ký tự đặc biệt).";
    }

    if (!preg_match('/^\d+(\.\d{1,2})?$/', $amount) || $amount <= 0) {
        $errors[] = "Số tiền phải là số dương.";
    }

    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) || !DateTime::createFromFormat('Y-m-d', $date)) {
        $errors[] = "Ngày thực hiện không hợp lệ, phải theo định dạng yyyy-mm-dd.";
    } else {
        // Chuyển sang định dạng dd/mm/yyyy để hiển thị hoặc lưu
        $dateObj = DateTime::createFromFormat('Y-m-d', $date);
        $date = $dateObj->format('d/m/Y');
    }

    foreach ($sensitive_keywords as $word) {
        if (stripos($note, $word) !== false) {
            $warnings[] = "⚠️ Ghi chú có chứa từ khóa nhạy cảm: <strong>$word</strong>";
        }
    }

    if (empty($errors)) {
        $transaction = [
            'name' => htmlspecialchars($name),
            'amount' => (float)$amount,
            'type' => $type,
            'note' => htmlspecialchars($note),
            'date' => $date,
        ];

        $_SESSION['transactions'][] = $transaction;
        $name = '';
        $amount = '';
        $type = '';
        $note = '';
        $date = '';
    }
}

// Recalculate totals cleanly after potential addition
foreach ($_SESSION['transactions'] as $t) {
    if ($t['type'] === 'thu') {
        $GLOBALS['total_income'] += $t['amount'];
    } else {
        $GLOBALS['total_expense'] += $t['amount'];
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <title>Giao dịch tài chính</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 700px;
            margin: 30px auto;
            background: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            color: #333;
            animation: fadeIn 1s ease forwards;
        }

        h2, h3, h4 {
            color: #222;
            margin-top: 20px;
            animation: slideDown 0.6s ease forwards;
        }

        form {
            background: #fff;
            padding: 20px 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: box-shadow 0.3s ease;
            animation: fadeInUp 0.8s ease forwards;
        }

        form:hover {
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        label {
            display: block;
            margin: 12px 0 6px;
            font-weight: 600;
            letter-spacing: 0.03em;
            color: #444;
            transition: color 0.3s ease;
        }

        input[type="text"],
        input[type="number"],
        input[type="date"] {
            width: 100%;
            padding: 10px 12px;
            border: 1.8px solid #ccc;
            border-radius: 6px;
            font-size: 1rem;
            outline-offset: 2px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            font-family: inherit;
        }

        input[type="text"]:focus,
        input[type="number"]:focus,
        input[type="date"]:focus {
            border-color: #4CAF50;
            box-shadow: 0 0 8px rgba(76, 175, 80, 0.4);
        }

        input[type="radio"] {
            margin-left: 15px;
            cursor: pointer;
            transform: scale(1.1);
            transition: transform 0.2s ease;
        }

        input[type="radio"]:hover {
            transform: scale(1.3);
        }

        button {
            margin-top: 20px;
            padding: 12px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 700;
            font-size: 1.1rem;
            letter-spacing: 0.05em;
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.4);
            transition: background-color 0.3s ease, box-shadow 0.3s ease, transform 0.2s ease;
            user-select: none;
            animation: pulse 3s infinite;
        }

        button:hover {
            background-color: #45a049;
            box-shadow: 0 8px 25px rgba(69, 160, 73, 0.6);
            transform: scale(1.05);
        }

        .error, .warning {
            padding: 15px 20px;
            margin: 20px 0;
            border-radius: 8px;
            font-weight: 600;
            letter-spacing: 0.02em;
            animation: slideInLeft 0.5s ease forwards;
        }

        .error {
            background-color: #ffe5e5;
            color: #d8000c;
            border: 2px solid #d8000c;
        }

        .warning {
            background-color: #fff8e1;
            color: #8a6d3b;
            border: 2px solid #8a6d3b;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            background: #fff;
            box-shadow: 0 8px 25px rgba(0,0,0,0.07);
            border-radius: 10px;
            overflow: hidden;
            animation: fadeIn 1s ease forwards;
        }

        th {
            background-color: #f2f2f2;
            color: #333;
            padding: 15px 12px;
            font-weight: 700;
            text-transform: uppercase;
        }

        th, td {
            padding: 14px 12px;
            border: 1px solid #ddd;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        tr:hover td {
            background-color: #f9fefb;
        }

        @keyframes fadeIn {
            from {opacity: 0;}
            to {opacity: 1;}
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes pulse {
            0%, 100% {
                box-shadow: 0 5px 15px rgba(76, 175, 80, 0.4);
            }
            50% {
                box-shadow: 0 8px 25px rgba(76, 175, 80, 0.7);
            }
        }

    </style>
</head>
<body>
<h2>Nhập giao dịch tài chính</h2>

<form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>">
    <label>Tên giao dịch:
        <input type="text" name="transaction_name"
               value="<?= htmlspecialchars($name ?? '') ?>">
    </label><br>
    <label>Số tiền:
        <input type="number" name="amount" step="0.01"
               value="<?= htmlspecialchars($amount ?? '') ?>">
    </label><br>
    <label>Loại giao dịch:
        <input type="radio" name="type" value="thu" <?= (isset($type) && $type === 'thu') ? 'checked' : '' ?>> Thu
        <input type="radio" name="type" value="chi" <?= (isset($type) && $type === 'chi') ? 'checked' : '' ?>> Chi
    </label><br>
    <label>Ghi chú:
        <input type="text" name="note"
               value="<?= htmlspecialchars($note ?? '') ?>">
    </label><br>
    <label>Ngày thực hiện (dd-mm-yyyy):
        <input type="date" name="date"
               value="<?= htmlspecialchars($date ?? '') ?>">
    </label><br>
    <button type="submit">Gửi giao dịch</button>
</form>


<?php if (!empty($errors)): ?>
    <div class="error" id="error-box"><strong>Lỗi:</strong>
        <ul><?php foreach ($errors as $e) echo "<li>$e</li>"; ?></ul>
    </div>
<?php endif; ?>

<?php if (!empty($warnings)): ?>
    <div class="warning" id="warning-box"><strong>Cảnh báo:</strong>
        <ul><?php foreach ($warnings as $w) echo "<li>$w</li>"; ?></ul>
    </div>
<?php endif; ?>

<?php if (!empty($_SESSION['transactions'])): ?>
    <h3>Danh sách giao dịch</h3>
    <table id="transaction-table">
        <thead>
        <tr>
            <th>Tên giao dịch</th>
            <th>Số tiền</th>
            <th>Loại</th>
            <th>Ghi chú</th>
            <th>Ngày</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($_SESSION['transactions'] as $vascost): ?>
            <tr>
                <td><?= $vascost['name'] ?></td>
                <td><?= number_format($vascost['amount'], 2) ?></td>
                <td><?= ucfirst($vascost['type']) ?></td>
                <td><?= $vascost['note'] ?></td>
                <td><?= $vascost['date'] ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <h4>Tổng thu: <?= number_format($GLOBALS['total_income'], 2) ?></h4>
    <h4>Tổng chi: <?= number_format($GLOBALS['total_expense'], 2) ?></h4>
    <h4>Số dư: <?= number_format($GLOBALS['total_income'] - $GLOBALS['total_expense'], 2) ?></h4>
<?php endif; ?>

<script>
    // Fade in errors and warnings gently
    window.addEventListener('DOMContentLoaded', () => {
        const errorBox = document.getElementById('error-box');
        const warningBox = document.getElementById('warning-box');

        if (errorBox) {
            errorBox.style.opacity = '1';
        }
        if (warningBox) {
            warningBox.style.opacity = '1';
        }
    });
</script>

</body>
</html>
