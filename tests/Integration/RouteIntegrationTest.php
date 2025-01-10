<?php

namespace Integration;

use PHPUnit\Framework\TestCase;

class RouteIntegrationTest extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testRootEndpointReturnsWelcomeMessage(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/';
        ob_start();
        require __DIR__ . '/../../public/index.php';
        $output = ob_get_clean();

        $this->assertStringContainsString('"message":"Welcome to the promotions app.', $output);
    }

    /**
     * @runInSeparateProcess
     */
    public function testRouteNotFoundError(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/test-not-found';
        ob_start();
        require __DIR__ . '/../../public/index.php';
        $output = ob_get_clean();

        $this->assertStringContainsString('{"error":"Route not found"}', $output);
    }
}
