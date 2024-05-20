<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Admin\Controller\Intervention;

use Envms\FluentPDO\Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\EDocument\Repository\EDocumentRepository;
use Selmak\Proaxive2\Domain\Intervention\Repository\InterventionRepository;
use Selmak\Proaxive2\Domain\Task\Repository\TaskAssocRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Selmak\Proaxive2\Http\Type\Admin\Intervention\InterventionUpdateType;
use Selmak\Proaxive2\Http\Type\Admin\TaskListType;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class InterventionReadController extends AbstractController
{

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws ContainerExceptionInterface
     * @throws Exception
     * @throws NotFoundExceptionInterface
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function read(Request $request, Response $response, array $args): Response
    {
        $intervention_id = (int)$args['id'];
        $i = $this->getRepository(InterventionRepository::class)->joinForId($intervention_id);
        if(!$i) {
            $this->addFlash('panel-error', "Cette intervention n'existe pas !");
            return $this->redirectToRoute('dash_intervention');
        }
        $taskForI = $this->getRepository(TaskAssocRepository::class)->findByIntervention($intervention_id);

        // Add task
        $formTasks = $this->createForm(TaskListType::class);
        $formTasks->setAction($this->getUrlFor('task_add_intervention', ['id' => $intervention_id]));

        // Form Update Intervention
        $form = $this->createForm(InterventionUpdateType::class, $i);
        $form->setAction($this->getUrlFor('intervention_update', ['id' => $intervention_id]));
        $form->handleRequest();

        // Find Documents
        $documents = $this->getRepository(EDocumentRepository::class)->allBy('interventions_id', $intervention_id);

        // Breadcrumbs
        $bds = $this->breadcrumbs;
        $bds->addCrumb('Accueil', $this->getUrlFor('dash_home'));
        $bds->addCrumb('Interventions', $this->getUrlFor('dash_intervention'));
        $bds->addCrumb($i['customer_name'], $this->getUrlFor('customer_read', ['id' => $i['customers_id']]));
        $bds->addCrumb($i['equipment_name'], $this->getUrlFor('equipment_read', ['id' => $i['equipments_id']]));
        $bds->addCrumb($i['ref_number'], false);
        $bds->render();
        // .Breadcrumbs

        return $this->render($response, 'backoffice/intervention/read.html.twig', [
           'i' => $i,
           'intervention_id' => $intervention_id,
           'tasks' => '',
           'tForI' => $taskForI,
           'formTasks' => $formTasks,
           'breadcrumbs' => $bds,
           'form' => $form,
           'currentMenu' => 'intervention',
            'documents' => $documents
        ]);

    }
}