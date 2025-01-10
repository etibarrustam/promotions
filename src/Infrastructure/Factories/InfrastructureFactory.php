<?php

namespace App\Infrastructure\Factories;

use App\Core\Contracts\DiscountRepositoryInterface;
use App\Core\Contracts\ProductRepositoryInterface;
use App\Infrastructure\Services\ConfigService;

class InfrastructureFactory
{
    public static function createProductRepository(ConfigService $configService): ProductRepositoryInterface
    {
        return DataSourceFactory::createProductRepository($configService);
    }

    public static function createDiscountRepository(ConfigService $configService): DiscountRepositoryInterface
    {
        return DataSourceFactory::createDiscountRepository($configService);
    }
}
