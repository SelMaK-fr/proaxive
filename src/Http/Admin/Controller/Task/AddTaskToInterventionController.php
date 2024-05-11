<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Admin\Controller\Task;

use Envms\FluentPDO\Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\Task\Repository\TaskAssocRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;

class AddTaskToInterventionController extends AbstractController
{

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws Exception
     */
    public function addToIntervention(Request $request, Response $response, array $args): Response
    {
        $intervention_id = (int)$args['id'];
        if($request->getMethod() === 'POST') {
            $data = $request->getParsedBody()['form_tasks'];
            $tasksForI = $this->getRepository(TaskAssocRepository::class)->findForSelect2($intervention_id);
            foreach ($tasksForI as $a){
                $tasksIn[] = [
                    'tasks_id' => $a['tasks_id'],
                    'interventions_id' => $a['interventions_id']
                ];
            }
            foreach ($data['tasks'] as $t){
                $array[] = [
                    'tasks_id' => $t,
                    'interventions_id' => $intervention_id
                ];
            }
            foreach ($tasksForI as $dt){
                foreach ($array as $dataT){
                    if((int)$dataT['tasks_id'] === $dt['tasks_id']){
                        $this->addFlash('error', 'Cette tâche a déjà été effectuée !');
                        return $this->redirectToRoute('intervention_read', ['id' => $intervention_id]);
                    }
                }
            }
            $tasksAssoc = $this->getRepository(TaskAssocRepository::class)->add($array);
            if($tasksAssoc){
                $this->addFlash('panel-info', 'Tâche(s) sauvegardée(s) pour cette intervention.');
            } else {
                $this->addFlash('panel-error', 'Impossible de poursuivre.');
            }
            return $this->redirectToRoute('intervention_read', ['id' => $intervention_id]);
        }
        return $response;
    }
}