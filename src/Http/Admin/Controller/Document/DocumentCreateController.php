<?php
declare(strict_types=1);

namespace Selmak\Proaxive2\Http\Admin\Controller\Document;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\EDocument\EDocument;
use Selmak\Proaxive2\Domain\EDocument\Repository\EDocumentRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Slim\Psr7\UploadedFile;

class DocumentCreateController extends AbstractController
{
    public function __invoke(Request $request, Response $response): Response
    {
        // Récupère les données du formulaire
        $data = $request->getParsedBody()['form_document'];
        $isOnline = $data['is_online'] ? 1 : 0;
        // récupère les éléments passés dans l'URL
        $args = $request->getQueryParams();
        // PATH du stockage "Documents"
        $directory = $this->settings->get('storage')['documents'];

        $getFile = $request->getUploadedFiles()['form_document'];
        // Récupère le nom du fichier.
        $uploadedFile = $getFile['file'];
        // Vérifie si erreur
        if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
            $filename = $this->moveUploadedFile($directory . '/' . $args['c'], $uploadedFile);
        }
        $document = new EDocument();
        $document->setName($data['name']);
        $document->setFilename($filename);
        $document->setReference(bin2hex(random_bytes(8)));
        $document->setSize($uploadedFile->getSize());
        $document->setExtension($uploadedFile->getClientMediaType());
        $document->setIsOnline($isOnline);
        $document->setCustomersId((int)$args['c']);
        $document->setInterventionsId((int)$args['i']);

        $this->getRepository(EDocumentRepository::class)->createOject($document);
        dd($document);
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