<?php
declare(strict_types=1);
namespace App\Controller\Backoffice\Task;

use App\AbstractController;
use App\Repository\TaskAssocRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AddTaskToInterventionController extends AbstractController
{
    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws \Envms\FluentPDO\Exception
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
                        $this->session->getFlash()->add('error', 'Cette tâche a déjà été effectuée !');
                        return $this->redirect($this->routeParser->urlFor('intervention_read', ['id' => $intervention_id]));
                    }
                }
            }
            $tasksAssoc = $this->getRepository(TaskAssocRepository::class)->add($array);
            if($tasksAssoc){
                $this->session->getFlash()->add('panel-info', 'Tâche(s) sauvegardée(s) pour cette intervention.');
            } else {
                $this->session->getFlash()->add('panel-error', 'Impossible de poursuivre.');
            }
            return $this->redirect($this->routeParser->urlFor('intervention_read', ['id' => $intervention_id]));
        }
        return $response;
    }
}