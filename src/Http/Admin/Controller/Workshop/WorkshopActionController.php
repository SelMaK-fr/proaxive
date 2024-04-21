<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Admin\Controller\Workshop;

use Envms\FluentPDO\Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\Workshop\Repository\WorkshopRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Selmak\Proaxive2\Http\Type\WorkshopType;

class WorkshopActionController extends AbstractController
{

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws Exception
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
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
                    $this->addFlash('panel-info', sprintf('Mise à jour pour - %s - effectuée', $data['name']));
                }
            } else {
                $save = $this->getRepository(WorkshopRepository::class)->add($data, true);
                if($save) {
                    $this->addFlash('panel-info', sprintf("Le nouveau magasin/atelier - %s - a bien été créé", $data['name']));
                }
            }
        }
        // Breadcrumbs
        $bds = $this->app->getContainer()->get('breadcrumbs');
        $bds->addCrumb('Accueil', $this->getUrlFor('dash_home'));
        $bds->addCrumb('Magasins', $this->getUrlFor('dash_workshop'));
        $bds->addCrumb('Actions', false);
        $bds->render();
        // .Breadcrumbs
        return $this->render($response, 'backoffice/workshop/action.html.twig', [
            'form' => $form,
            'breadcrumbs' => $bds,
            'currentMenu' => 'workshop'
        ]);
    }
}