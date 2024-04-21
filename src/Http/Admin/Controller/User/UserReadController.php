<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Admin\Controller\User;

use Envms\FluentPDO\Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\Intervention\Repository\InterventionRepository;
use Selmak\Proaxive2\Domain\User\Repository\UserRepository;
use Selmak\Proaxive2\Domain\Workshop\Repository\WorkshopRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;

class UserReadController extends AbstractController
{

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws Exception
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function read(Request $request, Response $response, array $args): Response
    {
        $user_id = (int)$args['id'];
        $u = $this->getRepository(UserRepository::class)->find('id', $user_id);
        // Redirect if roles = USER_MANAGER
        if($u->roles === 'USER_MANAGER'){
            return $this->redirectToRoute('user_update', ['id' => $u->id]);
        }
        $i = $this->getRepository(InterventionRepository::class)->allBy('users_id', $user_id, 6);
        $w = $this->getRepository(WorkshopRepository::class)->find('id', $u->company_id);
        return $this->render($response, 'backoffice/user/read.html.twig', [
            'u' => $u,
            'i' => $i,
            'w' => $w,
            'currentMenu' => 'user'
        ]);
    }
}