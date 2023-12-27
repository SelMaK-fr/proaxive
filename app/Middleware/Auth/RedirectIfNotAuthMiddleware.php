<?php
declare(strict_types=1);
namespace App\Middleware\Auth;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;

class RedirectIfNotAuthMiddleware
{

    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $term = str_contains($request->getUri()->getPath(), "admin");
        $response = new Response();
        if(!isset($_COOKIE['proaxive2-auth']) && $term){
            return $response->withHeader('Location', '/auth/login')->withStatus(302);
        } else {
            $toResponse = $handler->handle($request);
        }
        return $toResponse;
    }
}