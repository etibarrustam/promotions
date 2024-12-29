<?php

namespace App\Infrastructure\Http;

class Response
{
    public function __construct(
        private readonly int $statusCode = HttpStatusCode::OK->value,
        private readonly string $body = '',
        private array $headers = [],
    ) {
    }

    public function setHeader(string $name, string $value): void
    {
        $this->headers[$name] = $value;
    }

    public function send(): void
    {
        http_response_code($this->statusCode);
        foreach ($this->headers as $name => $value) {
            header("{$name}: {$value}");
        }
        echo $this->body;
    }

    public static function json(array $data, int $statusCode = HttpStatusCode::OK->value): self
    {
        return new self($statusCode, json_encode($data), ['Content-Type' => 'application/json']);
    }
}
