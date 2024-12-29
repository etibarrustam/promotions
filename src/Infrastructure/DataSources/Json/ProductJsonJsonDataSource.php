<?php

namespace App\Infrastructure\DataSources\Json;

use App\Application\Contracts\ProductDataSourceInterface;

class ProductJsonJsonDataSource extends JsonDataSource implements ProductDataSourceInterface
{
    public function fetchProducts(): array
    {
        return $this->fetchDataFromFile();
    }
}
