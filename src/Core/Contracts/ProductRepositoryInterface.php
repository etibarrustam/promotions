<?php

namespace App\Core\Contracts;

use App\Infrastructure\Exceptions\DataSourceException;

interface ProductRepositoryInterface
{
    /**
     * @param array $filters
     * @return array
     * @throws DataSourceException
     */
    public function getFilteredProducts(array $filters = []): array;
}
