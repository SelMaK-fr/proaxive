<?php

declare(strict_types=1);

namespace Selmak\Proaxive2\Http\Controller\Portal;

use Odan\Session\SessionInterface;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Slim\App;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class LogoutController extends AbstractController
{

    public function __invoke(Request $request, Response $response): Response
    {
        if($this->session->get('customer')) {
            $this->session->delete('customer');
            //setcookie('proaxive2-auth', '', -1, '/');
        }
        return $response->withStatus(302)->withHeader('Location', $this->getUrlFor('app_home'));
    }
}