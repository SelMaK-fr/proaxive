<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Admin\Controller\User;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\User\Repository\UserRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class UserController extends AbstractController
{

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function index(Request $request, Response $response): Response
    {
        $users = $this->getRepository(UserRepository::class)->allWithCompany();

        return $this->render($response, 'backoffice/user/index.html.twig', [
            'users' => $users,
            'currentMenu' => 'user'
        ]);
    }
}