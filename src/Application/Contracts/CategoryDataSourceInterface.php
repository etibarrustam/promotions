<?php

namespace App\Application\Contracts;

interface CategoryDataSourceInterface
{
    public function fetchCategories(): array;
}