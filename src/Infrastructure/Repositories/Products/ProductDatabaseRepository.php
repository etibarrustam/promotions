<?php

namespace App\Infrastructure\Repositories\Products;

use App\Core\Contracts\ProductRepositoryInterface;
use App\Core\Entities\Category;
use App\Core\Entities\Product;
use App\Core\ValueObjects\Price;
use App\Infrastructure\Exceptions\DataSourceException;
use PDO;
use PDOException;

class ProductDatabaseRepository implements ProductRepositoryInterface
{
    public function __construct(private PDO $connection)
    {
    }

    public function getFilteredProducts(array $filters = []): array
    {
        try {
            $query = 'SELECT p.id, p.sku, p.name, p.price, 
                             c.id AS category_id, c.name AS category_name
                      FROM products p 
                      JOIN categories c ON p.category_id = c.id';

            $conditions = [];
            $params = [];

            if (!empty($filters['category'])) {
                $conditions[] = 'c.name = :category';
                $params[':category'] = $filters['category'];
            }

            if (!empty($filters['priceLessThan'])) {
                $conditions[] = 'p.price <= :price';
                $params[':price'] = $filters['priceLessThan'];
            }

            if (!empty($conditions)) {
                $query .= ' WHERE ' . implode(' AND ', $conditions);
            }

            $stmt = $this->connection->prepare($query);
            $stmt->execute($params);

            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return array_map(
                fn($item) => new Product(
                    $item['id'],
                    $item['sku'],
                    $item['name'],
                    new Category($item['category_id'], $item['category_name']),
                    new Price($item['price'])
                ),
                $products
            );
        } catch (PDOException $e) {
            throw new DataSourceException('Database error: ' . $e->getMessage());
        }
    }
}
