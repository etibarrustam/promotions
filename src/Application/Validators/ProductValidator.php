<?php

namespace App\Application\Validators;

use App\Core\Contracts\ValidatorInterface;
use App\Core\Exceptions\ValidationException;

class ProductValidator implements ValidatorInterface
{
    /**
     * @param array $data
     * @return void
     * @throws ValidationException
     */
    public function validate(array $data): void
    {
        $errors = [];

        if (isset($data['category']) && empty($data['category'])) {
            $errors['category'] = 'Category cannot be empty.';
        }
        if (isset($data['priceLessThan']) && !is_numeric($data['priceLessThan'])) {
            $errors['priceLessThan'] = 'Price must be a numeric value.';
        }

        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
    }
}
