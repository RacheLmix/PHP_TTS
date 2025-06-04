<?php
// ===================== DỮ LIỆU GIẢ ĐỊNH =========================
$employees = [
    ['id' => 101, 'name' => 'Nguyễn Văn A', 'base_salary' => 5000000],
    ['id' => 102, 'name' => 'Trần Thị B', 'base_salary' => 6000000],
    ['id' => 103, 'name' => 'Lê Văn C', 'base_salary' => 5500000],
];

$timesheet = [
    101 => ['2025-03-01', '2025-03-02', '2025-03-04', '2025-03-05'],
    102 => ['2025-03-01', '2025-03-03', '2025-03-04'],
    103 => ['2025-03-02', '2025-03-03', '2025-03-04', '2025-03-05', '2025-03-06'],
];

$adjustments = [
    101 => ['allowance' => 500000, 'deduction' => 200000],
    102 => ['allowance' => 300000, 'deduction' => 100000],
    103 => ['allowance' => 400000, 'deduction' => 150000],
];

define('STANDARD_DAYS', 22);

// ===================== 1. TÍNH NGÀY CÔNG =========================
// Sử dụng count() và array_map()
$workingDays = array_map('count', $timesheet);

// ===================== 2. TÍNH LƯƠNG =============================
// Lương = (base_salary / STANDARD_DAYS) * days + allowance - deduction
function calculateNetSalaries($employees, $workingDays, $adjustments) {
    return array_map(function ($employee) use ($workingDays, $adjustments) {
        $id = $employee['id'];
        $salaryPerDay = $employee['base_salary'] / STANDARD_DAYS;
        $daysWorked = $workingDays[$id];
        $allowance = $adjustments[$id]['allowance'];
        $deduction = $adjustments[$id]['deduction'];
        return round($salaryPerDay * $daysWorked + $allowance - $deduction);
    }, $employees);
}
$netSalaries = calculateNetSalaries($employees, $workingDays, $adjustments);

// ===================== 3. BÁO CÁO TỔNG HỢP ========================
// Áp dụng compact(), array_keys(), array_values()
function generatePayrollTable($employees, $workingDays, $adjustments, $netSalaries) {
    return array_map(function ($employee) use ($workingDays, $adjustments, $netSalaries) {
        $id = $employee['id'];
        return [
            'id' => $employee['id'],
            'name' => $employee['name'],
            'days' => $workingDays[$id],
            'base_salary' => $employee['base_salary'],
            'allowance' => $adjustments[$id]['allowance'],
            'deduction' => $adjustments[$id]['deduction'],
            'net_salary' => $netSalaries[array_search($employee['id'], array_column($GLOBALS['employees'], 'id'))]
        ];
    }, $employees);
}
$payrollReport = generatePayrollTable($employees, $workingDays, $adjustments, $netSalaries);

// ===================== IN BẢNG LƯƠNG =============================
echo "BẢNG TỔNG HỢP LƯƠNG\n";
echo "Mã NV | Họ tên | Ngày công | Lương cơ bản | Phụ cấp | Khấu trừ | Lương thực lĩnh\n";
foreach ($payrollReport as $row) {
    echo implode(' | ', [
        $row['id'],
        $row['name'],
        $row['days'],
        number_format($row['base_salary']),
        number_format($row['allowance']),
        number_format($row['deduction']),
        number_format($row['net_salary']),
    ]) . "\n";
}

// ===================== 4. NGÀY CÔNG MAX/MIN =====================
$sortedDays = $workingDays;
asort($sortedDays); // tăng dần
$minId = array_key_first($sortedDays);
$maxId = array_key_last($sortedDays);
$minName = $employees[array_search($minId, array_column($employees, 'id'))]['name'];
$maxName = $employees[array_search($maxId, array_column($employees, 'id'))]['name'];

echo "\nNhân viên làm ít nhất: {$minName} ({$workingDays[$minId]} ngày công)\n";
echo "Nhân viên làm nhiều nhất: {$maxName} ({$workingDays[$maxId]} ngày công)\n";

// ===================== 5. CẬP NHẬT NHÂN VIÊN =====================
$newEmployees = [
    ['id' => 104, 'name' => 'Phạm Thị D', 'base_salary' => 6200000],
];
$employees = array_merge($employees, $newEmployees);

// Thêm ngày công
array_push($timesheet[101], '2025-03-07'); // thêm cuối
array_unshift($timesheet[102], '2025-03-08'); // thêm đầu
array_pop($timesheet[103]); // xóa cuối
array_shift($timesheet[103]); // xóa đầu

// ===================== 6. LỌC THEO NGÀY CÔNG >= 4 ===============
$filtered = array_filter($employees, function ($employee) use ($workingDays) {
    return isset($workingDays[$employee['id']]) && $workingDays[$employee['id']] >= 4;
});
echo "\nNhân viên đủ điều kiện xét thưởng:\n";
foreach ($filtered as $emp) {
    echo "- {$emp['name']} ({$workingDays[$emp['id']]} ngày công)\n";
}

// ===================== 7. KIỂM TRA DỮ LIỆU =======================
$checkDate = '2025-03-03';
$checkName = 'Trần Thị B';
$checkId = 101;

$nameToId = array_column($employees, 'id', 'name');
$empIdByName = $nameToId[$checkName] ?? null;
$hasWorked = ($empIdByName && isset($timesheet[$empIdByName]) && in_array($checkDate, $timesheet[$empIdByName])) ? 'Có' : 'Không';
$hasAdjustment = array_key_exists($checkId, $adjustments) ? 'Có' : 'Không';

echo "\n{$checkName} có đi làm ngày {$checkDate}: {$hasWorked}\n";
echo "Thông tin phụ cấp của nhân viên {$checkId} tồn tại: {$hasAdjustment}\n";

// ===================== 8. LÀM SẠCH DỮ LIỆU =======================
foreach ($timesheet as $id => &$days) {
    $days = array_unique($days); // loại bỏ ngày công trùng
}
unset($days);

// ===================== 9. TỔNG QUỸ LƯƠNG =========================
$totalSalary = array_sum($netSalaries);
echo "\nTổng quỹ lương tháng 03/2025: " . number_format($totalSalary) . " VND\n";
?>
