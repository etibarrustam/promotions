<?php

namespace App\Core\Contracts;

use App\Core\Entities\Product;

interface DiscountHandlerInterface
{
    public function apply(Product $product, ?int $currentMaxDiscount = 0): int;
}
