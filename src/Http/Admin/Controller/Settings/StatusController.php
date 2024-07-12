<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Admin\Controller\Settings;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\Status\Repository\StatusRepository;
use Selmak\Proaxive2\Domain\Status\Status;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Selmak\Proaxive2\Http\Type\Admin\StatusType;

class StatusController extends AbstractController
{
    protected string $repository = StatusRepository::class;
    protected string $menuItem = 'settings';

    public function index(Request $request, Response $response): Response
    {
        $status = $this->getRepository($this->repository)->all();
        $form = $this->createForm(StatusType::class);
        $form->setAction($this->getUrlFor('settings_status_create'));
        $form->handleRequest();
        //
        $breadcrumbs = $this->breadcrumbs;
        $breadcrumbs->addCrumb('Accueil', $this->getUrlFor('dash_home'));
        $breadcrumbs->addCrumb('Paramètres', false);
        $breadcrumbs->addCrumb("Statut", false);
        $breadcrumbs->render();
        //
        return $this->render($response, 'backoffice/settings/status/index.html.twig', [
            'currentMenu' => $this->menuItem,
            'status' => $status,
            'form' => $form,
            'setting_current' => 'status',
            'breadcrumbs' => $breadcrumbs
        ]);
    }

    public function actionForm(Request $request, Response $response, array $args): Response
    {
        if($request->getMethod() === 'POST') {
            $id = (int)$request->getQueryParams()['id'];
            $req = $request->getParsedBody();
            if($id != 0) {
                // for update
                $data = new Status($req);
                $data->setId($id);
            } else {
                // create
                $data = new Status($req['form_status']);

            }
            $checkIfExist = $this->getRepository($this->repository)->ifExist('name', $data->getName());
            if($id != 0) {
                $this->getRepository($this->repository)->update($data, $id, false);
                $this->addFlash('panel-info', "Mise à jour effectuée.");
            } else {
                if($checkIfExist != 1){
                    $this->getRepository($this->repository)->add($data);
                    $this->addFlash('panel-info', "Action effectuée avec succès.");
                } else {
                    $this->addFlash('panel-error', "Cet élément existe déjà !");
                }
            }
            return $this->redirectToRoute('settings_status');
        }
        return new \Slim\Psr7\Response();
    }
}