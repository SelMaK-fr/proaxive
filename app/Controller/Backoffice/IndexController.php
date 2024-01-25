<?php
declare(strict_types=1);
namespace App\Controller\Backoffice;

use App\AbstractController;
use App\Repository\CustomerRepository;
use App\Repository\InterventionRepository;
use App\Service\GeneratePdfService;
use Knp\Snappy\Pdf;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class IndexController extends AbstractController
{

    public function index(Request $request, Response $response): Response
    {
        $interventions = $this->getRepository(InterventionRepository::class)->all()->limit(10)->orderBy('created_at DESC');
        // Breadcrumbs
        $bds = $this->app->getContainer()->get('breadcrumbs');
        $bds->addCrumb('Proaxive Dashboard', false);
        $bds->addCrumb('Accueil', false);
        $bds->render();
        // .Breadcrumbs
        return $this->render($response, 'backoffice/index.html.twig', [
            'interventions' => $interventions,
            'currentMenu' => 'home',
            'breadcrumbs' => $bds
        ]);
    }
}