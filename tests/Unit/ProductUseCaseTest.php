<?php

namespace Unit;

use PHPUnit\Framework\TestCase;
use App\Application\UseCases\ProductUseCase;
use App\Core\Contracts\ProductRepositoryInterface;
use App\Core\Services\DiscountService;
use App\Core\Entities\Product;

class ProductUseCaseTest extends TestCase
{
    public function testGetFilteredProductsAppliesDiscounts(): void
    {
        $repository = $this->createMock(ProductRepositoryInterface::class);
        $discountService = $this->createMock(DiscountService::class);

        $repository->method('getFilteredProducts')->willReturn([
            $this->createMock(Product::class),
        ]);

        $discountService->expects($this->once())->method('applyDiscounts');

        $useCase = new ProductUseCase($repository, $discountService);
        $products = $useCase->getFilteredProducts(['category' => 'boots']);

        $this->assertCount(1, $products);
    }
}
