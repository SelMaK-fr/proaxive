<?php

namespace Selmak\Proaxive2\Application\Middleware;

use Odan\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Views\Twig;

class TwigFlashMiddleware implements MiddlewareInterface
{
    private Twig $twig;
    private SessionInterface $session;

    public function __construct(Twig $twig, SessionInterface $session)
    {
        $this->twig = $twig;
        $this->session = $session;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $flash = $this->session->getFlash();
        $this->twig->getEnvironment()->addGlobal('flash', $flash);

        return $handler->handle($request);
    }
}