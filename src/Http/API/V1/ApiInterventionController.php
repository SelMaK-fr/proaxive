<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\API\V1;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\Intervention\Repository\InterventionRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;

class ApiInterventionController extends AbstractController
{

    public function interventionStatus(Request $request, Response $response): Response
    {

        $i = $this->getRepository(InterventionRepository::class)->apiStats();
        $data = [];
        foreach ($i as $v)
        {
            if($v['way_steps'] != 5) {
                $data[] = [
                    'way_steps' => $v['way_steps'],
                    'count' => $v['count']
                ];
            }

        }
        //dd($data);
        $result = array_count_values($data);
        header('Content-Type: application/json');
        $response->getBody()->write(json_encode($data));
        return $response;
    }

}