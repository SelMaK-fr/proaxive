<?php

namespace App\Controller\Backoffice\Equipment\PeripheralDevice;

use App\AbstractController;
use App\Repository\EquipmentRepository;
use App\Type\PeripheralType;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PeripheralUpdateController extends AbstractController
{

    public function update(Request $request, Response $response, array $args): Response
    {
        $equipment_id = (int)$args['id'];
        $e = $this->getRepository(EquipmentRepository::class)->find('id', $equipment_id, true);
        $form = $this->createForm(PeripheralType::class, $e);
        $form->handleRequest();
        if($form->isSubmitted() && $form->isValid()) {
            $data = $form->getRequestData()['form_equipment'];
            $save = $this->getRepository(EquipmentRepository::class)->update($data, $equipment_id);
            if($save) {
                $this->session->getFlash()->add('panel-info', sprintf("Le périphérique - %s - a bien été mis à jour.", $data['name']));
                return $this->redirect($request->getServerParams()['HTTP_REFERER']);
            }
        }
        return $this->render($response, 'backoffice/equipment/device/update.html.twig', [
            'form' => $form,
            'e' => $e,
            'currentMenu' => 'equipment'
        ]);
    }
}