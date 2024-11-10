<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Admin\Controller\Note;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\Note\Repository\NoteRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;

class NoteController extends AbstractController
{
    public function __invoke(Request $request, Response $response): Response
    {
        $notes = $this->getRepository(NoteRepository::class)->allWitUsers();
        // Breadcrumbs
        $bds = $this->breadcrumbs;
        $bds->addCrumb('Accueil', $this->getUrlFor('dash_home'));
        $bds->addCrumb('Notes', false);
        $bds->render();
        // .Breadcrumbs
        return $this->render($response, 'backoffice/note/index.html.twig', [
            'notes' => $notes,
            'currentMenu' => 'note',
            'breadcrumbs' => $bds
        ]);
    }
}