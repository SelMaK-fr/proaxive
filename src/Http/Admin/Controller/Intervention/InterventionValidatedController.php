<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Admin\Controller\Intervention;

use Envms\FluentPDO\Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\Equipment\Repository\EquipmentRepository;
use Selmak\Proaxive2\Domain\Intervention\Repository\InterventionRepository;
use Selmak\Proaxive2\Domain\User\Repository\UserRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Selmak\Proaxive2\Http\Type\Admin\Equipment\EquipmentFastType;
use Selmak\Proaxive2\Http\Type\Admin\Intervention\InterventionValidationType;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class InterventionValidatedController extends AbstractController
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
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $intervention_id = (int)$args['id'];
        $i = $this->getRepository(InterventionRepository::class)->find('id', $intervention_id);
        $u = $this->getRepository(UserRepository::class)->find('id', $i->users_id);
        // Search if equipment return 0 if none
        $e = $this->getRepository(EquipmentRepository::class)->countRowWhere((int)$i->customers_id, 'customers_id');
        // Add fast Equipment FORM
        $formEquipment = $this->createForm(EquipmentFastType::class);
        $formEquipment->setAction($this->getUrlFor('equipment_create_fast'));
        // Validated Intervention FORM
        $form = $this->createForm(InterventionValidationType::class, $i);
        $form->handleRequest();
        if($form->isSubmitted() && $form->isValid()) {
            $data = $form->getRequestData()['form_intervention_validation'];
            // Valid intervention
            // If equipment is empty
            if(isset($data['empty_equipment'])){
                $this->addFlash('panel-error', 'Veuillez créer un équipement pour ce client');
                return $this->redirectToReferer($request);
            }
            $data['state'] = 'PROGRESS';
            $data['way_steps'] = 1;
            $save = $this->getRepository(InterventionRepository::class)->update($data, $intervention_id);
            if($save){
                $this->addFlash('panel-info', sprintf("L'intervention N°%s a bien été validée.", $i->ref_number));
                return $this->redirectToRoute('intervention_update', ['id' => $i->id]);
            }
        }
        // Breadcrumbs
        $bds = $this->breadcrumbs;
        $bds->addCrumb('Accueil', $this->getUrlFor('dash_home'));
        $bds->addCrumb('Interventions', $this->getUrlFor('dash_intervention'));
        $bds->addCrumb('Validation', false);
        $bds->render();
        // .Breadcrumbs
        return $this->render($response, 'backoffice/intervention/validate.html.twig', [
            'i' => $i,
            'e' => $e,
            'u' => $u,
            'formEquipment' => $formEquipment,
            'breadcrumbs' => $bds,
            'form' => $form,
            'currentMenu' => 'intervention'
        ]);
    }
}