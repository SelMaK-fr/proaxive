<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Admin\Controller;

use Envms\FluentPDO\Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\Intervention\Repository\InterventionRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;

class IndexController extends AbstractController
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