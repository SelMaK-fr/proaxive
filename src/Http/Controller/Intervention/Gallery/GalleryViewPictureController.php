<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Controller\Intervention\Gallery;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\InterventionPicture\Repository\InterventionPictureRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;

class GalleryViewPictureController extends AbstractController
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $picture_id = (int)$args['pid'];
        $picture = $this->getRepository(InterventionPictureRepository::class)->find('id', $picture_id);
        $directory = $this->settings->get('storage')['gallery'] . $picture->interventions_id;
        $filePath = $directory . '/' . $picture->filename;
        if (!file_exists($filePath)) {
            $response->getBody()->write('Image not found.');
            return $response->withStatus(404);
        }
        $mimeType = mime_content_type($filePath);
        // Récupérer le contenu du fichier et le renvoyer
        $response->getBody()->write(file_get_contents($filePath));
        return $response->withHeader('Content-Type', $mimeType);
    }
}