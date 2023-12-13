<?php
declare(strict_types=1);
namespace App\Controller\Backoffice\Customer;

use App\AbstractController;
use App\Repository\CustomerRepository;
use App\Repository\EquipmentRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CustomerReadController extends AbstractController
{
    public function read(Request $request, Response $response, array $arg): Response
    {
        $customer_id = (int)$arg['id'];
        $customer = $this->getRepository(CustomerRepository::class)->find('id', $customer_id);
        $equipments = $this->getRepository(EquipmentRepository::class)->allBy('customers_id', $customer_id);
        $nbEquipments = $this->getRepository(EquipmentRepository::class)->countRowWhere($customer_id, 'customers_id');
        return $this->render($response, 'backoffice/customer/read.html.twig', [
            'customer' => $customer,
            'equipments' => $equipments,
            'nbEquipments' => $nbEquipments,
            'currentMenu' => 'customer'
        ]);
    }
}