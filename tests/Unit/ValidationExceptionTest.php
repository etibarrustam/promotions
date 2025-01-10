<?php

namespace Unit;

use PHPUnit\Framework\TestCase;
use App\Core\Exceptions\ValidationException;

class ValidationExceptionTest extends TestCase
{
    public function testExceptionStoresValidationErrors(): void
    {
        $errors = ['field' => 'Error message'];
        $exception = new ValidationException($errors);

        $this->assertEquals($errors, $exception->getErrors());
    }
}
