<?php
declare(strict_types=1);
namespace App\Controller\Backoffice\Workshop;

use App\AbstractController;
use App\Repository\WorkshopRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class WorkshopController extends AbstractController
{

    public function index(Request $request, Response $response): Response
    {
        $workshop = $this->getRepository(WorkshopRepository::class)->all();

        return $this->render($response, 'backoffice/workshop/index.html.twig', [
            'workshop' => $workshop,
            'currentMenu' => 'workshop'
        ]);
    }
}