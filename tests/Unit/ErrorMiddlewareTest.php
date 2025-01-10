<?php

namespace Unit;

use Exception;
use PHPUnit\Framework\TestCase;
use App\Presentation\Middlewares\ErrorMiddleware;
use App\Infrastructure\Http\Response;

class ErrorMiddlewareTest extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testHandleReturnsErrorResponse(): void
    {
        $middleware = new ErrorMiddleware();
        $response = new Response();

        $exception = new Exception('Test error');

        $response = $middleware->handle($exception, $response);

        $this->expectOutputString('{"error":"An unexpected error occurred"}');
        $response->send();
    }
}
