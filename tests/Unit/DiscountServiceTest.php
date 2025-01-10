<?php

namespace Unit;

use PHPUnit\Framework\TestCase;
use App\Core\Services\DiscountService;
use App\Core\Services\CategoryDiscountHandler;
use App\Core\Services\ProductDiscountHandler;
use App\Core\Entities\Product;

class DiscountServiceTest extends TestCase
{
    public function testApplyDiscountsAppliesCategoryDiscount(): void
    {
        $categoryHandler = $this->createMock(CategoryDiscountHandler::class);
        $productHandler = $this->createMock(ProductDiscountHandler::class);

        $categoryHandler->method('apply')->willReturn(20);
        $productHandler->method('apply')->willReturn(0);
        $categoryHandler->setNext($productHandler);

        $service = new DiscountService($categoryHandler);

        $product = $this->createMock(Product::class);
        $product->expects($this->once())->method('applyDiscount')->with(20);

        $service->applyDiscounts($product);
    }
}
