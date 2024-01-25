<?php
declare(strict_types=1);
namespace App\Controller\Backoffice\Intervention;

use App\AbstractController;
use App\Repository\EquipmentRepository;
use App\Repository\InterventionRepository;
use App\Type\EquipmentFastType;
use App\Type\InterventionValidationType;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class InterventionValidatedController extends AbstractController
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
    public function validated(Request $request, Response $response, array $args): Response
    {
        $intervention_id = (int)$args['id'];
        $i = $this->getRepository(InterventionRepository::class)->find('id', $intervention_id);
        // Search if equipment return 0 if none
        $e = $this->getRepository(EquipmentRepository::class)->countRowWhere($i->customers_id, 'customers_id');

        // Add fast Equipment FORM
        $formEquipment = $this->createForm(EquipmentFastType::class);
        $formEquipment->setAction($this->routeParser->urlFor('equipment_create_fast'));
        // Validated Intervention FORM
        $form = $this->createForm(InterventionValidationType::class, $i);
        $form->handleRequest();
        if($form->isSubmitted() && $form->isValid()) {
            $data = $form->getRequestData()['form_intervention_validation'];
            // Valid intervention
            // If equipment is empty
            if(isset($data['empty_equipment'])){
                $this->session->getFlash()->add('panel-error', 'Veuillez créer un équipement pour ce client');
                return $this->redirectToReferer($request);
            }
            $data['state'] = 'VALIDATED';
            $save = $this->getRepository(InterventionRepository::class)->update($data, $intervention_id);
            if($save){
                $this->session->getFlash()->add('panel-info', sprintf("L'intervention N°%s a bien été validée.", $i->ref_number));
                return $this->redirectToRoute('intervention_read', ['id' => $i->id]);
            }
        }
        // Breadcrumbs
        $bds = $this->app->getContainer()->get('breadcrumbs');
        $bds->addCrumb('Accueil', $this->routeParser->urlFor('dash_home'));
        $bds->addCrumb('Interventions', $this->routeParser->urlFor('dash_intervention'));
        $bds->addCrumb('Validation', false);
        $bds->render();
        // .Breadcrumbs
        return $this->render($response, 'backoffice/intervention/validate.html.twig', [
            'i' => $i,
            'e' => $e,
            'formEquipment' => $formEquipment,
            'breadcrumbs' => $bds,
            'form' => $form,
            'currentMenu' => 'intervention'
        ]);
    }
}