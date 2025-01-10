<?php

namespace App\Core\ValueObjects;

use InvalidArgumentException;

class Price
{
    private float $originalAmount;

    public function __construct(private float $amount, private string $currency = 'EUR')
    {
        $this->originalAmount = $amount;
    }

    public function getOriginalAmount(): float
    {
        return $this->originalAmount;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getRoundedAmount(): int
    {
        return (int) round($this->amount);
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function applyDiscount(int $percentage): self
    {
        if ($percentage < 0 || $percentage > 100) {
            throw new InvalidArgumentException('Discount percentage must be between 0 and 100.');
        }

        $discountedAmount = (float) ($this->originalAmount * (1 - $percentage / 100));

        $this->amount = $discountedAmount;

        return new self($discountedAmount, $this->currency);
    }
}
