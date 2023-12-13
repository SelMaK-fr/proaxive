<?php
declare(strict_types=1);
namespace App\Controller\Backoffice\User;

use App\AbstractController;
use App\Repository\UserRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserReadController extends AbstractController
{

    public function read(Request $request, Response $response, array $args): Response
    {
        $user_id = (int)$args['id'];
        $u = $this->getRepository(UserRepository::class)->find('id', $user_id);
        return $this->render($response, 'backoffice/user/read.html.twig', [
            'u' => $u,
            'currentMenu' => 'user'
        ]);
    }
}