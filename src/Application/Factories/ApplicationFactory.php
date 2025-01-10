<?php

namespace App\Application\Factories;

use App\Application\UseCases\ProductUseCase;
use App\Core\Contracts\ProductRepositoryInterface;
use App\Core\Services\DiscountService;

class ApplicationFactory
{
    public static function createProductUseCase(
        ProductRepositoryInterface $productRepository,
        DiscountService $discountService
    ): ProductUseCase {
        return new ProductUseCase($productRepository, $discountService);
    }
}
