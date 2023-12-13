<?php
declare(strict_types=1);
namespace App\Controller\Backoffice\Intervention;

use App\AbstractController;
use App\Repository\InterventionRepository;
use App\Repository\TaskAssocRepository;
use App\Type\InterventionUpdateType;
use App\Type\TaskListType;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class InterventionReadController extends AbstractController
{

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws \Envms\FluentPDO\Exception
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function read(Request $request, Response $response, array $args): Response
    {
        $intervention_id = (int)$args['id'];

        $i = $this->getRepository(InterventionRepository::class)->joinForId($intervention_id);
        $taskForI = $this->getRepository(TaskAssocRepository::class)->findByIntervention($intervention_id);

        // Add task
        $formTasks = $this->createForm(TaskListType::class);
        $formTasks->setAction($this->routeParser->urlFor('task_add_intervention', ['id' => $intervention_id]));

        // Form Update Intervention
        $form = $this->createForm(InterventionUpdateType::class, $i);
        $form->setAction($this->routeParser->urlFor('intervention_update', ['id' => $intervention_id]));
        $form->handleRequest();

        return $this->render($response, 'backoffice/intervention/read.html.twig', [
           'i' => $i,
           'intervention_id' => $intervention_id,
           'tasks' => '',
           'tForI' => $taskForI,
           'formTasks' => $formTasks,
           'form' => $form,
           'currentMenu' => 'intervention'
        ]);

    }
}