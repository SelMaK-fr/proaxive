<?php

namespace Selmak\Proaxive2\Domain\Intervention\Gallery\Middleware;

use Envms\FluentPDO\Query;
use Odan\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Interfaces\RouteParserInterface;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Routing\RouteContext;

class HasAccessToImageMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly SessionInterface $session,
        private readonly Query $db,
        private readonly RouteParserInterface $routeParser
    )
    {}
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $routeContext = RouteContext::fromRequest($request);
        $picture_id = (int)$routeContext->getRoutingResults()->getRouteArguments()['pid'];
        if(!$this->session->get('customer')) {
            $response = new ResponseFactory();
            return $response->createResponse()->withStatus(302)->withHeader('Location', $this->routeParser->urlFor('portal_login'));
        }
        $picture = $this->db->from('intervention_pictures')->where('id = ?', [$picture_id])->fetch();
        if($picture['is_online'] === 0 ){

        }
        return $handler->handle($request);
    }
}