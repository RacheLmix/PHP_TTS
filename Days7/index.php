<?php
require_once __DIR__ . '/src/AffiliatePartner.php';
require_once __DIR__ . '/src/PremiumAffiliatePartner.php';
require_once __DIR__ . '/src/AffiliateManager.php';

$orderValue = 2000000;

$ctv1 = new AffiliatePartner("Nguyễn Văn A", "a@gmail.com", 5);
$ctv2 = new AffiliatePartner("Trần Thị B", "b@gmail.com", 7);
$ctv3 = new PremiumAffiliatePartner("Lê Văn C", "c@gmail.com", 10, 50000);

$manager = new AffiliateManager();
$manager->addPartner($ctv1);
$manager->addPartner($ctv2);
$manager->addPartner($ctv3);

$partners = [$ctv1, $ctv2, $ctv3];
$totalCommission = $manager->totalCommission($orderValue);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hoa Hồng Cộng Tác Viên</title>
    <style>
        body { font-family: Arial; background: #f5f5f5; padding: 30px; }
        .partner { background: white; border-radius: 8px; padding: 15px; margin-bottom: 10px; box-shadow: 0 0 5px rgba(0,0,0,0.1); }
        .bonus { color: #2e8b57; font-weight: bold; }
        .total { font-size: 18px; margin-top: 20px; color: #d2691e; font-weight: bold; }
    </style>
</head>
<body>

<h2>🔸 Hoa hồng từng CTV (đơn hàng <?= number_format($orderValue) ?> VNĐ)</h2>

<?php foreach ($partners as $ctv): ?>
    <div class="partner">
        <?= $ctv->getSummary(); ?>
        <br>👉 Hoa hồng: <strong><?= number_format($ctv->calculateCommission($orderValue)); ?>đ</strong>
    </div>
<?php endforeach; ?>

<div class="total">💰 Tổng hoa hồng toàn hệ thống: <?= number_format($totalCommission); ?>đ</div>

</body>
</html>
