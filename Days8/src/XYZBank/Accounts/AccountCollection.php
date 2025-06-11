<?php
namespace XYZBank\Accounts;

use IteratorAggregate;
use ArrayIterator;

class AccountCollection implements IteratorAggregate {
    private array $accounts = [];

    public function addAccount(BankAccount $account): void {
        $this->accounts[] = $account;
    }

    public function getIterator(): ArrayIterator {
        return new ArrayIterator($this->accounts);
    }

    public function getHighValueAccounts(): array {
        return array_filter($this->accounts, fn($acc) => $acc->getBalance() >= 10000000);
    }
}