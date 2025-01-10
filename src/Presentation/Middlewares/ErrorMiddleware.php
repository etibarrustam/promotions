<?php

namespace App\Presentation\Middlewares;

use App\Core\Exceptions\ApplicationException;
use App\Core\Exceptions\ValidationException;
use App\Infrastructure\Exceptions\ConfigFileNotFoundException;
use App\Infrastructure\Exceptions\InvalidConfigFileFormatException;
use App\Infrastructure\Http\HttpStatusCode;
use App\Infrastructure\Http\Response;
use Throwable;

class ErrorMiddleware
{
    /**
     * @var array<string, callable>
     */
    private array $exceptionHandlers = [];

    public function __construct()
    {
        $this->registerExceptionHandler(
            ValidationException::class,
            function (ValidationException $exception) {
                return [
                    'statusCode' => HttpStatusCode::HTTP_UNPROCESSABLE_ENTITY->value,
                    'data' => ['errors' => $exception->getErrors()],
                ];
            }
        );

        $this->registerExceptionHandler(
            ApplicationException::class,
            function (ApplicationException $exception) {
                return [
                    'statusCode' => $exception->getCode(),
                    'data' => ['error' => $exception->getMessage()],
                ];
            }
        );

        $this->registerExceptionHandler(
            ConfigFileNotFoundException::class,
            function (ConfigFileNotFoundException $exception) {
                return [
                    'statusCode' => $exception->getCode(),
                    'data' => ['error' => $exception->getMessage()],
                ];
            }
        );

        $this->registerExceptionHandler(
            InvalidConfigFileFormatException::class,
            function (InvalidConfigFileFormatException $exception) {
                return [
                    'statusCode' => $exception->getCode(),
                    'data' => ['error' => $exception->getMessage()],
                ];
            }
        );
    }

    /**
     * Registers a handler for a specific exception type.
     *
     * @param string $exceptionClass
     * @param callable $handler
     * @return void
     */
    public function registerExceptionHandler(string $exceptionClass, callable $handler): void
    {
        $this->exceptionHandlers[$exceptionClass] = $handler;
    }

    /**
     * Handles an exception and returns a response.
     *
     * @param Throwable $exception
     * @param Response $response
     * @return Response
     */
    public function handle(Throwable $exception, Response $response): Response
    {
        foreach ($this->exceptionHandlers as $exceptionClass => $handler) {
            if ($exception instanceof $exceptionClass) {
                $result = $handler($exception);

                return $response
                    ->setStatusCode($result['statusCode'])
                    ->json($result['data']);
            }
        }

        return $response
            ->setStatusCode(HttpStatusCode::INTERNAL_SERVER_ERROR->value)
            ->json(['error' => 'An unexpected error occurred']);
    }
}
