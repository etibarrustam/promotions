<?php

namespace App\Infrastructure\Http;

class Request
{
    public function __construct(private array $queryParams, private array $headers)
    {
    }

    public static function create(): static
    {
        return new static($_GET, getallheaders());
    }

    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    public function getHeader(string $name): ?string
    {
        return $this->headers[$name] ?? null;
    }
}
