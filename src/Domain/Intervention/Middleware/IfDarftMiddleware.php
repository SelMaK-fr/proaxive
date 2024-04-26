<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Domain\Intervention\Middleware;

use Envms\FluentPDO\Query;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Slim\Interfaces\RouteParserInterface;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Routing\RouteContext;

class IfDarftMiddleware implements MiddlewareInterface
{

    public function __construct(private readonly Query $db, private readonly RouteParserInterface $routeParser){}

    public function process(Request $request, Handler $handler): Response
    {
        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();
        $i = $this->db->from('interventions')
            ->select('id, state')
            ->where('interventions.id = ?', [$route->getArgument('id')])
            ->fetch()
        ;
        if($i && $i['state'] === 'DRAFT' || $i['state'] === 'PENDING'){
            $response = new ResponseFactory();
            return $response->createResponse()->withStatus(302)->withHeader('Location', $this->routeParser->urlFor('intervention_validation', ['id' => $route->getArgument('id')]));
        }
        return $handler->handle($request);
    }
}