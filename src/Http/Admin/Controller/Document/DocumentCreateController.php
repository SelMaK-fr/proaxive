<?php
declare(strict_types=1);

namespace Selmak\Proaxive2\Http\Admin\Controller\Document;

use Selmak\Proaxive2\Domain\EDocument\EDocument;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Http\Type\Admin\Document\DocumentCreateType;

class DocumentCreateController extends AbstractController
{
    public function __invoke(Request $request, Response $response): Response
    {

        $form = $this->createForm(DocumentCreateType::class);
        $form->setAction($this->getUrlFor('document_add'));
        $form->handleRequest();

        // Breadcrumbs
        $bds = $this->breadcrumbs;
        $bds->addCrumb('Accueil', $this->getUrlFor('dash_home'));
        $bds->addCrumb('Documents', $this->getUrlFor('dash_documents'));
        $bds->addCrumb('Création', false);
        $bds->render();
        // .Breadcrumbs

        return $this->render($response, 'backoffice/document/create.html.twig', [
            'breadcrumbs' => $bds,
            'form' => $form
        ]);
    }

}