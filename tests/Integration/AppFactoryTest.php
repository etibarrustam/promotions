<?php

namespace Integration;

use App\Infrastructure\Factories\AppFactory;
use App\Infrastructure\Services\ConfigService;
use PHPUnit\Framework\TestCase;

class AppFactoryTest extends TestCase
{
    public function testCreateReturnsConfiguredApp(): void
    {
        $configService = new ConfigService(__DIR__ . '/../../.env.testing');

        $app = AppFactory::create($configService);

        $this->assertTrue(property_exists($app, 'router'), "The object does not have the expected property 'router'");
        $this->assertTrue(
            property_exists($app, 'productController'),
            "The object does not have the expected property 'productController'"
        );
    }
}
