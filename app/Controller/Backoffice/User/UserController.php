<?php
declare(strict_types=1);
namespace App\Controller\Backoffice\User;

use App\AbstractController;
use App\Repository\UserRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserController extends AbstractController
{

    public function index(Request $request, Response $response): Response
    {
        $users = $this->getRepository(UserRepository::class)->allWithCompany();

        return $this->render($response, 'backoffice/user/index.html.twig', [
            'users' => $users,
            'currentMenu' => 'user'
        ]);
    }
}