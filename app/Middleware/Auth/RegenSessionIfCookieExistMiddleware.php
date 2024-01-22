<?php
declare(strict_types=1);
namespace App\Middleware\Auth;

use Envms\FluentPDO\Query;
use Odan\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response as RPSR7;

class RegenSessionIfCookieExistMiddleware
{

    public function __construct(private readonly Query $query, private readonly SessionInterface $session)
    {

    }
    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if($_COOKIE['proaxive2-auth']){
            $parts = explode('==', $_COOKIE['proaxive2-auth']);
            $user_id = (int)$parts[0];
            $user = $this->query->from('users')
                ->where('users.id = ?', [$user_id])
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