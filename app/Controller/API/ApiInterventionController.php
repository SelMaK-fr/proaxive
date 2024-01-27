<?php
declare(strict_types=1);
namespace App\Controller\API;

use App\AbstractController;
use App\Repository\InterventionRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ApiInterventionController extends AbstractController
{

    public function interventionStatus(Request $request, Response $response): Response
    {

        $i = $this->getRepository(InterventionRepository::class)->apiStats();

        $data = [];
        foreach ($i as $v)
        {
            $data['way_steps'] = $v['way_steps'];
            $data['count'] = $v['count'];
        }
        //dd($data);
        $result = array_count_values($data);
        header('Content-Type: application/json');
        $response->getBody()->write(json_encode($i));
        return $response;
    }

}