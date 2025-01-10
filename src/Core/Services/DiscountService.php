<?php

namespace App\Core\Services;

use App\Core\Contracts\DiscountCalculatorInterface;
use App\Core\Contracts\DiscountHandlerInterface;
use App\Core\Entities\Product;

class DiscountService implements DiscountCalculatorInterface
{
    private DiscountHandlerInterface $chain;

    public function __construct(DiscountHandlerInterface $chain)
    {
        $this->chain = $chain;
    }

    public function applyDiscounts(Product $product): void
    {
        $maxDiscount = $this->chain->apply($product);

        if ($maxDiscount > 0) {
            $product->applyDiscount($maxDiscount);
        }
    }
}
