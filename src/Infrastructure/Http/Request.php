<?php

namespace App\Infrastructure\Http;

class Request
{
    public function __construct(
        private array $queryParams,
        private array $bodyParams,
        private string $method,
        private string $path
    ) {
    }

    public static function create(): self
    {
        return new self($_GET, $_POST, $_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
    }

    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    public function getBodyParams(): array
    {
        return $this->bodyParams;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getPath(): string
    {
        return parse_url($this->path, PHP_URL_PATH);
    }
}
