<?php

namespace Unit;

use PHPUnit\Framework\TestCase;
use App\Infrastructure\Http\Response;

class ResponseTest extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testJsonSendsCorrectHeadersAndBody(): void
    {
        $response = Response::json(['message' => 'Success'], 200);

        $this->expectOutputString('{"message":"Success"}');
        $response->send();
    }
}
