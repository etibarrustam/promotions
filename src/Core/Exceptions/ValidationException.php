<?php

namespace App\Core\Exceptions;

use App\Infrastructure\Http\HttpStatusCode;

class ValidationException extends ApplicationException
{
    private array $errors;

    public function __construct(array $errors, int $code = HttpStatusCode::BAD_REQUEST->value)
    {
        $this->errors = $errors;

        parent::__construct('', $code);
    }

    /**
     * Get the validation errors formatted as a response.
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Add a validation error.
     *
     * @param string $field.
     * @param string $message.
     */
    public function addError(string $field, string $message): void
    {
        $this->errors[$field] = $message;
    }
}
