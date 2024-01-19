<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handle;
use Slim\Psr7\Response;

class RedirectIfNotAdminMiddleware
{
    public function __invoke(Request $request, Handle $handler): Request|ResponseInterface
    {
        if(isset($_SESSION['auth'])){
            if($_SESSION['auth']['roles'] != 'SUPER_ADMIN')
            {
                $response = new Response();
                return $response->withHeader('Location', '/admin/auth/perms')->withStatus(302);
            }

        }
        return $handler->handle($request);
    }
}