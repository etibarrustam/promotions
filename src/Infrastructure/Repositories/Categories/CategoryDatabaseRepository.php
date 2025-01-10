<?php

namespace App\Infrastructure\Repositories\Categories;

use App\Core\Contracts\CategoryRepositoryInterface;
use App\Infrastructure\Exceptions\DataSourceException;
use PDO;
use PDOException;

class CategoryDatabaseRepository implements CategoryRepositoryInterface
{
    public function __construct(private PDO $connection)
    {
    }

    public function getCategories(): array
    {
        try {
            $stmt = $this->connection->query('SELECT id, name FROM categories');
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new DataSourceException('Database error: ' . $e->getMessage());
        }
    }
}
