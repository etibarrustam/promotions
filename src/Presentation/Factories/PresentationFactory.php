<?php

namespace App\Presentation\Factories;

use App\Application\UseCases\ProductUseCase;
use App\Application\Validators\ProductValidator;
use App\Presentation\Controllers\ProductController;
use App\Presentation\Presenters\ProductPresenter;

class PresentationFactory
{
    public static function createProductController(ProductUseCase $productUseCase): ProductController
    {
        return new ProductController(
            $productUseCase,
            new ProductPresenter(),
            new ProductValidator()
        );
    }
}
