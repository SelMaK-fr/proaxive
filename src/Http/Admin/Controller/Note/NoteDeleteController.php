<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Admin\Controller\Note;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\Note\Repository\NoteRepository;
use Selmak\Proaxive2\Domain\Task\Repository\TaskRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;

class NoteDeleteController extends AbstractController
{
    public function __invoke(Request $request, Response $response): Response
    {
        if($request->getMethod() === 'DELETE'){
            $data = $request->getParsedBody();
            if($data){
                unset($data['_METHOD']);
                $this->getRepository(NoteRepository::class)->delete((int)$data['id']);
                $this->addFlash('panel-info', "Note supprimée.");
                return $this->redirectToRoute('dash_note');
            }
        }
        return $response;
    }
}