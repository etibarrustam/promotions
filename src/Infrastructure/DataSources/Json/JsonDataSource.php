<?php

namespace App\Infrastructure\DataSources\Json;

use App\Core\Contracts\FileParserInterface;
use RuntimeException;

abstract class JsonDataSource
{
    public function __construct(
        protected readonly string $filePath,
        protected readonly FileParserInterface $parser
    ) {
        if (!file_exists($filePath)) {
            throw new RuntimeException("File not found: {$filePath}");
        }
    }

    public function fetchDataFromFile(): array
    {
        return $this->parser->parse($this->filePath);
    }
}
