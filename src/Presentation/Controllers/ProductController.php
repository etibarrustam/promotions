<?php

namespace App\Presentation\Controllers;

use App\Application\UseCases\ProductUseCase;
use App\Core\Contracts\PresenterInterface;
use App\Core\Contracts\ValidatorInterface;
use App\Core\Exceptions\ValidationException;
use App\Infrastructure\Exceptions\DataSourceException;
use App\Infrastructure\Http\Request;
use App\Infrastructure\Http\Response;

class ProductController
{
    public function __construct(
        private ProductUseCase $useCase,
        private PresenterInterface $presenter,
        private ValidatorInterface $validator
    ) {
    }

    /**
     * @throws ValidationException
     * @throws DataSourceException
     */
    public function fetchProducts(Request $request, Response $response): Response
    {
        $queryParams = $request->getQueryParams();
        $this->validator->validate($queryParams);

        $products = $this->useCase->getFilteredProducts($queryParams);

        return $response->json($this->presenter->present($products));
    }
}
