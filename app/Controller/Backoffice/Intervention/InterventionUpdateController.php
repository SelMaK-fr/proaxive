<?php
declare(strict_types=1);
namespace App\Controller\Backoffice\Intervention;

use App\AbstractController;
use App\Repository\InterventionRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class InterventionUpdateController extends AbstractController
{

    public function update(Request $request, Response $response, array $args): Response
    {
        $intervention_id = (int)$args['id'];
        if($request->getMethod() === 'POST') {
            $data = $request->getParsedBody()['form_intervention_update'];
            $this->getRepository(InterventionRepository::class)->update($data, $intervention_id);
            $this->session->getFlash()->add('panel-info', sprintf("L'intervention - %s - a bien été mise à jour", $data['name']));
            return $response->withStatus(302)->withHeader('Location', $this->routeParser->urlFor('intervention_read', ['id' => $intervention_id]));
        }
        return new \Slim\Psr7\Response();
    }
}