<?php

namespace Unit;

use PHPUnit\Framework\TestCase;
use App\Infrastructure\Repositories\Products\ProductDatabaseRepository;
use PDO;
use PDOStatement;

class ProductRepositoryTest extends TestCase
{
    public function testGetFilteredProductsReturnsProducts(): void
    {
        $pdo = $this->createMock(PDO::class);
        $statement = $this->createMock(PDOStatement::class);

        $pdo->method('prepare')->willReturn($statement);
        $statement->method('fetchAll')->willReturn([
            [
                'id' => 1,
                'sku' => '000001',
                'name' => 'Test Product',
                'category_id' => 1,
                'category_name' => 'boots',
                'price' => 100,
            ],
        ]);

        $repository = new ProductDatabaseRepository($pdo);
        $products = $repository->getFilteredProducts(['category' => 'boots']);

        $this->assertCount(1, $products);
        $this->assertEquals('Test Product', $products[0]->getName());
    }
}
