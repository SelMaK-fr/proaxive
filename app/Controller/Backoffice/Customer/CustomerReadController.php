<?php
declare(strict_types=1);
namespace App\Controller\Backoffice\Customer;

use App\AbstractController;
use App\Repository\CustomerRepository;
use App\Repository\EquipmentRepository;
use App\Repository\InterventionRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CustomerReadController extends AbstractController
{
    public function read(Request $request, Response $response, array $arg): Response
    {
        $customer_id = (int)$arg['id'];
        $customer = $this->getRepository(CustomerRepository::class)->find('id', $customer_id);
        $equipments = $this->getRepository(EquipmentRepository::class)->allBy('customers_id', $customer_id);
        $interventions = $this->getRepository(InterventionRepository::class)->allBy('customers_id', $customer_id);
        // Breadcrumbs
        $bds = $this->app->getContainer()->get('breadcrumbs');
        $bds->addCrumb('Accueil', $this->routeParser->urlFor('dash_home'));
        $bds->addCrumb('Clients', $this->routeParser->urlFor('dash_customer'));
        $bds->addCrumb($customer->fullname, false);
        $bds->render();
        // .Breadcrumbs
        return $this->render($response, 'backoffice/customer/read.html.twig', [
            'customer' => $customer,
            'equipments' => $equipments,
            'breadcrumbs' => $bds,
            'interventions' => $interventions,
            'currentMenu' => 'customer'
        ]);
    }
}