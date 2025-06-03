<?php

// Dữ liệu đầu vào
$users = [
    1 => ['name' => 'Alice', 'referrer_id' => null],
    2 => ['name' => 'Bob', 'referrer_id' => 1],
    3 => ['name' => 'Charlie', 'referrer_id' => 2],
    4 => ['name' => 'David', 'referrer_id' => 3],
    5 => ['name' => 'Eva', 'referrer_id' => 1],
];

$orders = [
    ['order_id' => 101, 'user_id' => 4, 'amount' => 200.0],
    ['order_id' => 102, 'user_id' => 3, 'amount' => 150.0],
    ['order_id' => 103, 'user_id' => 5, 'amount' => 300.0],
];

$commissionRates = [
    1 => 0.10, // Cấp 1: 10%
    2 => 0.05, // Cấp 2: 5%
    3 => 0.02, // Cấp 3: 2%
];

/**
 * Lấy chuỗi người giới thiệu bằng đệ quy
 * @param array $users Danh sách người dùng
 * @param int $userId ID người dùng cần tìm chuỗi giới thiệu
 * @return array Chuỗi ID người giới thiệu
 */
function getReferralChain(array $users, int $userId): array {
    static $chain = []; // Biến static để lưu chuỗi trong quá trình đệ quy

    if (!isset($users[$userId]) || $users[$userId]['referrer_id'] === null) {
        $result = $chain;
        $chain = []; // Reset sau mỗi lần duyệt
        return $result;
    }

    $chain[] = $users[$userId]['referrer_id'];
    return getReferralChain($users, $users[$userId]['referrer_id']);
}

/**
 * Tính hoa hồng cho một đơn hàng
 * @param array $order Đơn hàng
 * @param array $users Danh sách người dùng
 * @param array $commissionRates Tỷ lệ hoa hồng
 * @param callable $logCallback Hàm ghi log (callback ẩn danh)
 * @return array Danh sách hoa hồng
 */
function calculateOrderCommission(
    array $order,
    array $users,
    array $commissionRates,
    callable $logCallback
): array {
    $commissions = [];
    $referralChain = getReferralChain($users, $order['user_id']);

    foreach ($referralChain as $level => $referrerId) {
        $actualLevel = $level + 1;
        if (isset($commissionRates[$actualLevel])) {
            $commission = $order['amount'] * $commissionRates[$actualLevel];
            $commissions[$referrerId][] = [
                'order_id' => $order['order_id'],
                'buyer_id' => $order['user_id'],
                'buyer_name' => $users[$order['user_id']]['name'],
                'level' => $actualLevel,
                'amount' => $commission
            ];
            $logCallback(
                sprintf(
                    "Đơn hàng %d: Người dùng %s (%d) nhận hoa hồng cấp %d: %.2f",
                    $order['order_id'], $users[$referrerId]['name'], $referrerId,
                    $actualLevel, $commission
                )
            );
        }
    }

    return $commissions;
}

/**
 * Tính tổng hoa hồng cho tất cả đơn hàng
 * @param array $orders Danh sách đơn hàng
 * @param array $users Danh sách người dùng
 * @param array $commissionRates Tỷ lệ hoa hồng (mặc định)
 * @param callable|null $logCallback Hàm ghi log (tùy chọn)
 * @return array Báo cáo hoa hồng
 */
function calculateCommission(
    array $orders,
    array $users,
    array $commissionRates = [1 => 0.10, 2 => 0.05, 3 => 0.02],
    ?callable $logCallback = null
): array {
    $logCallback = $logCallback ?? function(string $message) {
        // Hàm ẩn danh mặc định để ghi log ra console
        echo $message . PHP_EOL;
    };

    $allCommissions = [];

    // Sử dụng array_map với hàm ẩn danh để xử lý từng đơn hàng
    array_map(function($order) use ($users, $commissionRates, $logCallback, &$allCommissions) {
        $commissions = calculateOrderCommission($order, $users, $commissionRates, $logCallback);
        foreach ($commissions as $userId => $commissionDetails) {
            if (!isset($allCommissions[$userId])) {
                $allCommissions[$userId] = [];
            }
            $allCommissions[$userId] = array_merge($allCommissions[$userId], $commissionDetails);
        }
    }, $orders);

    return $allCommissions;
}

/**
 * In báo cáo hoa hồng chi tiết
 * @param array ...$commissions Danh sách hoa hồng (variadic function)
 */
function printCommissionReport(array $commissions): void {
    global $users; // Lấy tên người dùng từ danh sách users

    foreach ($commissions as $userId => $details) {
        if (!isset($users[$userId])) continue; // Bỏ qua nếu user không tồn tại

        $total = array_sum(array_column($details, 'amount'));
        echo sprintf("\nBáo cáo hoa hồng cho %s (ID: %d):\n", $users[$userId]['name'], $userId);
        echo "----------------------------------------\n";

        foreach ($details as $detail) {
            echo sprintf(
                "Đơn hàng %d (Người mua: %s, Cấp %d): %.2f\n",
                $detail['order_id'], $detail['buyer_name'], $detail['level'], $detail['amount']
            );
        }

        echo sprintf("Tổng hoa hồng: %.2f\n", $total);
    }
}


// Thực thi chương trình
$commissions = calculateCommission($orders, $users, $commissionRates);
printCommissionReport($commissions);

?>