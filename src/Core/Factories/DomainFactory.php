<?php

namespace App\Core\Factories;

use App\Core\Contracts\DiscountRepositoryInterface;
use App\Core\Services\CategoryDiscountHandler;
use App\Core\Services\DiscountService;
use App\Core\Services\ProductDiscountHandler;

class DomainFactory
{
    public static function createDiscountService(DiscountRepositoryInterface $discountRepository): DiscountService
    {
        $categoryHandler = new CategoryDiscountHandler($discountRepository);
        $productHandler = new ProductDiscountHandler($discountRepository);
        $categoryHandler->setNext($productHandler);

        return new DiscountService($categoryHandler);
    }
}
