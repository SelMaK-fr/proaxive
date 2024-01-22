<?php
declare(strict_types=1);
namespace App\Controller\Backoffice\Intervention;

use App\AbstractController;
use App\Repository\InterventionRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class InterventionDeleteController extends AbstractController
{

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws \Envms\FluentPDO\Exception
     */
    public function delete(Request $request, Response $response, array $args): Response
    {
        $intervention_id = (int)$args['id'];
        if($request->getMethod() === 'DELETE') {
            $i = $this->getRepository(InterventionRepository::class)->find('id', $intervention_id);
            $data = $request->getParsedBody()['form_delete_intervention'];
            if($data['ref_number'] === $i->ref_number){
                $this->getRepository(InterventionRepository::class)->delete($intervention_id);
                $this->session->getFlash()->add('panel-info', "L'intervention a bien été supprimée");
                return $this->redirectToRoute('dash_intervention');
            } else {
                $this->session->getFlash()->add('panel-error', "La référence ne correspond pas !");
                return $this->redirectToReferer($request);
            }

        }
        return $this->redirectToRoute('dash_intervention');
    }
}