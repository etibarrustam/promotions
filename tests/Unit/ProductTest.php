<?php

namespace Unit;

use PHPUnit\Framework\TestCase;
use App\Core\Entities\Product;
use App\Core\Entities\Category;
use App\Core\ValueObjects\Price;

class ProductTest extends TestCase
{
    public function testApplyDiscountCalculatesCorrectPrice(): void
    {
        $category = $this->createMock(Category::class);
        $price = new Price(100);

        $product = new Product(1, 'sku001', 'Test Product', $category, $price);
        $product->applyDiscount(20);

        $this->assertEquals(80, $product->getFinalPrice());
        $this->assertEquals('20%', $product->getDiscountPercentage());
    }
}
