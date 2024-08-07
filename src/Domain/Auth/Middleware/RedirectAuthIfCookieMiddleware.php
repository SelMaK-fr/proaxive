<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Domain\Auth\Middleware;

use Envms\FluentPDO\Query;
use Odan\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Selmak\Proaxive2\Domain\Auth\SessionUser;
use Selmak\Proaxive2\Factory\CookieFactory;
use Slim\Psr7\Response as RPSR7;

class RedirectAuthIfCookieMiddleware implements MiddlewareInterface
{

    public function __construct(
        private readonly Query $query,
        private readonly SessionInterface $session,
        private readonly CookieFactory $cookie){}

    public function process(Request $request, RequestHandler $handler): Response
    {
        $response = new RPSR7();
        if($this->cookie->has('proaxive2-auth')){
            $user = $this->query->from('users')
                ->where('users.auth_token = ?', [$this->cookie->get('proaxive2-auth')])
                ->fetch()
            ;
            if($user){
                $this->session->set('auth', [
                    'id' => $user['id'],
                    'pseudo' => $user['pseudo'],
                    'fullname' => $user['fullname'],
                    'mail' => $user['mail'],
                    'roles' => $user['roles'],
                    'avatar' => $user['avatar'],
                    'auth_token' => $user['auth_token'],
                ]);
            }
            if($user['auth_token'] === $this->cookie->get('proaxive2-auth') && isset($_SESSION['auth'])){
                return $response->withHeader('Location', '/admin')->withStatus(302);
            }
        } else {
            $toResponse = $handler->handle($request);
        }
        return $toResponse;
    }
}