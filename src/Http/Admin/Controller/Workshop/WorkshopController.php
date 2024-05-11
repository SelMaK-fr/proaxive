<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Admin\Controller\Workshop;

use Envms\FluentPDO\Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\Workshop\Repository\WorkshopRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class WorkshopController extends AbstractController
{

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function index(Request $request, Response $response): Response
    {
        $workshop = $this->getRepository(WorkshopRepository::class)->allWithUser();
        // Breadcrumbs
        $bds = $this->breadcrumbs;
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