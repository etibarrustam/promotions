<?php

namespace App\Infrastructure\DataSources\Json;

use App\Application\Contracts\CategoryDataSourceInterface;

class CategoryJsonJsonDataSource extends JsonDataSource implements CategoryDataSourceInterface
{
    public function fetchCategories(): array
    {
        return $this->fetchDataFromFile();
    }
}
