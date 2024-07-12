<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\API\V1\Customer;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\Customer\Repository\CustomerRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;

class ApiGetStats extends AbstractController
{
    public function __invoke(Request $request, Response $response, array $args): JsonResponse
    {
        $customer_id = (int) $args['id'];
        $stats = $this->getRepository(CustomerRepository::class)->getCustomerStats($customer_id);
        $data = [
            'getCustomerId' => $stats['customer_id'],
            'getCustomerFullname' => $stats['customer_fullname'],
            'getTotalInterventions' => $stats['nbInterventions'],
            'getTotalEquipments' => $stats['nbEquipments'],
            'getTotalDocuments' => $stats['nbDocuments'],
            'getTotalOutlay' => $stats['nbOutlay'],
        ];
        return new JsonResponse($data, 200, []);
    }
}