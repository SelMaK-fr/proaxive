<?php
declare(strict_types=1);
namespace App\Controller\Frontoffice\Portal;

use App\AbstractController;
use App\Repository\EquipmentRepository;
use App\Repository\InterventionRepository;
use App\Repository\TaskAssocRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PortalInterventionController extends AbstractController
{

    public function index(Request $request, Response $response): Response
    {

        $i = $this->getRepository(InterventionRepository::class)->allBy('customers_id', $this->session->get('customer')['id']);

        return $this->render($response, '/frontoffice/portal/intervention/index.html.twig', [
            'i' => $i
        ]);
    }

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