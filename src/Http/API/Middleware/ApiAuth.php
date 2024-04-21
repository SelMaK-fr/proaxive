<?php

namespace Selmak\Proaxive2\Http\API\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Selmak\Proaxive2\Domain\Application\Middleware\BaseMiddleware;
use Selmak\Proaxive2\Http\API\Exception\ApiAuthException;

final class ApiAuth extends BaseMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): ResponseInterface
    {
        $jwtHeader = $request->getHeaderLine('Authorization');
        if (!$jwtHeader) {
            throw new ApiAuthException('JWT Token required.', 400);
        }
        $jwt = explode('Bearer ', $jwtHeader);
        if(!isset($jwt[1])) {
            throw new ApiAuthException('JWT Token invalid.', 400);
        }
        $decoded = $this->checkToken($jwt[1]);
        $object = (array) $request->getParsedBody();
        $object['decoded'] = $decoded;

        //$response = $handler->handle($request);
        //$response->getBody()->write($request->withParsedBody($object));
        return $handler->handle($request->withParsedBody($object));
    }
}