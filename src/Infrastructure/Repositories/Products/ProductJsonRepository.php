<?php

namespace App\Infrastructure\Repositories\Products;

use App\Core\Contracts\FileParserInterface;
use App\Core\Contracts\ProductRepositoryInterface;
use App\Core\Entities\Category;
use App\Core\Entities\Product;
use App\Core\ValueObjects\Price;
use App\Infrastructure\Exceptions\DataSourceException;
use Throwable;

class ProductJsonRepository implements ProductRepositoryInterface
{
    public function __construct(private string $filePath, private FileParserInterface $fileParser)
    {
    }

    /**
     * @param array $filters
     * @return array
     * @throws DataSourceException
     */
    public function getFilteredProducts(array $filters = []): array
    {
        try {
            $products = array_map(
                fn($item) => new Product(
                    $item['id'],
                    $item['sku'],
                    $item['name'],
                    new Category($item['category_id'], $item['category_name']),
                    new Price($item['price'])
                ),
                $this->fileParser->parse($this->filePath)
            );

            return $this->applyFilters($products, $filters);
        } catch (Throwable $e) {
            throw new DataSourceException('Error reading JSON file: ' . $e->getMessage());
        }
    }

    private function applyFilters(array $products, array $filters): array
    {
        return array_filter($products, function (Product $product) use ($filters) {
            if (isset($filters['category']) && $product->getCategory()->getName() !== $filters['category']) {
                return false;
            }

            if (isset($filters['priceLessThan']) && $product->getOriginalPrice() > $filters['priceLessThan']) {
                return false;
            }

            return true;
        });
    }
}
