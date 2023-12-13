<?php
declare(strict_types=1);
namespace App\Middleware;

use Envms\FluentPDO\Query;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Interfaces\RouteParserInterface;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Routing\RouteContext;

class IfUpdateSocietyMiddleware implements MiddlewareInterface
{

    public function __construct(private readonly Query $db, private readonly RouteParserInterface $routeParser){}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();
        $u = $this->db->from('customers')
            ->select('id, is_society')
            ->where('customers.id = ?', [$route->getArgument('id')])
            ->fetch()
        ;
        if($u['is_society'] === 1){
            $response = new ResponseFactory();
            return $response->createResponse()->withStatus(302)->withHeader('Location', $this->routeParser->urlFor('society_update', ['id' => $u['id']]));
        }
        return $handler->handle($request);
    }
}