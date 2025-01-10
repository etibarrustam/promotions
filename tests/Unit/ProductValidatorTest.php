<?php

namespace Unit;

use PHPUnit\Framework\TestCase;
use App\Application\Validators\ProductValidator;
use App\Core\Exceptions\ValidationException;

class ProductValidatorTest extends TestCase
{
    public function testValidateThrowsExceptionForInvalidData(): void
    {
        $validator = new ProductValidator();

        $this->expectException(ValidationException::class);
        $validator->validate(['priceLessThan' => 'invalid_number']);
    }

    public function testValidatePassesForValidData(): void
    {
        $validator = new ProductValidator();
        $validator->validate(['category' => 'boots', 'priceLessThan' => 100]);
        $this->assertTrue(true); // No exception means test passed
    }
}
