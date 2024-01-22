<?php
declare(strict_types=1);
namespace App\Middleware\Equipment;

use Envms\FluentPDO\Query;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Interfaces\RouteParserInterface;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Routing\RouteContext;

class IfUpdatePeripheralMiddleware implements MiddlewareInterface
{
    public function __construct(private readonly Query $db, private readonly RouteParserInterface $routeParser){}
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();
        $e = $this->db->from('equipments')
            ->select('equipments.id, te.id te_id, types_equipments_id, is_peripheral')
            ->leftJoin('types_equipments as te ON te.id = equipments.types_equipments_id')
            ->where('equipments.id = ?', [$route->getArgument('id')])
            ->fetch()
        ;
        if($e['is_peripheral'] === 1){
            $response = new ResponseFactory();
            return $response->createResponse()->withStatus(302)->withHeader('Location', $this->routeParser->urlFor('equipment_update_device', ['id' => $e['id']]));
        }
        return $handler->handle($request);
    }
}