<?php

require_once 'AffiliatePartner.php';

class PremiumAffiliatePartner extends AffiliatePartner
{
    private float $bonusPerOrder;

    public function __construct($name, $email, $commissionRate, $bonusPerOrder, $isActive = true)
    {
        parent::__construct($name, $email, $commissionRate, $isActive);
        $this->bonusPerOrder = $bonusPerOrder;
    }

    public function calculateCommission(float $orderValue): float
    {
        return parent::calculateCommission($orderValue) + $this->bonusPerOrder;
    }

    public function getSummary(): string
    {
        return parent::getSummary() . " | Loại: CTV Cao Cấp | Bonus: " . number_format($this->bonusPerOrder) . "đ";
    }
}
