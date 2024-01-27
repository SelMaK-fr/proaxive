<?php
declare(strict_types=1);
namespace App\Controller\Backoffice\Workshop;

use App\AbstractController;
use App\Repository\WorkshopRepository;
use Envms\FluentPDO\Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class WorkshopController extends AbstractController
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
        $workshop = $this->getRepository(WorkshopRepository::class)->all();
        // Breadcrumbs
        $bds = $this->app->getContainer()->get('breadcrumbs');
        $bds->addCrumb('Accueil', $this->getUrlFor('dash_home'));
        $bds->addCrumb('Magasins', false);
        $bds->render();
        // .Breadcrumbs
        return $this->render($response, 'backoffice/workshop/index.html.twig', [
            'workshop' => $workshop,
            'breadcrumbs' => $bds,
            'currentMenu' => 'workshop'
        ]);
    }
}