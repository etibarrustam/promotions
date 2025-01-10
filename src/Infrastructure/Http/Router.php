<?php

namespace App\Infrastructure\Http;

use Closure;

class Router
{
    private array $routes = [];

    public function get(string $path, Closure $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, Closure $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }

    private function addRoute(string $method, string $path, Closure $handler): void
    {
        $this->routes[$method][$path] = $handler;
    }

    public function dispatch(Request $request, Response $response): void
    {
        $method = $request->getMethod();
        $path = $request->getPath();

        if (isset($this->routes[$method][$path])) {
            $handler = $this->routes[$method][$path];
            $handler($request, $response);
        } else {
            $response->setStatusCode(HttpStatusCode::NOT_FOUND->value)
                ->json(['error' => 'Route not found'])
                ->send();
        }
    }
}
