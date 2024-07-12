<?php

namespace Selmak\Proaxive2\Security\Middleware;

use Odan\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Selmak\Proaxive2\Infrastructure\Parameter\Interface\ParameterInterface;
use Slim\Interfaces\RouteParserInterface;
use Slim\Psr7\Factory\ResponseFactory;

final class IfMailerIsNotActivateMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly ParameterInterface $parameter,
        private readonly RouteParserInterface $routeParser,
        private readonly LoggerInterface $logger,
        private readonly SessionInterface $session
    ){}
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if($this->parameter->getParam('mail_auto') === 0){
            $response = new ResponseFactory();
            $this->logger->error('[PHPMailer] Impossible de poursuivre, service d\'envoi de courriel désactivé.');
            $this->session->getFlash()->add('panel-error', 'Erreur : veuillez activer le service Mail.');
            return $response->createResponse()->withStatus(302)->withHeader('Location', $this->routeParser->urlFor('dash_home'));
        }
        return $handler->handle($request);
    }
}