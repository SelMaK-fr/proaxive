<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Domain\Intervention\Middleware;

use Envms\FluentPDO\Query;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Interfaces\RouteParserInterface;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Routing\RouteContext;

class IfIdIsNullMiddleware implements MiddlewareInterface
{

    private Query $db;
    private RouteParserInterface $routeParser;

    public function __construct(Query $db, RouteParserInterface $routeParser){
        $this->db = $db;
        $this->routeParser = $routeParser;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();
        $i = $this->db->from('interventions')
            ->select('id, state')
            ->where('interventions.id = ?', [$route->getArgument('id')])
            ->fetch()
        ;
        if($i && $i['customers_id'] === null || $i['equipments_id'] === null){
            $response = new ResponseFactory();
            if($i['state'] === 'ARCHIVE'){
                return $response->createResponse()->withStatus(302)->withHeader('Location', $this->routeParser->urlFor('intervention_archive_read', ['id' => $route->getArgument('id')]));
            }
            return $response->createResponse()->withStatus(302)->withHeader('Location', $this->routeParser->urlFor('intervention_archive', ['id' => $route->getArgument('id')]));
        }
        return $handler->handle($request);
    }
}