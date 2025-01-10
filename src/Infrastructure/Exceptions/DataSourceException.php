<?php

namespace App\Infrastructure\Exceptions;

use App\Infrastructure\Http\HttpStatusCode;
use Exception;

class DataSourceException extends Exception
{
    public function __construct(string $message, int $code = HttpStatusCode::INTERNAL_SERVER_ERROR->value)
    {
        parent::__construct($message, $code);
    }
}
