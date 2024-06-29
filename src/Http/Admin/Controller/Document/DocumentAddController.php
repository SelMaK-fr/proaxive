<?php
declare(strict_types=1);

namespace Selmak\Proaxive2\Http\Admin\Controller\Document;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\EDocument\EDocument;
use Selmak\Proaxive2\Domain\EDocument\Repository\EDocumentRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Slim\Psr7\UploadedFile;

class DocumentAddController extends AbstractController
{
    /**
     * Create document for Intervention (modal)
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws \Random\RandomException
     */
    public function __invoke(Request $request, Response $response): Response
    {
        // Récupère les données du formulaire
        $data = $request->getParsedBody()['form_document'];

        $isOnline = $data['is_online'] ? 1 : 0;
        // récupère les éléments passés dans l'URL
        $args = $request->getQueryParams();
        // Vérifie si des argument sont passés en parametre
        if (!empty($args)) {
            $customer_id = (int)$args['c'];
        } else {
            $customer_id = (int)$data['customer_id'];
        }
        // PATH du stockage "Documents"
        $directory = $this->settings->get('storage')['documents'];

        $getFile = $request->getUploadedFiles()['form_document'];
        // Récupère le nom du fichier.
        $uploadedFile = $getFile['file'];
        // Vérifie si erreur
        if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
            $filename = $this->moveUploadedFile($directory . '/' . $customer_id, $uploadedFile);
        }

        $document = new EDocument();
        $document->setName($data['name']);
        $document->setFilename($filename);
        $document->setReference(bin2hex(random_bytes(8)));
        $document->setSize($uploadedFile->getSize());
        $document->setExtension($uploadedFile->getClientMediaType());
        $document->setIsOnline($isOnline);
        $document->setCustomersId($customer_id);
        if ($args) {
            $document->setInterventionsId((int)$args['i']);
        }
        $this->getRepository(EDocumentRepository::class)->createOject($document);
        $this->addFlash('panel-info', 'Document importé avec succès.');
        return $this->redirectToReferer($request);
    }

    private function moveUploadedFile($directory, UploadedFile $uploadedFile): string
    {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        $basename = bin2hex(random_bytes(8));
        $filename = sprintf('%s.%0.8s', $basename, $extension);
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }
        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

        return $filename;
    }
}