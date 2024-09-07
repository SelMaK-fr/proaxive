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

class DeleteTaskOfInterventionController extends AbstractController
{

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws Exception
     */
    public function deleteOfI(Request $request, Response $response, array $args): Response
    {
        $intervention_id = (int)$args['id'];
        $task_id = (int)$args['task'];
        if($request->getMethod() === 'POST') {
            $t = $this->getRepository(TaskAssocRepository::class)->search($intervention_id, $task_id);
            if($t->interventions_id != $intervention_id){
                $this->addFlash('panel-error', 'aucune tâche trouvée !');
                return $this->redirectToRoute('intervention_read', ['id' => $intervention_id]);
            }
            $this->getRepository(TaskAssocRepository::class)->delete((int)$t->id);
            $this->addFlash('panel-info', 'Tâche supprimée avec succès.');
            return $this->redirectToRoute('intervention_read', ['id' => $intervention_id]);
        }
        return $response;
    }
}