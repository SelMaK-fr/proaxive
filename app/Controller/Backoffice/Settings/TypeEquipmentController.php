<?php
declare(strict_types=1);
namespace App\Controller\Backoffice\Settings;

use App\AbstractController;
use App\Repository\BrandRepository;
use App\Repository\TypeEquipmentRepository;
use App\Type\TypeEquipmentType;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator;
use Selmak\Proaxive2\Service\TextFormatterService;

class TypeEquipmentController extends AbstractController
{

    public function index(Request $request, Response $response): Response
    {
        $types = $this->getRepository(TypeEquipmentRepository::class)->all();
        $form = $this->createForm(TypeEquipmentType::class);
        $form->setAction($this->routeParser->urlFor('settings_type_equipment_create'));
        $form->handleRequest();
        //
        $breadcrumbs = $this->app->getContainer()->get('breadcrumbs');
        $breadcrumbs->addCrumb('Accueil', $this->routeParser->urlFor('dash_home'));
        $breadcrumbs->addCrumb('Equipements', $this->routeParser->urlFor('dash_equipment'));
        $breadcrumbs->addCrumb('Catégories', false);
        $breadcrumbs->render();
        //
        return $this->render($response, 'backoffice/settings/types_equipment/index.html.twig', [
            'currentMenu' => 'settings',
            'types' => $types,
            'setting_current' => 'types',
            'form' => $form,
            'breadcrumbs' => $breadcrumbs
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws \Envms\FluentPDO\Exception
     */
    public function actionForm(Request $request, Response $response, array $args): Response
    {
        if($request->getMethod() === 'POST') {
            $id = (int)$request->getQueryParams()['id'];
            if($id != 0){
                $data = $request->getParsedBody();
            } else {
                $data = $request->getParsedBody()['form_type_equipment'];
            }
            $validator = $this->validator->validate($data, [
                'name' => [
                    'rules' => Validator::notBlank()
                ]
            ]);
            if($validator->count() === 0) {
                $textFormatter = new TextFormatterService();
                if(empty($data['slug'])) {
                    $data['slug'] = $textFormatter->cleanText($data['name']);
                }
                $data['is_peripheral'] = $data['is_peripheral'] ?? null;
                $checkIfExist = $this->getRepository(TypeEquipmentRepository::class)->ifExist('name', $data['name']);
                if($id != 0) {
                    $this->getRepository(TypeEquipmentRepository::class)->update($data, $id);
                } else {
                    if($checkIfExist != 1){
                        $this->getRepository(TypeEquipmentRepository::class)->add($data);
                        $this->session->getFlash()->add('panel-info', "Action effectuée avec succès.");
                    } else {
                        $this->session->getFlash()->add('panel-error', "Cet élément existe déjà !");
                    }
                }

                return $this->redirectToRoute('settings_type_equipment');
            }
        }
        return new \Slim\Psr7\Response();
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws \Envms\FluentPDO\Exception
     */
    public function delete(Request $request, Response $response): Response
    {
        if($request->getMethod() === 'DELETE'){
            $data = $request->getParsedBody();
            if($data){
                unset($data['_METHOD']);
                $this->getRepository(TypeEquipmentRepository::class)->delete((int)$data['id']);
                $this->session->getFlash()->add('panel-info', "Type d'équipement supprimé.");
                return $this->redirectToReferer($request);
            }
        }
        return new \Slim\Psr7\Response();
    }
}