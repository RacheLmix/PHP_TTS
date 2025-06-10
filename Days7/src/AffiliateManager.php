<?php

class AffiliateManager
{
    private $partners = [];

    public function addPartner(AffiliatePartner $affiliate): void
    {
        $this->partners[] = $affiliate;
    }

    public function listPartners(): void
    {
        echo "===== DANH SÁCH CỘNG TÁC VIÊN =====\n";
        foreach ($this->partners as $partner) {
            echo $partner->getSummary() . "\n";
        }
        echo "===================================\n";
    }

    public function totalCommission(float $orderValue): float
    {
        $total = 0;
        foreach ($this->partners as $partner) {
            if (method_exists($partner, 'calculateCommission')) {
                $total += $partner->calculateCommission($orderValue);
            }
        }
        return $total;
    }
}
