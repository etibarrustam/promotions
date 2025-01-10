<?php

namespace App\Infrastructure\Repositories\Discounts;

use App\Core\Contracts\DiscountRepositoryInterface;
use App\Core\Entities\Discount;
use App\Infrastructure\Exceptions\DataSourceException;
use PDO;
use PDOException;

class DiscountDatabaseRepository implements DiscountRepositoryInterface
{
    public function __construct(private PDO $connection)
    {
    }

    public function getDiscounts(): array
    {
        try {
            $query = 'SELECT id, type, target_id, percentage 
                      FROM discounts';

            $stmt = $this->connection->query($query);
            $discounts = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return array_map(function ($discount) {
                return new Discount(
                    $discount['id'],
                    $discount['type'],
                    $discount['target_id'],
                    $discount['percentage']
                );
            }, $discounts);
        } catch (PDOException $e) {
            throw new DataSourceException('Database error: ' . $e->getMessage());
        }
    }
}
