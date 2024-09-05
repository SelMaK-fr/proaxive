<?php
declare(strict_types=1);

namespace Selmak\Proaxive2\Http\Admin\Controller\Document;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\EDocument\Repository\EDocumentRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Slim\Psr7\Stream;

class DocumentReadController extends AbstractController
{
    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws \Envms\FluentPDO\Exception
     */
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $document_id = (int)$args['id'];
        $document = $this->getRepository(EDocumentRepository::class)->find('id', $document_id);
        $directory = $this->settings->get('storage')['documents'];
        $pathFilePdf = $directory . $document->customers_id .'/' . $document->filename;
        $stream = fopen($pathFilePdf, 'r');
        $response = $response
            ->withHeader('Accept', 'application/pdf, text/html, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->withHeader('Content-Disposition', 'attachment; filename="'.$document->filename.'"');
        return $response->withBody(new Stream($stream));
    }
}