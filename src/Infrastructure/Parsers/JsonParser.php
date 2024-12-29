<?php

namespace App\Infrastructure\Parsers;

use App\Core\Contracts\FileParserInterface;
use RuntimeException;

class JsonParser implements FileParserInterface
{
    /**
     * Parse a JSON file and return its contents as an array.
     *
     * @param string $filePath
     * @return array
     * @throws RuntimeException
     */
    public function parse(string $filePath): array
    {
        if (!file_exists($filePath)) {
            throw new RuntimeException("File not found: {$filePath}");
        }

        $data = json_decode(file_get_contents($filePath), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException("Invalid JSON format in file: {$filePath}");
        }

        return $data ?? [];
    }
}
