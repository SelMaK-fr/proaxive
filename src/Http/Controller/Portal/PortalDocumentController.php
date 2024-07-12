<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Controller\Portal;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\EDocument\Repository\EDocumentRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Psr7\Stream;

class PortalDocumentController extends AbstractController
{
    public function index(Request $request, Response $response): Response
    {
        $documents = $this->getRepository(EDocumentRepository::class)
            ->allBy('customers_id', (int)$this->getSession('customer')['id'])
            ->where('is_online = 1');

        return $this->render($response, 'frontoffice/portal/document/index.html.twig', [
            'documents' => $documents,
        ]);
    }

    public function read(Request $request, Response $response): Response
    {
        $id = (int)$request->getAttribute('id');
        $documents = $this->getRepository(EDocumentRepository::class)->find('customers_id', $id);

        return $this->render($response, 'frontoffice/portal/document/index.html.twig', [
            'documents' => $documents,
        ]);
    }

    /**
     * Permet de télécharger un document client
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function download(Request $request, Response $response): Response
    {
        if($request->getMethod() === 'POST') {
           $parseReference = (string)$request->getAttribute('reference');
           $d = $this->getRepository(EDocumentRepository::class)->findOneByReference($parseReference);
           if($d){
               if($d->customers_id === (int)$this->getSession('customer')['id']){
                   $directory = $this->settings->get('storage')['documents'];
                   $pathFilePdf = $directory . $d->customers_id .'/' . $d->filename;
                   $stream = fopen($pathFilePdf, 'r');
                   $response = $response
                       ->withHeader('Content-Type', 'application/pdf')
                       ->withHeader('Content-Disposition', 'attachment; filename="'.$d->filename.'"');
                   return $response->withBody(new Stream($stream));
               } else {
                   throw new HttpUnauthorizedException($request, "Vous n'avez pas le droit d'accèder à cette donnée");
               }
           } else {
               $this->addFlash('portal-error', "Cette référence de document n'existe pas.");
               return $this->redirectToRoute('portal_documents');
           }
        }
        return new \Slim\Psr7\Response();
    }
}