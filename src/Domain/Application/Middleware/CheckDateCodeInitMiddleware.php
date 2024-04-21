<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Domain\Application\Middleware;

use Envms\FluentPDO\Query;
use Odan\Session\SessionInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Interfaces\RouteParserInterface;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Routing\RouteContext;

class CheckDateCodeInitMiddleware implements MiddlewareInterface
{

    const LIMIT_TIME = 10;
    private ContainerInterface $container;
    private RouteParserInterface $routeParser;

    public function __construct(ContainerInterface $container, RouteParserInterface $routeParser)
    {
        $this->container = $container;
        $this->routeParser = $routeParser;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();
        $db = $this->container->get(Query::class);
        $user = $db->from('users')
            ->select('id, token')
            ->where('token = ?', [$route->getArgument('token')])
            ->fetch()
        ;
        $session = $this->container->get(SessionInterface::class);
        $response = new ResponseFactory();
        if($user){
            $dateCode = date_create_immutable($user['reset_at']);
            $today = date_create_immutable();
            $interval = $dateCode->diff($today);
            // Check Token
            if($user['token'] != $route->getArgument('token')){
                $session->getFlash()->add('error', 'Le token ne correspond pas à cet utilisateur.');
                return $response->createResponse()->withStatus(302)->withHeader('Location', $this->routeParser->urlFor('auth_user_login'));
            }
            // Check time for verification code
            if($interval->i > self::LIMIT_TIME){
                $session->getFlash()->add('error', 'Les 10 minutes sont écoulées pour ce token.');
                return $response->createResponse()->withStatus(302)->withHeader('Location', $this->routeParser->urlFor('auth_user_login'));
            }
        } else {
            $session->getFlash()->add('error', 'Token ou utilisateur introuvable.');
            return $response->createResponse()->withStatus(302)->withHeader('Location', $this->routeParser->urlFor('auth_user_login'));
        }

        return $handler->handle($request);
    }
}