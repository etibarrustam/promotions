<?php

namespace App\Core\Exceptions;

use App\Infrastructure\Http\HttpStatusCode;

class NotFoundException extends ApplicationException
{
    public function __construct(string $message, int $code = HttpStatusCode::NOT_FOUND->value)
    {
        parent::__construct($message, $code);
    }
}
