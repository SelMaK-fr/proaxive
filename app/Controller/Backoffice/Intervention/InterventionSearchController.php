<?php
declare(strict_types=1);
namespace App\Controller\Backoffice\Intervention;

use App\AbstractController;
use App\Repository\InterventionRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class InterventionSearchController extends AbstractController
{


    public function searchByFields(Request $request, Response $response, array $args): Response
    {
        if($request->getMethod() === 'GET') {
            $data = $request->getQueryParams()['form_intervention_search'];
            $req = $this->getRepository(InterventionRepository::class)->searchByFields($data);
        }
    }
}