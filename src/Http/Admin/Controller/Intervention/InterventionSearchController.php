<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Admin\Controller\Intervention;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\Intervention\Repository\InterventionRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;

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