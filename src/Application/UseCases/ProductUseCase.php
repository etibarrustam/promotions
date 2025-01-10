<?php

namespace App\Application\UseCases;

use App\Core\Contracts\ProductRepositoryInterface;
use App\Core\Services\DiscountService;
use App\Infrastructure\Exceptions\DataSourceException;

class ProductUseCase
{
    public function __construct(
        private ProductRepositoryInterface $repository,
        private DiscountService $discountService
    ) {
    }

    /**
     * @param array $queryParams
     * @return array
     * @throws DataSourceException
     */
    public function getFilteredProducts(array $queryParams): array
    {
        $products = $this->repository->getFilteredProducts($queryParams);

        foreach ($products as $product) {
            $this->discountService->applyDiscounts($product);
        }

        return $products;
    }
}
