<?php

namespace App\Presentation\Presenters;

use App\Core\Contracts\PresenterInterface;
use App\Core\Entities\Product;

class ProductPresenter implements PresenterInterface
{
    /**
     * Format a list of products for the API response.
     *
     * @param Product[] $data
     * @return array
     */
    public function present(array $data): array
    {
        return [
            'products' => array_map(function (Product $product) {
                return $this->formatProduct($product);
            }, $data),
        ];
    }

    /**
     * Format a single product into the response structure.
     *
     * @param Product $product
     * @return array
     */
    private function formatProduct(Product $product): array
    {
        return [
            'sku' => $product->getSku(),
            'name' => $product->getName(),
            'category' => $product->getCategory()->getName(),
            'price' => [
                'original' => $product->getOriginalPrice(),
                'final' => $product->getFinalPrice(),
                'discount_percentage' => $product->getDiscountPercentage(),
                'currency' => $product->getCurrency(),
            ],
        ];
    }
}
