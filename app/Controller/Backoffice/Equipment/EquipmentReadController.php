<?php
declare(strict_types=1);
namespace App\Controller\Backoffice\Equipment;

use App\AbstractController;
use App\Repository\CustomerRepository;
use App\Repository\EquipmentRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class EquipmentReadController extends AbstractController
{

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws \Envms\FluentPDO\Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function read(Request $request, Response $response, array $args): Response
    {
        $equipment_id = (int)$args['id'];
        $e = $this->getRepository(EquipmentRepository::class)->findWithTypeBool($equipment_id);
        $c = $this->getRepository(CustomerRepository::class)->find('id', $e->customers_id);

        //
        $bds = $this->app->getContainer()->get('breadcrumbs');
        $bds->addCrumb('Accueil', $this->routeParser->urlFor('dash_home'));
        $bds->addCrumb('Equipements', $this->routeParser->urlFor('dash_equipment'));
        $bds->addCrumb($e->customer_name, $this->routeParser->urlFor('customer_read', ['id' => $e->customers_id]));
        $bds->addCrumb($e->te_name, false);
        $bds->addCrumb($e->name, false);
        $bds->render();
        //
        return $this->render($response, 'backoffice/equipment/read.html.twig', [
            'currentMenu' => 'equipment',
            'e' => $e,
            'c' => $c,
            'breadcrumbs' => $bds
        ]);
    }
}