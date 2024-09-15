<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Admin\Controller\Intervention\Gallery;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\InterventionPicture\Repository\InterventionPictureRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;

class InterventionDeletePictureController extends AbstractController
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $pid = (int)$args['pid'];
        $id = (int)$args['id'];
        if($request->getMethod() === 'DELETE') {
            $picture = $this->getRepository(InterventionPictureRepository::class)->find('id', $pid);
            $directory = $this->settings->get('storage')['gallery'];
            if($picture) {
                $filename = $picture->filename;
                $delete = $this->getRepository(InterventionPictureRepository::class)->delete($pid);
                if($delete) {
                    unlink($directory.'/'.$id. '/'. $filename);
                    $this->addFlash('panel-info', 'Elément supprimé avec succès.');
                    return $this->redirectToRoute('intervention_read', ['id' => $id]);
                }
            }
        }
        return $this->redirectToRoute('intervention_read', ['id' => $id]);
    }
}