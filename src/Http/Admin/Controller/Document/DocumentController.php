<?php
declare(strict_types=1);

namespace Selmak\Proaxive2\Http\Admin\Controller\Document;

use Selmak\Proaxive2\Domain\EDocument\Repository\EDocumentRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class DocumentController extends AbstractController
{
    public function __invoke(Request $request, Response $response): Response
    {
        $documents = $this->getRepository(EDocumentRepository::class)->allWithCustomerAndIntervention();
        // Breadcrumbs
        $bds = $this->breadcrumbs;
        $bds->addCrumb('Accueil', $this->getUrlFor('dash_home'));
        $bds->addCrumb('Documents', false);
        $bds->render();
        // .Breadcrumbs

        return $this->render($response, 'backoffice/document/index.html.twig', [
            'breadcrumbs' => $bds,
            'documents' => $documents,
        ]);
    }
}