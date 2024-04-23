<?php

declare(strict_types=1);

namespace Selmak\Proaxive2\Http\API\V1\Statistic;

use Laminas\Diactoros\Response\JsonResponse;
use Selmak\Proaxive2\Domain\Customer\Repository\CustomerRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class ApiGetAll extends AbstractController
{
    public function __invoke(Request $request, Response $response): Response
    {

    }
}