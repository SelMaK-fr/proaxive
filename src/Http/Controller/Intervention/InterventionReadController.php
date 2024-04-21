<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Controller\Intervention;

use Envms\FluentPDO\Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\Intervention\Repository\InterventionRepository;
use Selmak\Proaxive2\Domain\Task\Repository\TaskAssocRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;

class InterventionReadController extends AbstractController
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
    public function read(Request $request, Response $response, array $args): Response
    {
        $ref_for_link = $args['ref_for_link'];
        $i = $this->getRepository(InterventionRepository::class)->findByString('ref_for_link', $ref_for_link);
        $t = $this->getRepository(TaskAssocRepository::class)->findByIntervention((int)$i->id);
        return $this->render($response, 'frontoffice/intervention/read.html.twig', [
            'i' => $i,
            'tasks' => $t
        ]);
    }
}