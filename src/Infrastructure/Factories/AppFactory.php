<?php

namespace App\Infrastructure\Factories;

use App\Application\Factories\ApplicationFactory;
use App\Core\Factories\DomainFactory;
use App\Infrastructure\Http\Router;
use App\Infrastructure\Services\ConfigService;
use App\Presentation\Factories\PresentationFactory;

class AppFactory
{
    public static function create(ConfigService $configService): object
    {
        $productRepository = InfrastructureFactory::createProductRepository($configService);
        $discountRepository = InfrastructureFactory::createDiscountRepository($configService);

        $discountService = DomainFactory::createDiscountService($discountRepository);

        $productUseCase = ApplicationFactory::createProductUseCase($productRepository, $discountService);

        $productController = PresentationFactory::createProductController($productUseCase);

        return (object) [
            'router' => new Router(),
            'productController' => $productController,
        ];
    }
}
