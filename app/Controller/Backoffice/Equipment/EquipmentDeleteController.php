<?php
declare(strict_types=1);
namespace App\Controller\Backoffice\Equipment;

use App\AbstractController;
use App\Repository\EquipmentRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class EquipmentDeleteController extends AbstractController
{

    public function delete(Request $request, Response $response, array $args): Response
    {
        $equipment_id = (int)$args['id'];
        if($request->getMethod() === 'DELETE'){
            $this->getRepository(EquipmentRepository::class)->delete($equipment_id);
            $this->session->getFlash()->add('panel-success', "L'équipement a bien été supprimé");
        }
        return $this->redirectToRoute('dash_equipment');
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws \Envms\FluentPDO\Exception
     */
    public function deleteSelected(Request $request, Response $response, array $args): Response
    {
        if($request->getMethod() === 'DELETE'){
            $data = $request->getParsedBody()['delete_equipments'];
            if($data != null){
                foreach ($data as $d){
                    $this->getRepository(EquipmentRepository::class)->delete((int)$d);
                }
                $this->session->getFlash()->add('panel-info', "Les équipements ont bien été supprimés.");
                return $this->redirectToReferer($request);
            } else {
                $this->session->getFlash()->add('panel-error', "La sélection est vide !");
            }
        }
        return $this->redirectToReferer($request);
    }
}