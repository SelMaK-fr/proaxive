<?php

declare(strict_types=1);

namespace Selmak\Proaxive2\Http\API\V1\Statistic;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\Intervention\Repository\InterventionRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Selmak\Proaxive2\Infrastructure\Counter\CounterService;

class ApiGetIntervention extends AbstractController
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $interventions = $this->getRepository(InterventionRepository::class)->apiStats();
        // Count default
        $valueLabels = [
          1 => "not_started",
          2 => "in_workshop",
          3 => "final_test",
          4 => "exit_waiting",
          5 => "completed",
        ];
        $counter = new CounterService($valueLabels);
        foreach ($interventions as $row) {
            $counter->updateCounter($row["way_steps"], $row["count"]);
        }
        $counters = $counter->getCounters();
        return new JsonResponse($counters, 200, []);
    }
}