<?php

namespace App\Core\Contracts;

use App\Core\Exceptions\ValidationException;

interface ValidatorInterface
{
    /**
     * @param array $data
     * @throws ValidationException
     */
    public function validate(array $data): void;
}
