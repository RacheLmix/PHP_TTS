<?php
namespace XYZBank\Accounts;

class SavingsAccount extends BankAccount implements InterestBearing {
    use TransactionLogger;

    public const INTEREST_RATE = 0.05;

    public function deposit(float $amount): void {
        $this->balance += $amount;
        $this->logTransaction("Gửi tiền", $amount, $this->balance);
    }

    public function withdraw(float $amount): void {
        if (($this->balance - $amount) < 1000000) {
            echo "Không thể rút tiền. Số dư tối thiểu sau giao dịch phải ≥ 1.000.000 VNĐ.\n";
            return;
        }
        $this->balance -= $amount;
        $this->logTransaction("Rút tiền", $amount, $this->balance);
    }

    public function getAccountType(): string {
        return "Tiết kiệm";
    }

    public function calculateAnnualInterest(): float {
        return $this->balance * self::INTEREST_RATE;
    }
}