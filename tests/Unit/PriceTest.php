<?php

namespace Unit;

use PHPUnit\Framework\TestCase;
use App\Core\ValueObjects\Price;

class PriceTest extends TestCase
{
    public function testApplyDiscountReducesAmount(): void
    {
        $price = new Price(100);

        $price->applyDiscount(25);
        $this->assertEquals(75, $price->getAmount());
    }

    public function testApplyDiscountThrowsExceptionForInvalidPercentage(): void
    {
        $price = new Price(100);

        $this->expectException(\InvalidArgumentException::class);
        $price->applyDiscount(150); // Invalid percentage
    }
}
