<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handle;
use Slim\Psr7\Response;

class RedirectIfNotAdminOrTechMiddleware
{
    public function __invoke(Request $request, Handle $handler): Request|ResponseInterface
    {
        $response = new Response();
        if(isset($_SESSION['auth'])){
            if($_SESSION['auth']['roles'] != 'SUPER_ADMIN' && $_SESSION['auth']['roles'] != 'USER_TECH')
            {
                return $response->withHeader('Location', '/admin/auth/perms')->withStatus(302);
            }

        } else {
            return $response->withHeader('Location', '/auth/login')->withStatus(302);
        }
        return $handler->handle($request);
    }
}