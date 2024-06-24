<?php
declare(strict_types=1);

namespace Selmak\Proaxive2\Http\Admin\Controller\Outlay;

use Selmak\Proaxive2\Domain\Outlay\Repository\OutlayRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class OutlayController extends AbstractController
{
    public function __invoke(Request $request, Response $response): Response
    {
        $outlay = $this->getRepository(OutlayRepository::class)->allWithCustomer();
        // Breadcrumbs
        $bds = $this->breadcrumbs;
        $bds->addCrumb('Accueil', $this->getUrlFor('dash_home'));
        $bds->addCrumb('Débours', false);
        $bds->render();
        // .Breadcrumbs

        return $this->render($response, 'backoffice/outlay/index.html.twig', [
            'outlay' => $outlay,
            'breadcrumbs' => $bds,
            'currentMenu' => 'outlay'
        ]);
    }
}