<?php

namespace App\Core\Contracts;

interface PresenterInterface
{
    public function present(array $data): array;
}
