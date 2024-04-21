<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Controller\Portal;

use Envms\FluentPDO\Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\Equipment\Repository\EquipmentRepository;
use Selmak\Proaxive2\Domain\Intervention\Repository\InterventionRepository;
use Selmak\Proaxive2\Domain\Task\Repository\TaskAssocRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;

class PortalInterventionController extends AbstractController
{

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws Exception
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function index(Request $request, Response $response): Response
    {

        $i = $this->getRepository(InterventionRepository::class)->allBy('customers_id', $this->getSession('customer')['id']);

        return $this->render($response, '/frontoffice/portal/intervention/index.html.twig', [
            'i' => $i
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws ContainerExceptionInterface
     * @throws Exception
     * @throws NotFoundExceptionInterface
     */
    public function read(Request $request, Response $response, array $args): Response
    {
        $ref_number = (string)$args['ref_number'];
        $i = $this->getRepository(InterventionRepository::class)->joinForIdWithKey('ref_for_link', $ref_number);
        $e = $this->getRepository(EquipmentRepository::class)->findWithBrand($i->equipments_id);
        $tasksForI = $this->getRepository(TaskAssocRepository::class)->findByIntervention((int)$i->i_id);

        return $this->render($response, '/frontoffice/portal/intervention/read.html.twig', [
            'i' => $i,
            'e' => $e,
            'tasks' => $tasksForI
        ]);
    }
}