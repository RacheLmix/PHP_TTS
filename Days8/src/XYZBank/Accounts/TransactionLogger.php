<?php
namespace XYZBank\Accounts;

use DateTime;

trait TransactionLogger {
    public function logTransaction(string $type, float $amount, float $newBalance): void {
        $timestamp = (new DateTime())->format('Y-m-d H:i:s');
        echo "[{$timestamp}] Giao dịch: {$type} " . number_format($amount, 0, ',', '.') . " VNĐ | Số dư mới: " . number_format($newBalance, 0, ',', '.') . " VNĐ\n";
    }
}