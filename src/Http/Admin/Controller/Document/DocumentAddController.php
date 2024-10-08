<?php
declare(strict_types=1);

namespace Selmak\Proaxive2\Http\Admin\Controller\Document;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator as V;
use Selmak\Proaxive2\Domain\EDocument\EDocument;
use Selmak\Proaxive2\Domain\EDocument\Repository\EDocumentRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Selmak\Proaxive2\Infrastructure\Storage\UploadInterface;
use Selmak\Proaxive2\Infrastructure\Storage\UploadService;
use Slim\Exception\HttpBadRequestException;
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

        if($request->getMethod() === 'POST'){
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
            $validator = $this->validator->validate($getFile, [
                'file' => [
                    'rules' => v::objectType()->attribute('file', v::oneOf(
                        v::mimetype('application/msword'),
                        v::mimetype('application/vnd.openxmlformats-officedocument.wordprocessingml.document'),
                        v::mimetype('application/pdf'),
                        v::mimetype('application/vnd.ms-excel'),
                        v::mimetype('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'),
                        v::mimetype('text/csv'),
                        v::mimetype('application/vnd.oasis.opendocument.text')
                    ))
                ]
            ]);
            // Récupère le nom du fichier.
            $uploadedFile = $getFile['file'];
            if ($validator->count() === 0){
                if(isset($uploadedFile) && $uploadedFile instanceof UploadedFile){
                    // Vérifie si erreur
                    if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
                        $uploadService = new UploadService();
                        $filename = $uploadService->move($directory . '/' . $customer_id, $uploadedFile);
                        if(empty($data['name'])){
                            $data['name'] = $uploadedFile->getClientFilename();
                        }
                        $document = new EDocument();
                        $document->setName($data['name']);
                        $document->setFilename($filename);
                        $document->setReference(bin2hex(random_bytes(8)));
                        $document->setSize($uploadedFile->getSize());
                        $document->setExtension($uploadedFile->getClientMediaType());
                        $document->setIsOnline($isOnline);
                        $document->setCustomersId($customer_id);
                        if ($args['i'] != null) {
                            $document->setInterventionsId((int)$args['i']);
                        }
                        $this->getRepository(EDocumentRepository::class)->createOject($document);
                        $this->addFlash('panel-info', 'Document importé avec succès.');
                    } else {
                        $this->addFlash('panel-error', 'Erreur de téléversement !');
                    }
                    return $this->redirectToReferer($request);
                } else {
                    throw new HttpBadRequestException($request, 'This not a instance of UploadedFile');
                }
            } else {
                $errors = [];
                foreach ($validator as $failure) {
                    $errors[] = $failure->getMessage();
                }
                $this->addFlash('panel-error', $errors[0]);
                return $this->redirectToReferer($request);
            }

        }
        return $this->redirectToReferer($request);
    }
}