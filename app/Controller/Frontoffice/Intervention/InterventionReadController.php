<?php
declare(strict_types=1);
namespace App\Controller\Frontoffice\Intervention;

use App\AbstractController;
use App\Repository\InterventionRepository;
use App\Repository\TaskAssocRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class InterventionReadController extends AbstractController
{

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