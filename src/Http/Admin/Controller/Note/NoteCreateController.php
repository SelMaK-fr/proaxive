<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Admin\Controller\Note;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\Note\Note;
use Selmak\Proaxive2\Domain\Note\Repository\NoteRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Selmak\Proaxive2\Http\Type\Admin\NoteType;

class NoteCreateController extends AbstractController
{
    public function __invoke(Request $request, Response $response): Response
    {
        $note = new Note();
        $form = $this->createForm(NoteType::class, $note);
        $form->handleRequest();
        if($form->isSubmitted() && $form->isValid()) {
            $data = $form->getRequestData()['form_note'];
            $note->setCompanyId($this->getUser()->getCompanyId());
            $note->setUsersId($this->getUser()->getId());
            $note->setStick((int)$data['stick']);

            $this->getRepository(NoteRepository::class)->add($note, true);
            $this->addFlash('panel-info', 'Note créée avec succès.');
            return $this->redirectToRoute('dash_note');
        }
        // Breadcrumbs
        $bds = $this->breadcrumbs;
        $bds->addCrumb('Accueil', $this->getUrlFor('dash_home'));
        $bds->addCrumb('Notes', false);
        $bds->addCrumb('Création', false);
        $bds->render();
        // .Breadcrumbs
        return $this->render($response, "backoffice/note/create.html.twig", [
            'form' => $form,
            'breadcrumbs' => $bds,
            'currentMenu' => 'note'
        ]);
    }
}