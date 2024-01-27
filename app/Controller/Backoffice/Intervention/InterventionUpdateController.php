<?php
declare(strict_types=1);
namespace App\Controller\Backoffice\Intervention;

use App\AbstractController;
use App\Repository\InterventionRepository;
use Envms\FluentPDO\Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class InterventionUpdateController extends AbstractController
{

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws Exception
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function update(Request $request, Response $response, array $args): Response
    {
        $intervention_id = (int)$args['id'];
        if($request->getMethod() === 'POST') {
            $data = $request->getParsedBody()['form_intervention_update'];
            $this->getRepository(InterventionRepository::class)->update($data, $intervention_id);
            $this->addFlash('panel-success', sprintf("L'intervention - %s - a bien été mise à jour", $data['name']));
            return $this->redirectToRoute('intervention_read', ['id' => $intervention_id]);
        }
        return new \Slim\Psr7\Response();
    }
}