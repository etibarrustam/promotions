<?php

namespace App\Infrastructure\Http;

use JetBrains\PhpStorm\NoReturn;

class Response
{
    public function __construct(
        private int $statusCode = HttpStatusCode::OK->value,
        private readonly string $body = '',
        private array $headers = [],
    ) {
    }

    public function setHeader(string $name, string $value): self
    {
        $this->headers[$name] = $value;

        return $this;
    }

    public function send(): void
    {
        http_response_code($this->statusCode);
        foreach ($this->headers as $name => $value) {
            header("{$name}: {$value}");
        }
        echo $this->body;
    }

    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public static function json(array $data, int $statusCode = HttpStatusCode::OK->value): self
    {
        return new self($statusCode, json_encode($data), ['Content-Type' => 'application/json']);
    }
}
