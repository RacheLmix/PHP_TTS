<?php

class AffiliatePartner
{
    const PLATFORM_NAME = "VietLink Affiliate";

    protected string $name;
    protected string $email;
    protected float $commissionRate;
    protected bool $isActive;

    public function __construct($name, $email, $commissionRate, $isActive = true)
    {
        $this->name = $name;
        $this->email = $email;
        $this->commissionRate = $commissionRate;
        $this->isActive = $isActive;
    }

    public function __destruct()
    {
        echo "[LOG] {$this->name} đã bị huỷ khỏi hệ thống.\n";
    }

    public function calculateCommission(float $orderValue): float
    {
        return $orderValue * ($this->commissionRate / 100);
    }

    public function getSummary(): string
    {
        return "[$this->name - " . self::PLATFORM_NAME . "] - Email: $this->email | Tỷ lệ hoa hồng: $this->commissionRate% | Trạng thái: " . ($this->isActive ? "Hoạt động" : "Ngưng hoạt động");
    }
}
