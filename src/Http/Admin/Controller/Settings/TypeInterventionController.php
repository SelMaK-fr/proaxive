<?php
declare(strict_types=1);

namespace Selmak\Proaxive2\Http\Admin\Controller\Settings;

use Envms\FluentPDO\Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator;
use Selmak\Proaxive2\Domain\TypeEquipment\Repository\TypeEquipmentRepository;
use Selmak\Proaxive2\Domain\TypeIntervention\Repository\TypeInterventionRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Selmak\Proaxive2\Http\Type\Admin\TypeInterventionType;
use Selmak\Proaxive2\Infrastructure\Formater\TextFormatterService;

class TypeInterventionController extends AbstractController
{
    public function index(Request $request, Response $response): Response
    {
        $types = $this->getRepository(TypeInterventionRepository::class)->all();
        $form = $this->createForm(TypeInterventionType::class);
        $form->setAction($this->getUrlFor('settings_type_intervention_create'));
        $form->handleRequest();
        //
        $bds = $this->breadcrumbs;
        $bds->addCrumb('Accueil', $this->getUrlFor('dash_home'));
        $bds->addCrumb('Paramètres', false);
        $bds->addCrumb('Interventions', $this->getUrlFor('dash_intervention'));
        $bds->addCrumb('Catégories', false);
        $bds->render();
        //
        return $this->render($response, 'backoffice/settings/types_intervention/index.html.twig', [
            'currentMenu' => 'settings',
            'types' => $types,
            'setting_current' => 'types_intervention',
            'form' => $form,
            'breadcrumbs' => $bds
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws Exception
     */
    public function actionForm(Request $request, Response $response, array $args): Response
    {
        if($request->getMethod() === 'POST') {
            $id = (int)$request->getQueryParams()['id'];
            if($id != 0){
                $data = $request->getParsedBody();
            } else {
                $data = $request->getParsedBody()['form_type_intervention'];
            }
            $validator = $this->validator->validate($data, [
                'name' => [
                    'rules' => Validator::notBlank()
                ]
            ]);
            if($validator->count() === 0) {
                $checkIfExist = $this->getRepository(TypeInterventionRepository::class)->ifExist('name', $data['name']);
                if($id != 0) {
                    $this->getRepository(TypeInterventionRepository::class)->update($data, $id, false);
                } else {
                    if($checkIfExist != 1){
                        $this->getRepository(TypeInterventionRepository::class)->add($data, true);
                        $this->addFlash('panel-info', "Action effectuée avec succès.");
                    } else {
                        $this->addFlash('panel-error', "Cet élément existe déjà !");
                    }
                }

                return $this->redirectToRoute('settings_type_intervention');
            }
        }
        return new \Slim\Psr7\Response();
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws Exception
     */
    public function delete(Request $request, Response $response): Response
    {
        if($request->getMethod() === 'DELETE'){
            $data = $request->getParsedBody();
            if($data){
                unset($data['_METHOD']);
                $this->getRepository(TypeInterventionRepository::class)->delete((int)$data['id']);
                $this->addFlash('panel-info', "Type d'intervention supprimé.");
                return $this->redirectToReferer($request);
            }
        }
        return new \Slim\Psr7\Response();
    }
}