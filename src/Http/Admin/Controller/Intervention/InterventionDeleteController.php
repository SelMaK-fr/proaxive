<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Admin\Controller\Intervention;

use Envms\FluentPDO\Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\Intervention\Repository\InterventionRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;

class InterventionDeleteController extends AbstractController
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
    public function delete(Request $request, Response $response, array $args): Response
    {
        $intervention_id = (int)$args['id'];
        if($request->getMethod() === 'DELETE') {
            $i = $this->getRepository(InterventionRepository::class)->find('id', $intervention_id);
            $data = $request->getParsedBody()['form_delete_intervention'];
            if($data['ref_number'] === $i->ref_number){
                $this->getRepository(InterventionRepository::class)->delete($intervention_id);
                $this->addFlash('panel-info', "L'intervention a bien été supprimée");
                return $this->redirectToRoute('dash_intervention');
            } else {
                $this->addFlash('panel-error', "La référence ne correspond pas !");
                return $this->redirectToReferer($request);
            }

        }
        return $this->redirectToRoute('dash_intervention');
    }
}