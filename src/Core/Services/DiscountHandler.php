<?php

namespace App\Core\Services;

use App\Core\Contracts\DiscountHandlerInterface;
use App\Core\Entities\Product;

abstract class DiscountHandler implements DiscountHandlerInterface
{
    private ?DiscountHandlerInterface $nextHandler = null;

    public function setNext(DiscountHandlerInterface $handler): DiscountHandlerInterface
    {
        $this->nextHandler = $handler;
        return $handler;
    }

    public function apply(Product $product, ?int $currentMaxDiscount = 0): int
    {
        $currentDiscount = $this->calculateDiscount($product);

        $maxDiscount = max($currentMaxDiscount, $currentDiscount ?? 0);

        if ($this->nextHandler) {
            return $this->nextHandler->apply($product, $maxDiscount);
        }

        return $maxDiscount;
    }

    abstract protected function calculateDiscount(Product $product): ?int;
}
