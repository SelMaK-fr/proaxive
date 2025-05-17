<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Admin\Controller\Note;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\Note\Note;
use Selmak\Proaxive2\Domain\Note\Repository\NoteRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Selmak\Proaxive2\Http\Type\Admin\NoteType;

class NoteUpdateController extends AbstractController
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $note = $this->getRepository(NoteRepository::class)->find('id', (int)$args['id']);
        $form = $this->createForm(NoteType::class, $note);
        $form->handleRequest();
        if($form->isSubmitted() && $form->isValid()) {
            $data = $form->getRequestData()['form_note'];
            $this->getRepository(NoteRepository::class)->update($data, (int)$args['id'], false);
            $this->addFlash('panel-info', 'Note éditée avec succès.');
            return $this->redirectToRoute('dash_note');
        }
        // Breadcrumbs
        $bds = $this->breadcrumbs;
        $bds->addCrumb('Accueil', $this->getUrlFor('dash_home'));
        $bds->addCrumb('Notes', false);
        $bds->addCrumb($this->sanitize($note->title), false);
        $bds->render();
        // .Breadcrumbs
        return $this->render($response, "backoffice/note/create.html.twig", [
            'form' => $form,
            'breadcrumbs' => $bds,
            'currentMenu' => 'note'
        ]);
    }
}