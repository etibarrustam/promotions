<?php

namespace App\Core\Contracts;

use App\Core\Entities\Category;

interface CategoryRepositoryInterface
{
    /**
     * @return Category[]
     */
    public function getCategories(): array;
}
