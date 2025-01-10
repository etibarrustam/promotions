<?php

namespace App\Core\Services;

use App\Core\Contracts\DiscountRepositoryInterface;
use App\Core\Entities\Product;

class CategoryDiscountHandler extends DiscountHandler
{
    public function __construct(private readonly DiscountRepositoryInterface $repository)
    {
    }

    protected function calculateDiscount(Product $product): int
    {
        $discounts = $this->repository->getDiscounts();

        foreach ($discounts as $discount) {
            if (
                $discount->getType() === 'category' &&
                $product->getCategory()->getId() === $discount->getTargetId()
            ) {
                return $discount->getPercentage();
            }
        }

        return 0;
    }
}
