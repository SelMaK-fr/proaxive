<?php

namespace Selmak\Proaxive2\Http\API\V1;

use DI\Container;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface as Response;

abstract class ApiAbstractController
{
    public function __construct(protected Container $container){}

    protected function jsonResponse(Response $response, string $status, $message, int $code): Response
    {
        $result = [
            'code' => $code,
            'status' => $status,
            'message' => $message
        ];
        return new JsonResponse($result, $code, [], JSON_PRETTY_PRINT);
    }
}