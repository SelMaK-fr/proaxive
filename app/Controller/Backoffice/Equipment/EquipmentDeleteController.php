<?php
declare(strict_types=1);
namespace App\Controller\Backoffice\Equipment;

use App\AbstractController;
use App\Repository\EquipmentRepository;
use Envms\FluentPDO\Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class EquipmentDeleteController extends AbstractController
{

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws Exception
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function delete(Request $request, Response $response, array $args): Response
    {
        $equipment_id = (int)$args['id'];
        if($request->getMethod() === 'DELETE'){
            $this->getRepository(EquipmentRepository::class)->delete($equipment_id);
            $this->addFlash('panel-success', "L'équipement a bien été supprimé");
        }
        return $this->redirectToRoute('dash_equipment');
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws ContainerExceptionInterface
     * @throws Exception
     * @throws NotFoundExceptionInterface
     */
    public function deleteSelected(Request $request, Response $response, array $args): Response
    {
        if($request->getMethod() === 'DELETE'){
            $data = $request->getParsedBody()['delete_equipments'];
            if($data != null){
                foreach ($data as $d){
                    $this->getRepository(EquipmentRepository::class)->delete((int)$d);
                }
                $this->addFlash('panel-info', "Les équipements ont bien été supprimés.");
                return $this->redirectToReferer($request);
            } else {
                $this->addFlash('panel-error', "La sélection est vide !");
            }
        }
        return $this->redirectToReferer($request);
    }
}