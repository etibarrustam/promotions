<?php

namespace Integration;

use App\Infrastructure\Services\ConfigService;
use PHPUnit\Framework\TestCase;
use App\Infrastructure\Repositories\Products\ProductDatabaseRepository;
use PDO;

class ProductRepositoryTest extends TestCase
{
    private PDO $pdo;
    private ProductDatabaseRepository $repository;

    protected function setUp(): void
    {
        $config = new ConfigService(__DIR__ . '/../../.env.testing');

        $this->pdo = new PDO(
            sprintf(
                '%s:host=%s;port=%s;dbname=%s;charset=utf8mb4',
                $config->get('DB_DRIVER'),
                $config->get('DB_HOST'),
                $config->get('DB_PORT'),
                $config->get('DB_NAME')
            ),
            $config->get('DB_USER'),
            $config->get('DB_PASSWORD')
        );
        $this->repository = new ProductDatabaseRepository($this->pdo);
    }

    public function testGetFilteredProductsReturnsExpectedResults(): void
    {
        $products = $this->repository->getFilteredProducts(['category' => 'boots']);

        $this->assertCount(3, $products);
        $this->assertEquals('BV Lean leather ankle boots', $products[0]->getName());
    }
}
