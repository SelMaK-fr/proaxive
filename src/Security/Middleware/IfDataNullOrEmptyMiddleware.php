<?php

declare(strict_types=1);

namespace Selmak\Proaxive2\Security\Middleware;

use Envms\FluentPDO\Query;
use Odan\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Slim\Interfaces\RouteParserInterface;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Routing\RouteContext;

class IfDataNullOrEmptyMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly Query $db,
        private readonly RouteParserInterface $routeParser,
        private readonly LoggerInterface $logger,
        private readonly SessionInterface $session
    ){}
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $equipments = $this->db->from('equipments')
            ->select(null)
            ->select('COUNT(*) as count')
            ->fetch()
        ;
        $customer = $this->db->from('customers')
            ->select(null)
            ->select('COUNT(*) as count')
            ->fetch()
        ;
        if($equipments['count'] === 0 || $customer['query'] === 0){
            $response = new ResponseFactory();
            $this->logger->error('[Intervention] Impossible de créer une intervention : aucun équipement ou client.');
            $this->session->getFlash()->add('panel-error', 'Aucun équipement ou client.');
            return $response->createResponse()->withStatus(302)->withHeader('Location', $this->routeParser->urlFor('dash_home'));
        }
        return $handler->handle($request);
    }
}