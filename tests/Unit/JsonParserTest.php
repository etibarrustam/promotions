<?php

namespace Unit;

use PHPUnit\Framework\TestCase;
use App\Infrastructure\Parsers\JsonParser;
use RuntimeException;

class JsonParserTest extends TestCase
{
    private string $tempFile;

    protected function setUp(): void
    {
        $this->tempFile = tempnam(sys_get_temp_dir(), 'json_test_');
    }

    protected function tearDown(): void
    {
        if (file_exists($this->tempFile)) {
            unlink($this->tempFile);
        }
    }

    public function testParseReturnsArray(): void
    {
        file_put_contents($this->tempFile, '{"key": "value"}');

        $parser = new JsonParser();
        $result = $parser->parse($this->tempFile);

        $this->assertEquals(['key' => 'value'], $result);
    }

    public function testParseThrowsExceptionForNonExistentFile(): void
    {
        $parser = new JsonParser();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('File not found');

        $parser->parse('/path/to/nonexistent/file.json');
    }

    public function testParseThrowsExceptionForInvalidJson(): void
    {
        file_put_contents($this->tempFile, 'invalid_json');

        $parser = new JsonParser();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Invalid JSON format');

        $parser->parse($this->tempFile);
    }

    public function testParseReturnsEmptyArrayForEmptyJsonFile(): void
    {
        file_put_contents($this->tempFile, '{}');

        $parser = new JsonParser();
        $result = $parser->parse($this->tempFile);

        $this->assertEquals([], $result);
    }
}
