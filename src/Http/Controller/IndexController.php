<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\User\Repository\UserRepository;

class IndexController extends AbstractController
{

    public function __invoke(Request $request, Response $response): Response
    {
        if($request->getMethod() == 'POST') {
            $users = $this->getRepository(UserRepository::class)->extract('id', 'pseudo');
            $data = $request->getParsedBody();
            dd($data);
        }
        return $this->render($response, 'frontoffice/home/index.html.twig');
    }
}