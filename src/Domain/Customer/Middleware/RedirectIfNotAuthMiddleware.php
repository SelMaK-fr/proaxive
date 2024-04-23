<?php

declare(strict_types=1);

namespace Selmak\Proaxive2\Domain\Customer\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;

class RedirectIfNotAuthMiddleware
{
    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = new Response();
        if(!isset($_SESSION['customer'])){
            return $response->withHeader('Location', '/wxy/customers/login')->withStatus(302);
        } else {
            $toResponse = $handler->handle($request);
        }
        return $toResponse;
    }
}