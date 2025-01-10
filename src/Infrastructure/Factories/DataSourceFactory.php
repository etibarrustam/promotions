<?php

namespace App\Infrastructure\Factories;

use App\Core\Contracts\CategoryRepositoryInterface;
use App\Core\Contracts\ConfigInterface;
use App\Core\Contracts\DiscountRepositoryInterface;
use App\Core\Contracts\ProductRepositoryInterface;
use App\Infrastructure\Exceptions\DataSourceException;
use App\Infrastructure\Parsers\JsonParser;
use App\Infrastructure\Repositories\Categories\CategoryDatabaseRepository;
use App\Infrastructure\Repositories\Categories\CategoryJsonRepository;
use App\Infrastructure\Repositories\Discounts\DiscountDatabaseRepository;
use App\Infrastructure\Repositories\Discounts\DiscountJsonRepository;
use App\Infrastructure\Repositories\Products\ProductDatabaseRepository;
use App\Infrastructure\Repositories\Products\ProductJsonRepository;
use PDO;
use PDOException;

class DataSourceFactory
{
    public static function createProductRepository(ConfigInterface $config): ProductRepositoryInterface
    {
        if (self::isJsonDataSource($config)) {
            return new ProductJsonRepository(self::resolveJsonFilePath($config, 'PRODUCTS_FILE'), new JsonParser());
        }

        return new ProductDatabaseRepository(self::createPdoConnection($config));
    }

    public static function createDiscountRepository(ConfigInterface $config): DiscountRepositoryInterface
    {
        if (self::isJsonDataSource($config)) {
            return new DiscountJsonRepository(self::resolveJsonFilePath($config, 'DISCOUNTS_FILE'), new JsonParser());
        }

        return new DiscountDatabaseRepository(self::createPdoConnection($config));
    }

    public static function createCategoryRepository(ConfigInterface $config): CategoryRepositoryInterface
    {
        if (self::isJsonDataSource($config)) {
            return new CategoryJsonRepository(self::resolveJsonFilePath($config, 'CATEGORIES_FILE'), new JsonParser());
        }

        return new CategoryDatabaseRepository(self::createPdoConnection($config));
    }

    private static function createPdoConnection(ConfigInterface $config): PDO
    {
        try {
            $dsn = sprintf(
                '%s:host=%s;port=%s;dbname=%s;charset=utf8mb4',
                $config->get('DB_DRIVER'),
                $config->get('DB_HOST'),
                $config->get('DB_PORT'),
                $config->get('DB_NAME')
            );

            return new PDO(
                $dsn,
                $config->get('DB_USER'),
                $config->get('DB_PASSWORD')
            );
        } catch (PDOException $e) {
            throw new DataSourceException("Database connection error: " . $e->getMessage());
        }
    }

    private static function isJsonDataSource(ConfigInterface $config): bool
    {
        $dataSource = $config->get('DATA_SOURCE', 'json');

        return $dataSource === 'json';
    }

    private static function resolveJsonFilePath(ConfigInterface $config, string $fileKey): string
    {
        $filePath = $config->resolvePath($fileKey);

        if (!$filePath) {
            throw new DataSourceException("Configuration key '{$fileKey}' is missing.");
        }

        if (!file_exists($filePath)) {
            throw new DataSourceException("JSON file not found: {$filePath}");
        }

        return $filePath;
    }
}
