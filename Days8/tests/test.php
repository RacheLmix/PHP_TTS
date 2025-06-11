<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/XYZBank/Accounts/BankAccount.php';
require_once __DIR__ . '/../src/XYZBank/Accounts/SavingsAccount.php';
require_once __DIR__ . '/../src/XYZBank/Accounts/CheckingAccount.php';
require_once __DIR__ . '/../src/XYZBank/Accounts/Bank.php';
require_once __DIR__ . '/../src/XYZBank/Accounts/TransactionLogger.php';
require_once __DIR__ . '/../src/XYZBank/Accounts/InterestBearing.php';
require_once __DIR__ . '/../src/XYZBank/Accounts/AccountCollection.php';

use XYZBank\Accounts\SavingsAccount;
use XYZBank\Accounts\CheckingAccount;
use XYZBank\Accounts\AccountCollection;
use XYZBank\Accounts\Bank;

$acc1 = new SavingsAccount("10201122", "Nguyễn Thị A", 20000000);
$acc2 = new CheckingAccount("20301123", "Lê Văn B", 8000000);
$acc3 = new CheckingAccount("20401124", "Trần Minh C", 12000000);

$acc2->deposit(5000000);
$acc3->withdraw(2000000);

$interest = $acc1->calculateAnnualInterest();
echo "Lãi suất hàng năm cho {$acc1->getOwnerName()}: " . number_format($interest, 0, ',', '.') . " VNĐ\n\n";

$collection = new AccountCollection();
$collection->addAccount($acc1);
$collection->addAccount($acc2);
$collection->addAccount($acc3);

foreach ($collection as $acc) {
    echo "Tài khoản: {$acc->getAccountNumber()} | {$acc->getOwnerName()} | Loại: {$acc->getAccountType()} | Số dư: " . number_format($acc->getBalance(), 0, ',', '.') . " VNĐ\n";
}

echo "\nTổng số tài khoản đã tạo: " . Bank::$totalAccounts . "\n";
echo "Tên ngân hàng: " . Bank::getBankName() . "\n";