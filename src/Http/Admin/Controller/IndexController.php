<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Admin\Controller;

use Envms\FluentPDO\Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\Intervention\Repository\InterventionRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class IndexController extends AbstractController
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
        // List all progress interventions
        $interventions = $this->getRepository(InterventionRepository::class)->allProgress();
        // List last complete interventions
        $interventions_end = $this->getRepository(InterventionRepository::class)->allCompleted(6);
        // Breadcrumbs
        $bds = $this->breadcrumbs;
        $bds->addCrumb('Accueil', false);
        $bds->render();
        // .Breadcrumbs
        return $this->render($response, 'backoffice/index.html.twig', [
            'interventions' => $interventions,
            'interventions_end' => $interventions_end,
            'currentMenu' => 'home',
            'breadcrumbs' => $bds
        ]);
    }
}