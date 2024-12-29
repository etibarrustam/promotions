<?php

namespace App\Application\Contracts;

interface DiscountDataSourceInterface
{
    public function fetchDiscounts(): array;
}