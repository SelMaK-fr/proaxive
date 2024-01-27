<?php
declare(strict_types=1);
namespace App\Controller\Backoffice\Equipment;

use App\AbstractController;
use App\Repository\CustomerRepository;
use App\Repository\EquipmentRepository;
use App\Repository\InterventionRepository;
use Envms\FluentPDO\Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class EquipmentReadController extends AbstractController
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
    public function read(Request $request, Response $response, array $args): Response
    {
        $equipment_id = (int)$args['id'];
        $e = $this->getRepository(EquipmentRepository::class)->findWithTypeBool($equipment_id);
        $c = $this->getRepository(CustomerRepository::class)->find('id', $e->customers_id);
        $i = $this->getRepository(InterventionRepository::class)->allBy('equipments_id', $e->id);
        //
        $bds = $this->app->getContainer()->get('breadcrumbs');
        $bds->addCrumb('Accueil', $this->getUrlFor('dash_home'));
        $bds->addCrumb('Equipements', $this->getUrlFor('dash_equipment'));
        $bds->addCrumb($e->customer_name, $this->getUrlFor('customer_read', ['id' => $e->customers_id]));
        $bds->addCrumb($e->te_name, false);
        $bds->addCrumb($e->name, false);
        $bds->render();
        //
        return $this->render($response, 'backoffice/equipment/read.html.twig', [
            'currentMenu' => 'equipment',
            'e' => $e,
            'c' => $c,
            'i' => $i,
            'breadcrumbs' => $bds
        ]);
    }
}