<?php

namespace Unit;

use PHPUnit\Framework\TestCase;
use App\Infrastructure\Services\ConfigService;

class ConfigServiceTest extends TestCase
{
    private string $testEnvFile;

    protected function setUp(): void
    {
        $this->testEnvFile = __DIR__ . '/../../.env.testing';
    }

    public function testGetReturnsCorrectValue(): void
    {
        $configService = new ConfigService($this->testEnvFile);
        $this->assertEquals('8000', $configService->get('APP_PORT'));
    }

    public function testResolvePathReturnsCorrectPath(): void
    {
        $configService = new ConfigService($this->testEnvFile);
        $resolvedPath = $configService->resolvePath('DB_NAME');
        $this->assertStringContainsString('test_db', $resolvedPath);
    }
}
