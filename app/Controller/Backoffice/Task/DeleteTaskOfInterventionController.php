<?php
declare(strict_types=1);
namespace App\Controller\Backoffice\Task;

use App\AbstractController;
use App\Repository\TaskAssocRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class DeleteTaskOfInterventionController extends AbstractController
{
    public function deleteOfI(Request $request, Response $response, array $args): Response
    {
        $intervention_id = (int)$args['id'];
        $task_id = (int)$args['task'];
        if($request->getMethod() === 'POST') {
            $t = $this->getRepository(TaskAssocRepository::class)->search($intervention_id, $task_id);
            if($t['interventions_id'] != $intervention_id){
                $this->session->getFlash()->add('panel-error', 'aucune tâche trouvée !');
                return $this->redirectToRoute('intervention_read', ['id' => $intervention_id]);
            }
            $this->getRepository(TaskAssocRepository::class)->delete($t['id']);
            $this->session->getFlash()->add('panel-info', 'Tâche supprimée avec succès.');
            return $this->redirectToRoute('intervention_read', ['id' => $intervention_id]);
        }
        return $response;
    }
}