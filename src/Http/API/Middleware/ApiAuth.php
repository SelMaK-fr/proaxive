<?php

namespace Selmak\Proaxive2\Http\API\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Selmak\Proaxive2\Domain\Application\Middleware\BaseMiddleware;
use Selmak\Proaxive2\Http\API\Exception\ApiAuthException;
use Slim\Psr7\Response;

final class ApiAuth extends BaseMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): ResponseInterface
    {
        $response = new Response();
        $jwtHeader = $request->getHeader('Authorization');
        if (!$jwtHeader) {
            $response->getBody()->write(json_encode(['error' => 'Missing Authorization Header']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
        }
        $jwt = explode('Bearer ', $jwtHeader[0]);
        if(!isset($jwt[1])) {
            $response->getBody()->write(json_encode(['error' => 'JWT Token invalid.']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            //throw new ApiAuthException('JWT Token invalid.', 400);
        }
        $decoded = $this->checkToken($jwt[1]);
        $object = (array) $request->getParsedBody();
        $object['decoded'] = $decoded;

        return $handler->handle($request->withParsedBody($object));
    }
}