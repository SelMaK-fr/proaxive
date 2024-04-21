<?php

declare(strict_types=1);

namespace Selmak\Proaxive2\Domain\Application\Middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Selmak\Proaxive2\Http\API\Exception\ApiAuthException;

abstract class BaseMiddleware
{
    protected function checkToken(string $token): object
    {

        try {
            return JWT::decode($token, new Key(env('SECRET_KEY'), 'HS256'));
        } catch (\UnexpectedValueException $exception) {
            throw new ApiAuthException(sprintf('You are not authorized : %s', $exception->getMessage()), 403);
        }
    }
}