<?php

namespace App\Infrastructure\DataSources\Json;

use App\Application\Contracts\DiscountDataSourceInterface;

class DiscountJsonJsonDataSource extends JsonDataSource implements DiscountDataSourceInterface
{
    public function fetchDiscounts(): array
    {
        return $this->fetchDataFromFile();
    }
}
