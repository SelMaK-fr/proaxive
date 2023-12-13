<?php
declare(strict_types=1);
namespace App\Controller\Backoffice\Intervention;

use App\AbstractController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class InterventionUpdateController extends AbstractController
{

    public function update(Request $request, Response $response, array $args): Response
    {
        if($request->getMethod() === 'POST') {
            $data = $request->getParsedBody();
            dd($data);
        }
    }
}