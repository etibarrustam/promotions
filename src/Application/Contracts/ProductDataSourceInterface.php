<?php

namespace App\Application\Contracts;

interface ProductDataSourceInterface
{
    public function fetchProducts(): array;
}