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
use Selmak\Proaxive2\Domain\InterventionPicture\Repository\InterventionPictureRepository;
use Selmak\Proaxive2\Domain\Task\Repository\TaskAssocRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class PortalInterventionController extends AbstractController
{

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws Exception
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function index(Request $request, Response $response): Response
    {

        $page = $request->getQueryParams()['p'];
        $i = $this->getRepository(InterventionRepository::class)->findByCustomerWithPaginate(15, (int)$page ?: 1, ['customers_id' => $this->getSession('customer')['id']]);

        return $this->render($response, '/frontoffice/portal/intervention/index.html.twig', [
            'i' => $i
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws Exception
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function read(Request $request, Response $response, array $args): Response
    {
        $ref_number = (string)$args['ref_number'];
        $i = $this->getRepository(InterventionRepository::class)->joinForIdWithKey($ref_number);
        $e = $this->getRepository(EquipmentRepository::class)->findWithBrand($i->equipments_id);
        $tasksForI = $this->getRepository(TaskAssocRepository::class)->findByIntervention((int)$i->i_id);
        $pictures = $this->getRepository(InterventionPictureRepository::class)->allBy('interventions_id', (int)$i->i_id);

        return $this->render($response, '/frontoffice/portal/intervention/read.html.twig', [
            'i' => $i,
            'e' => $e,
            'pictures' => $pictures,
            'tasks' => $tasksForI
        ]);
    }
}