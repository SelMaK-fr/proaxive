<?php

namespace Selmak\Proaxive2\Domain\Application\Middleware;

use Odan\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Csrf\Guard;

class SessionMiddleware implements MiddlewareInterface
{

    public function __construct(private readonly SessionInterface $session, private readonly Guard $guard){}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (!$this->session->isStarted()) {
            $this->session->start();
        }
        $response = $handler->handle($request);
        $this->guard->setStorage($this);
        $this->session->save();

        return $response;
    }
}