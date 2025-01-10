<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Infrastructure\Factories\AppFactory;
use App\Infrastructure\Http\Request;
use App\Infrastructure\Http\Response;
use App\Infrastructure\Services\ConfigService;
use App\Presentation\Middlewares\ErrorMiddleware;

try {
    $configService = new ConfigService(__DIR__ . '/../' . getenv('ENV_FILE'));

    $app = AppFactory::create($configService);

    $app->router->get('/', function (Request $request, Response $response) use ($configService) {
        $response->json([
            'message' => 'Welcome to the promotions app. ☺️',
            'url' => 'http://localhost:' . $configService->get('APP_PORT') . '/products'
        ])->send();
    });

    $app->router->get('/products', function (Request $request, Response $response) use ($app) {
        $app->productController->fetchProducts($request, $response)->send();
    });

    $request = Request::create();
    $response = new Response();
    $app->router->dispatch($request, $response);
} catch (Throwable $e) {
    echo $e->getMessage();
    $errorMiddleware = new ErrorMiddleware();
    $response = new Response();
    $errorMiddleware->handle($e, $response)->send();
}
