<?php

namespace App\Infrastructure\Services;

use App\Core\Contracts\ConfigInterface;
use App\Infrastructure\Exceptions\ConfigFileNotFoundException;
use App\Infrastructure\Exceptions\InvalidConfigFileFormatException;
use RuntimeException;

class ConfigService implements ConfigInterface
{
    private array $config;
    private string $basePath;

    public function __construct(string $filePath)
    {
        if (!file_exists($filePath)) {
            throw new ConfigFileNotFoundException("Configuration file not found: {$filePath}");
        }

        $this->basePath = dirname($filePath);
        $this->config = $this->parseEnvFile($filePath);
    }

    private function parseEnvFile(string $filePath): array
    {
        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $config = [];

        foreach ($lines as $line) {
            if (str_starts_with(trim($line), '#')) {
                continue;
            }

            if (!str_contains($line, '=')) {
                throw new InvalidConfigFileFormatException($filePath, "Invalid key-value pair: {$line}");
            }

            [$key, $value] = explode('=', $line, 2);
            $config[trim($key)] = trim($value);
        }

        return $config;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->config[$key] ?? $default;
    }

    public function all(): array
    {
        return $this->config;
    }

    /**
     * @param string $key
     * @return string
     */
    public function resolvePath(string $key): string
    {
        $path = $this->get($key);

        if (!$path) {
            throw new RuntimeException("Configuration key '{$key}' not found.");
        }

        if ($this->isAbsolutePath($path)) {
            return $path;
        }

        return $this->basePath . DIRECTORY_SEPARATOR . $path;
    }

    /**
     * @param string $path
     * @return bool
     */
    private function isAbsolutePath(string $path): bool
    {
        return str_starts_with($path, DIRECTORY_SEPARATOR) ||
            preg_match('/^[a-zA-Z]:\\\\/', $path);
    }
}
