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
use Selmak\Proaxive2\Http\Type\Admin\Booking\BookingForInterventionType;
use Selmak\Proaxive2\Http\Type\Admin\Intervention\InterventionDiagType;
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
        $taskForI = $this->getRepository(TaskAssocRepository::class)->findByIntervention($intervention_id);

        // Add task
        $formTasks = $this->createForm(TaskListType::class);
        $formTasks->setAction($this->getUrlFor('task_add_intervention', ['id' => $intervention_id]));

        // Form Update Intervention
        $form = $this->createForm(InterventionUpdateType::class, $i);
        $form->setAction($this->getUrlFor('intervention_update', ['id' => $intervention_id]));
        $form->handleRequest();

        // Form insert Booking
        $formBooking = $this->createForm(BookingForInterventionType::class);
        $formBooking->setAction($this->getUrlFor('add_booking_for_intervention'));
        $formBooking->handleRequest();

        // Form Diag
        $formDiag = $this->createForm(InterventionDiagType::class, $i);
        $formDiag->setAction($this->getUrlFor('intervention_update', ['id' => $intervention_id]));
        $formDiag->handleRequest();

        // Breadcrumbs
        $bds = $this->breadcrumbs;
        $bds->addCrumb('Accueil', $this->getUrlFor('dash_home'));
        $bds->addCrumb('Interventions', $this->getUrlFor('dash_intervention'));
        $bds->addCrumb($this->sanitize($i['customer_name']), $this->getUrlFor('customer_read', ['id' => $i['customers_id']]));
        $bds->addCrumb($this->sanitize($i['equipment_name']), $this->getUrlFor('equipment_read', ['id' => $i['equipments_id']]));
        $bds->addCrumb($this->sanitize($i['ref_number']), false);
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
           'formBooking' => $formBooking,
           'formDiag' => $formDiag,
           'currentMenu' => 'intervention'
        ]);

    }
}