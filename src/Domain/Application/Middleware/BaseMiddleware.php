<?php

declare(strict_types=1);

namespace Selmak\Proaxive2\Domain\Application\Middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Laminas\Diactoros\Response\JsonResponse;
use Selmak\Proaxive2\Http\API\Exception\ApiAuthException;
use Slim\Psr7\Response;

abstract class BaseMiddleware
{
    protected function checkToken(string $token): object
    {

        try {
            return JWT::decode($token, new Key(env('APP_SECRET'), 'HS512'));
        } catch (\UnexpectedValueException $exception) {
            $response = new Response();
            $response->getBody()->write(json_encode(['error' => sprintf('You are not authorized : %s', $exception->getMessage())]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
            //throw new ApiAuthException(sprintf('You are not authorized : %s', $exception->getMessage()), 403);
        }
    }
}