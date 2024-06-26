<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Admin\Controller\Intervention;

use Envms\FluentPDO\Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\Intervention\Repository\InterventionRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;

class InterventionUpdateController extends AbstractController
{

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws Exception
     */
    public function update(Request $request, Response $response, array $args): Response
    {
        $intervention_id = (int)$args['id'];
        if($request->getMethod() === 'POST') {
            $data = array_values($request->getParsedBody())[0];
            $this->getRepository(InterventionRepository::class)->update($data, $intervention_id);
            $this->addFlash('panel-success', sprintf("L'intervention - %s - a bien été mise à jour", $data['name']));
            return $this->redirectToRoute('intervention_read', ['id' => $intervention_id]);
        }
        return new \Slim\Psr7\Response();
    }
}