<?php

declare(strict_types=1);

namespace Selmak\Proaxive2\Http\API\V1\Customer;

use Selmak\Proaxive2\Domain\Customer\Repository\CustomerRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class ApiGetAll extends AbstractController
{
    public function __invoke(Request $request, Response $response): Response
    {
        $page = $request->getQueryParams()['page'];
        $perPage = $request->getQueryParams()['perPage'];
        $customers = $this->getRepository(CustomerRepository::class)->getCustomersByPage((int)$page, (int)$perPage);
        return $this->jsonResponse($response, 'success', $customers, 200);
    }
}