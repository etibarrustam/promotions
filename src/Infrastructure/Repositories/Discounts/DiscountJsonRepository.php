<?php

namespace App\Infrastructure\Repositories\Discounts;

use App\Core\Contracts\DiscountRepositoryInterface;
use App\Core\Contracts\FileParserInterface;
use App\Core\Entities\Discount;
use App\Infrastructure\Exceptions\DataSourceException;
use Throwable;

class DiscountJsonRepository implements DiscountRepositoryInterface
{
    public function __construct(private string $filePath, private FileParserInterface $parser)
    {
    }

    public function getDiscounts(): array
    {
        try {
            $data = $this->parser->parse($this->filePath);
            return array_map(
                fn($discount) => new Discount(
                    $discount['id'],
                    $discount['type'],
                    $discount['target_id'],
                    $discount['percentage']
                ),
                $data
            );
        } catch (Throwable $e) {
            throw new DataSourceException("Error reading or parsing JSON file: {$this->filePath}. {$e->getMessage()}");
        }
    }
}
