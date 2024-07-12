<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Security\Middleware\Perms;

use Odan\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handle;
use Slim\Psr7\Response;

class RedirectIfNotAdminOrManagerMiddleware
{
    public function __construct(private readonly SessionInterface $session){}

    public function __invoke(Request $request, Handle $handler): Request|ResponseInterface
    {
        $response = new Response();
        if($this->session->get('auth')){
            if($this->session->get('auth')['roles'] != 'SUPER_ADMIN' && $this->session->get('auth')['roles'] != 'USER_MANAGER')
            {

                return $response->withHeader('Location', '/admin/auth/perms')->withStatus(302);
            }

        } else {
            return $response->withHeader('Location', '/auth/login')->withStatus(302);
        }
        return $handler->handle($request);
    }
}