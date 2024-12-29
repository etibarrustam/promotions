<?php

namespace App\Core\Contracts;

interface FileParserInterface
{
    /**
     * Parse the file at the given path and return its contents as an array.
     *
     * @param string $filePath
     * @return array
     */
    public function parse(string $filePath): array;
}
