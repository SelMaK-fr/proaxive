<?php

namespace Selmak\Proaxive2\Domain\Intervention\Middleware;

use Envms\FluentPDO\Query;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Slim\Interfaces\RouteParserInterface;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Routing\RouteContext;

class CheckUrlCreateMiddleware implements MiddlewareInterface
{

    public function __construct(
        private readonly Query $db,
        private readonly RouteParserInterface $routeParser,
        private readonly LoggerInterface $logger)
    {}
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();
        $i = $this->db->from('interventions')
            ->select('id, state')
            ->where('interventions.id = ?', [$request->getQueryParams()['inter']])
            ->fetch()
        ;
        if(!filter_input(INPUT_GET, 'inter', FILTER_VALIDATE_INT)){
            $this->logger->error("[Intervention] Le paramètre dans l'URL n'est pas valide, il faut renseigner un entier (ID Intervention)");
            throw new \Exception(sprintf("%s n'est pas un entier.", $request->getQueryParams()['inter']));
        }
        if($i === false){
            $response = new ResponseFactory();
            return $response->createResponse()->withStatus(302)->withHeader('Location', $this->routeParser->urlFor('dash_intervention'));
        }
        return $handler->handle($request);
    }
}