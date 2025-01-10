<?php

namespace App\Infrastructure\Repositories\Categories;

use App\Core\Contracts\CategoryRepositoryInterface;
use App\Core\Contracts\FileParserInterface;
use App\Infrastructure\Exceptions\DataSourceException;
use Throwable;

class CategoryJsonRepository implements CategoryRepositoryInterface
{
    public function __construct(private string $filePath, private FileParserInterface $parser)
    {
    }

    public function getCategories(): array
    {
        try {
            $data = $this->parser->parse($this->filePath);
            return $data['categories'] ?? [];
        } catch (Throwable $e) {
            throw new DataSourceException("Error reading or parsing JSON file: {$this->filePath}. {$e->getMessage()}");
        }
    }
}
