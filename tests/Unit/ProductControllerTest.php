<?php

namespace Unit;

use App\Core\Exceptions\ValidationException;
use PHPUnit\Framework\TestCase;
use App\Presentation\Controllers\ProductController;
use App\Application\UseCases\ProductUseCase;
use App\Presentation\Presenters\ProductPresenter;
use App\Application\Validators\ProductValidator;
use App\Infrastructure\Http\Request;
use App\Infrastructure\Http\Response;

class ProductControllerTest extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testFetchProductsReturnsCorrectResponse(): void
    {
        $useCase = $this->createMock(ProductUseCase::class);
        $presenter = $this->createMock(ProductPresenter::class);
        $validator = $this->createMock(ProductValidator::class);

        $useCase->method('getFilteredProducts')->willReturn([]); // No products returned
        $presenter->method('present')->willReturn(['products' => []]); // Empty product list

        $controller = new ProductController($useCase, $presenter, $validator);

        $request = $this->createMock(Request::class);
        $response = new Response();

        ob_start();
        $controller->fetchProducts($request, $response)->send();
        $output = ob_get_clean();

        $this->assertStringContainsString('"products":[]', $output);
    }
}
