<?php
//Các hằng số
const COMMISSION_RATE = 0.2;
const VAT_RATE = 0.1;
//Dữ liệu đầu vào
$campaignName = "Spring Sale 2025";
$orderCount = (int) "150";
$productPrice = (float) 99.99;
$productType = "Thời trang";
$campaignEnded = true;
// Danh sách đơn hàng (ID => giá trị)
$orderList = [
    "ID001" => 99.99,
    "ID002" => 49.99,
    "ID003" => 129.50,
    "ID004" => 75.00,
    "ID005" => 109.99
];

//Tính toán
$totalRevenue = $productPrice * $orderCount;
$commissionCost = $totalRevenue * COMMISSION_RATE;
$vat = $totalRevenue * VAT_RATE;
$profit = $totalRevenue - $commissionCost - $vat;

// Đánh giá hiệu quả
if ($profit > 0) {
    $evaluation = "Chiến dịch thành công";
} elseif ($profit == 0) {
    $evaluation = "Chiến dịch hòa vốn";
} else {
    $evaluation = "Chiến dịch thất bại";
}

switch ($productType) {
    case "Điện tử":
        $productMessage = "Sản phẩm Điện tử có tiềm năng cao.";
        break;
    case "Thời trang":
        $productMessage = "Sản phẩm Thời trang có doanh thu ổn định.";
        break;
    case "Gia dụng":
        $productMessage = "Sản phẩm Gia dụng được ưa chuộng.";
        break;
    default:
        $productMessage = "Loại sản phẩm chưa xác định.";
        break;
}

$actualRevenue = 0;
for ($i = 0; $i < count($orderList); $i++) {
    $actualRevenue += array_values($orderList)[$i];
}


// Debug sử dụng magic constants
echo "Debug info: File: " . __FILE__ . " - Line: " . __LINE__ . "\n\n";

echo "Tên chiến dịch: $campaignName\n";
echo "Trạng thái: " . ($campaignEnded ? "Đã kết thúc" : "Đang chạy") . "\n";
echo "Loại sản phẩm: $productType\n";
echo "----------------------------\n";
echo "Tổng doanh thu (ước tính): " . number_format($totalRevenue, 2) . " USD\n";
echo "Chi phí hoa hồng: " . number_format($commissionCost, 2) . " USD\n";
echo "VAT: " . number_format($vat, 2) . " USD\n";
echo "Lợi nhuận: " . number_format($profit, 2) . " USD\n";
echo "Đánh giá: $evaluation\n";
echo "$productMessage\n";
echo "----------------------------\n";
echo "Chi tiết đơn hàng:\n";

// Hiển thị chi tiết từng đơn hàng
foreach ($orderList as $id => $value) {
    echo "- Đơn hàng $id: " . number_format($value, 2) . " USD\n";
}

// Hiển thị danh sách đơn hàng dưới dạng mảng (debug)
echo "\nDanh sách đơn hàng:\n";
print_r($orderList);

// Thông báo tổng kết
echo "\nChiến dịch $campaignName đã " . ($campaignEnded ? "kết thúc" : "chưa kết thúc") . " với lợi nhuận: " . number_format($profit, 2) . " USD\n";



?>