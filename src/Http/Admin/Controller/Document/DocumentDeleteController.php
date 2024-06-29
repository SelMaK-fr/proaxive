<?php
declare(strict_types=1);

namespace Selmak\Proaxive2\Http\Admin\Controller\Document;

use Laminas\Diactoros\Response\RedirectResponse;
use Selmak\Proaxive2\Domain\EDocument\Repository\EDocumentRepository;
use Selmak\Proaxive2\Http\Admin\Controller\CrudController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class DocumentDeleteController extends CrudController
{

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        if($request->getMethod() === 'DELETE') {
            $id = (int)$args['id'];
            $document = $this->getRepository(EDocumentRepository::class)->find('id', $id);
            $directory = $this->settings->get('storage')['documents'];
            if($document) {
                $filename = $document->filename;
                $customer_id = $document->customers_id;
                $delete = $this->getRepository(EDocumentRepository::class)->delete($id);
                if($delete) {
                    unlink($directory.'/'.$customer_id. '/'. $filename);
                    $this->addFlash('panel-info', 'Elément supprimé avec succès.');
                    return $this->redirectToRoute('dash_documents');
                }
            }
        }
        return $this->redirectToRoute('dash_documents');
    }
}