<?php

namespace App\Core\Contracts;

interface ConfigInterface
{
    public function get(string $key, mixed $default = null): mixed;
    public function all(): array;
}