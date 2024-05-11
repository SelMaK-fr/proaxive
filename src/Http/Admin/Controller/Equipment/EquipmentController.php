<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Admin\Controller\Equipment;

use Envms\FluentPDO\Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\Equipment\Repository\EquipmentRepository;
use Selmak\Proaxive2\Domain\Intervention\Repository\InterventionRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Selmak\Proaxive2\Infrastructure\Paginator\Paginator;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class EquipmentController extends AbstractController
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
        $paginator = new Paginator('15', 'p', $request);
        $paginator->set_total($this->getRepository(EquipmentRepository::class)->count());
        $e = $this->getRepository(EquipmentRepository::class)->allArrayForPaginator($paginator->get_limit());
        $dataPaginate = $paginator->page_links();
        // Breadcrumbs
        $bds = $this->breadcrumbs;
        $bds->addCrumb('Accueil', $this->getUrlFor('dash_home'));
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