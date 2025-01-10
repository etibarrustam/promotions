<?php

namespace App\Core\Entities;

use App\Core\ValueObjects\Price;

class Product
{
    private ?int $discountPercentage = null;

    public function __construct(
        private readonly int $id,
        private readonly string $sku,
        private readonly string $name,
        private readonly Category $category,
        private readonly Price $price
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function applyDiscount(int $percentage): void
    {
        $this->discountPercentage = $percentage;
        $this->price->applyDiscount($percentage);
    }

    public function getDiscountPercentage(): ?string
    {
        return $this->discountPercentage ? "{$this->discountPercentage}%" : null;
    }

    public function getOriginalPrice(): float
    {
        return $this->price->getOriginalAmount();
    }

    /**
     * Get the final price of the product after applying discounts.
     *
     * @return float
     */
    public function getFinalPrice(): float
    {
        if ($this->discountPercentage > 0) {
            return $this->price->getRoundedAmount();
        }

        return $this->price->getAmount();
    }

    public function getCurrency(): string
    {
        return $this->price->getCurrency();
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
