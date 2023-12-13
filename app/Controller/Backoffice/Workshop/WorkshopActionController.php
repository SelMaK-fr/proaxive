<?php
declare(strict_types=1);
namespace App\Controller\Backoffice\Workshop;

use App\AbstractController;
use App\Repository\WorkshopRepository;
use App\Type\WorkshopType;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class WorkshopActionController extends AbstractController
{

    public function action(Request $request, Response $response, array $args): Response
    {
        $workshop_id = (int)$args['id'];
        if($workshop_id) {
            $workshop = $this->getRepository(WorkshopRepository::class)->find('id', $workshop_id);
            $form = $this->createForm(WorkshopType::class, $workshop);
        } else {
            $form = $this->createForm(WorkshopType::class);
        }
        $form->handleRequest();
        if($form->isSubmitted() && $form->isValid()) {
            $data = $form->getRequestData()['form_workshop'];
            if($workshop_id) {
                $saveUpdate = $this->getRepository(WorkshopRepository::class)->update($data, $workshop_id);
                if($saveUpdate) {
                    $this->session->getFlash()->add('panel-info', sprintf('Mise à jour pour - %s - effectuée', $data['name']));
                }
            } else {
                $save = $this->getRepository(WorkshopRepository::class)->add($data, true);
                if($save) {
                    $this->session->getFlash()->add('panel-info', sprintf("Le nouveau magasin/atelier - %s - a bien été créé", $data['name']));
                }
            }
        }
        return $this->render($response, 'backoffice/workshop/action.html.twig', [
            'form' => $form,
            'currentMenu' => 'workshop'
        ]);
    }
}