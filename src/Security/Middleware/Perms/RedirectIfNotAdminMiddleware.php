<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Security\Middleware\Perms;

use Odan\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handle;
use Selmak\Proaxive2\Domain\Auth\SessionUser;
use Slim\Psr7\Response;

class RedirectIfNotAdminMiddleware
{

    public function __construct(private readonly SessionInterface $session){}

    public function __invoke(Request $request, Handle $handler): Request|ResponseInterface
    {
        if($this->session->get('auth')){
            if($this->session->get('auth')['roles'] != 'SUPER_ADMIN')
            {
                $response = new Response();
                return $response->withHeader('Location', '/admin/auth/perms')->withStatus(302);
            }

        }
        return $handler->handle($request);
    }
}