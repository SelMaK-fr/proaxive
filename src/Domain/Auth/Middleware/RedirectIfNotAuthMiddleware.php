<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Domain\Auth\Middleware;

use Envms\FluentPDO\Query;
use Odan\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Selmak\Proaxive2\Factory\CookieFactory;
use Slim\Psr7\Response;

class RedirectIfNotAuthMiddleware
{
    public function __construct(
        private readonly Query $query,
        private readonly SessionInterface $session,
        private readonly CookieFactory $cookie){}

    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $term = str_contains($request->getUri()->getPath(), "admin");
        $response = new Response();
        $token = null;
        if($this->cookie->get('proaxive2-auth') !== null){
            $user = $this->query->from('users')
                ->where('users.auth_token = ?', [$this->cookie->get('proaxive2-auth')])
                ->fetch()
            ;
            if($this->cookie->get('proaxive2-auth') === $user['auth_token']){
                $this->session->set('auth', [
                    'id' => $user['id'],
                    'company_id' => $user['company_id'],
                    'pseudo' => $user['pseudo'],
                    'fullname' => $user['fullname'],
                    'mail' => $user['mail'],
                    'roles' => $user['roles'],
                    'avatar' => $user['avatar'],
                    'auth_token' => $user['auth_token'],
                ]);
                $token = true;
            }
        }
        if(!$token && $term && !$this->session->get('auth')){
            $this->session->getFlash()->add('danger', 'Violation de cookie, merci de vous reconnecter !');
            return $response->withHeader('Location', '/auth/login')->withStatus(302);
        }
        return $handler->handle($request);
    }
}