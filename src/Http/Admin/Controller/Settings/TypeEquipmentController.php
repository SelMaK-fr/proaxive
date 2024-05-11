<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Admin\Controller\Settings;

use Awurth\Validator\StatefulValidator;
use Envms\FluentPDO\Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator;
use Selmak\Proaxive2\Domain\TypeEquipment\Repository\TypeEquipmentRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Selmak\Proaxive2\Http\Type\Admin\TypeEquipmentType;
use Selmak\Proaxive2\Infrastructure\Formater\TextFormatterService;
use Slim\App;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class TypeEquipmentController extends AbstractController
{

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws ContainerExceptionInterface
     * @throws Exception
     * @throws NotFoundExceptionInterface
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function index(Request $request, Response $response): Response
    {
        $types = $this->getRepository(TypeEquipmentRepository::class)->all();
        $form = $this->createForm(TypeEquipmentType::class);
        $form->setAction($this->getUrlFor('settings_type_equipment_create'));
        $form->handleRequest();
        //
        $bds = $this->breadcrumbs;
        $bds->addCrumb('Accueil', $this->getUrlFor('dash_home'));
        $bds->addCrumb('Paramètres', false);
        $bds->addCrumb('Equipements', $this->getUrlFor('dash_equipment'));
        $bds->addCrumb('Catégories', false);
        $bds->render();
        //
        return $this->render($response, 'backoffice/settings/types_equipment/index.html.twig', [
            'currentMenu' => 'settings',
            'types' => $types,
            'setting_current' => 'types',
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
                        $this->addFlash('panel-info', "Action effectuée avec succès.");
                    } else {
                        $this->addFlash('panel-error', "Cet élément existe déjà !");
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
     * @throws Exception
     */
    public function delete(Request $request, Response $response): Response
    {
        if($request->getMethod() === 'DELETE'){
            $data = $request->getParsedBody();
            if($data){
                unset($data['_METHOD']);
                $this->getRepository(TypeEquipmentRepository::class)->delete((int)$data['id']);
                $this->addFlash('panel-info', "Type d'équipement supprimé.");
                return $this->redirectToReferer($request);
            }
        }
        return new \Slim\Psr7\Response();
    }
}