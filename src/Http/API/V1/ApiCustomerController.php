<?php

declare(strict_types=1);

namespace Selmak\Proaxive2\Http\API\V1;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\Customer\Repository\CustomerRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;

class ApiCustomerController extends AbstractController
{
    public function getAllCustomers(Request $request, Response $response): Response
    {
        header('Content-Type: application/json');
        $response->getBody()->write(json_encode(['']));
        return $response;
    }

    public function getCustomer(Request $request, Response $response, array $args): Response
    {
        $customerId = (int)$args['id'];
        header('Content-Type: application/json');
        $customer = $this->getRepository(CustomerRepository::class)->find('id', $customerId);
        $response->getBody()->write(json_encode($customer));
        return $response;
    }
}