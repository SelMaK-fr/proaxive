<?php
declare(strict_types=1);
namespace App\Controller\Backoffice\Equipment;

use App\AbstractController;
use App\Repository\EquipmentRepository;
use App\Repository\InterventionRepository;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Paginator\Paginator;

class EquipmentController extends AbstractController
{

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws \Envms\FluentPDO\Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index(Request $request, Response $response): Response
    {
        $paginator = new Paginator('15', 'p', $request);
        $paginator->set_total($this->getRepository(InterventionRepository::class)->count());
        $e = $this->getRepository(EquipmentRepository::class)->allArrayForPaginator($paginator->get_limit());
        $dataPaginate = $paginator->page_links();
        // Breadcrumbs
        $bds = $this->app->getContainer()->get('breadcrumbs');
        $bds->addCrumb('Accueil', $this->routeParser->urlFor('dash_home'));
        $bds->addCrumb('Equipements', false);
        $bds->render();
        // .Breadcrumbs
        return $this->render($response, 'backoffice/equipment/index.html.twig', [
            'equipments' => $e,
            'breadcrumbs' => $bds,
            'dataPaginate' => $dataPaginate,
            'currentMenu' => 'equipment'
        ]);
    }
}