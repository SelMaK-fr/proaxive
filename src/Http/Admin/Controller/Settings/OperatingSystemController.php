<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Admin\Controller\Settings;

use Awurth\Validator\StatefulValidator;
use Envms\FluentPDO\Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator as v;
use Selmak\Proaxive2\Domain\OperatingSystem\Repository\OperatingSystemRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Selmak\Proaxive2\Http\Type\Admin\OperatingSystemType;
use Slim\App;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class OperatingSystemController extends AbstractController
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
        $operatingSystem = $this->getRepository(OperatingSystemRepository::class)->all();
        $form = $this->createForm(OperatingSystemType::class);
        $form->setAction($this->getUrlFor('settings_os_create'));
        $form->handleRequest();
        //
        $breadcrumbs = $this->breadcrumbs;
        $breadcrumbs->addCrumb('Accueil', $this->getUrlFor('dash_home'));
        $breadcrumbs->addCrumb('Paramètres', false);
        $breadcrumbs->addCrumb("Systèmes d'exploitation", false);
        $breadcrumbs->render();
        //
        return $this->render($response, 'backoffice/settings/operating_system/index.html.twig', [
            'currentMenu' => 'settings',
            'operatingSystem' => $operatingSystem,
            'form' => $form,
            'setting_current' => 'operatingSystem',
            'breadcrumbs' => $breadcrumbs
        ]);
    }

    public function actionForm(Request $request, Response $response, array $args): Response
    {
        if($request->getMethod() === 'POST') {
            $id = (int)$request->getQueryParams()['id'];
            if($id != 0) {
                $data = $request->getParsedBody();
            } else {
                $data = $request->getParsedBody()['form_operating_system'];
            }
            $validator = $this->validator->validate($data, [
                'os_name' => [
                    'rules' => v::length(3, 80),
                    'message' => 'La clé "Nom du système" doit être comprise entre 3 et 80 caractère'
                ]
            ]);
            if($validator->count() === 0) {
                $data['os_full'] = $data['os_name'].' - '.$data['os_architecture'].' ('. $data['os_release'] .')';
                if($id != 0) {
                    $this->getRepository(OperatingSystemRepository::class)->update($data, $id);
                    $this->addFlash('panel-info', "Action effectuée avec succès.");
                } else {
                    $this->getRepository(OperatingSystemRepository::class)->add($data);
                    $this->addFlash('panel-info', sprintf('Le système (%s) a bien été créé.', $data['os_full']));
                }
                return $this->redirectToRoute('settings_os');
            } else {
                foreach ($validator as $v) {
                    $this->session->getFlash()->add('panel-error', sprintf('%s', $v->getMessage()));
                }
                return $this->redirectToReferer($request);
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
                $this->getRepository(OperatingSystemRepository::class)->delete((int)$data['id']);
                $this->session->getFlash()->add('panel-info', "OS supprimé.");
                return $this->redirectToRoute('settings_os');
            }
        }
        return new \Slim\Psr7\Response();
    }
}