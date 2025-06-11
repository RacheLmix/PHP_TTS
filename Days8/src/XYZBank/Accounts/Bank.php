<?php
namespace XYZBank\Accounts;

class Bank {
    public static int $totalAccounts = 0;

    public static function getBankName(): string {
        return "Ngân hàng XYZ";
    }
}