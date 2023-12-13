<?php

namespace App\Controller\Backoffice\Equipment;

use App\AbstractController;
use App\Repository\CustomerRepository;
use App\Repository\EquipmentRepository;
use App\Type\EquipmentSpecsType;
use App\Type\EquipmentType;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class EquipmentCreateController extends AbstractController
{

    public function create(Request $request, Response $response, array $args): Response
    {
        // init var customer
        $c = '';
        // check if id passed in parameter
        $customer_id = (int)$args['id'];
        if($customer_id){
            $c = $this->getRepository(CustomerRepository::class)->find('id', $customer_id);
        }
        $form = $this->createForm(EquipmentType::class);
        $form->handleRequest();
        if($form->isSubmitted() && $form->isValid()) {
            $data = $form->getRequestData()['form_equipment'];
            if($customer_id){$data_customer_id = $customer_id;}else{$data_customer_id = $data['customers_id'];}
            $cName = $this->getRepository(CustomerRepository::class)->find('id', $data_customer_id);
            $data['customer_name'] = $cName->fullname;
            $this->getRepository(EquipmentRepository::class)->add($data, true);
            $this->session->getFlash()->add('panel-info', sprintf("Le nouvel équipement %s a bien été créé.", $data['name']));
            return $this->redirectToRoute('dash_equipment');
        }
        return $this->render($response, 'backoffice/equipment/create.html.twig', [
            'form' => $form,
            'c' => $c,
            'currentMenu' => 'equipment'
        ]);
    }

    public function createSpecificities(Request $request, Response $response): Response
    {
        if(!$this->session->get('form_equipment_specs')) {
            $this->session->getFlash()->add('panel-error', "La session a expirée pour ce formulaire.");
            return $this->redirectToRoute('equipment_create');
        }

        $dataSession = $this->session->get('form_equipment_specs');

        $session = $dataSession[1];
        $dataEquipment = $dataSession[0];
        $form = $this->createForm(EquipmentSpecsType::class);
        $form->handleRequest();
        if($form->isSubmitted() && $form->isValid()) {
            $data = $form->getRequestData()['form_equipment_specs'];
            $pushAllData = $data + $session + $dataEquipment;
            $this->getRepository(EquipmentRepository::class)->add($pushAllData, true);
            $this->session->getFlash()->add('panel-info', sprintf("Le nouvel équipement %s a bien été créé.", $pushAllData['name']));
            $this->session->delete('form_equipment_specs');
            return $this->redirectToRoute('dash_equipment');
        }

        return $this->render($response, 'backoffice/equipment/create_specs.html.twig', [
            'form' => $form,
            'session' => $session,
            'dataEquipment' => $dataEquipment,
            'currentMenu' => 'equipment'
        ]);
    }

}