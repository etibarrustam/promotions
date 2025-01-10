<?php

namespace Integration;

use PHPUnit\Framework\TestCase;

class ProductIntegrationTest extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testFetchProductsReturnsProducts()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/products';
        $_GET = [];
        $_POST = [];

        ob_start();
        require __DIR__ . '/../../public/index.php';
        $output = ob_get_clean();

        $this->assertStringContainsString('"products":[', $output);
    }
}
