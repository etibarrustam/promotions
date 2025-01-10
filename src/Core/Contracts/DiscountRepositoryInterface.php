<?php

namespace App\Core\Contracts;

use App\Core\Entities\Discount;

interface DiscountRepositoryInterface
{
    /**
     * @return Discount[]
     */
    public function getDiscounts(): array;
}
