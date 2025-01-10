<?php

namespace App\Core\Contracts;

use App\Core\Entities\Product;

interface DiscountCalculatorInterface
{
    public function applyDiscounts(Product $product): void;
}
