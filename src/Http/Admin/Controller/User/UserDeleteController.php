<?php

declare(strict_types=1);

namespace Selmak\Proaxive2\Http\Admin\Controller\User;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\User\Repository\UserRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;

class UserDeleteController extends AbstractController
{
    public function delete(Request $request, Response $response, array $args): Response
    {
        $user_id = (int)$args['id'];

        $customer = $this->getRepository(UserRepository::class)->find('id', $user_id);

        if (!$customer) {
            $this->addFlash('panel-error', "L'utilisateur n'existe pas.");
            return $this->redirectToRoute('dash_user');
        }

        if($request->getMethod() === 'DELETE'){
            $data = $request->getParsedBody();

            // Check user auth
            if ($user_id === $this->session->get('id')) {
                $this->addFlash('panel-error', "Vous ne pouvez pas supprimer cet utilisateur");
                return $this->redirectToRoute('dash_user');
            }

            if($data['fullname'] === $customer->fullname){
                $this->getRepository(UserRepository::class)->delete($user_id);
                $this->addFlash('panel-info', "L'utilisateur a bien été supprimé");
                return $this->redirectToRoute('dash_user');
            } else {
                $this->addFlash('panel-error', 'Le nom ne correspond pas !');
                return $this->redirectToReferer($request);
            }
        }
        return $this->redirectToRoute('dash_user');
    }
}