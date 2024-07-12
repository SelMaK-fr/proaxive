<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Domain\Auth\Middleware;

use Envms\FluentPDO\Query;
use Odan\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Selmak\Proaxive2\Domain\Auth\SessionUser;

class RegenSessionIfCookieExistMiddleware
{

    public function __construct(private readonly Query $query, private readonly SessionInterface $session)
    {

    }
    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if($_COOKIE['proaxive2-auth']){
            $user = $this->query->from('users')
                ->where('users.auth_token = ?', [$_COOKIE['proaxive2-auth']])
                ->fetch()
            ;
            if($user['auth_token'] === $_COOKIE['proaxive2-auth'] && !isset($_SESSION['auth'])){
                $this->session->set('auth', [
                    'id' => $user['id'],
                    'pseudo' => $user['pseudo'],
                    'fullname' => $user['fullname'],
                    'mail' => $user['mail'],
                    'roles' => $user['roles'],
                    'avatar' => $user['avatar'],
                    'auth_token' => $user['auth_token'],
                    'company_id' => $user['company_id'],
                ]);
            }
        }
        return $handler->handle($request);
    }
}