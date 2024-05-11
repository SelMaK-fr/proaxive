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
use Selmak\Proaxive2\Domain\Task\Repository\TaskRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Selmak\Proaxive2\Http\Type\Admin\TaskType;
use Slim\App;

class TaskController extends AbstractController
{

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws Exception
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index(Request $request, Response $response): Response
    {
        $tasks = $this->getRepository(TaskRepository::class)->all();

        // For create
        $form = $this->createForm(TaskType::class);
        $form->setAction($this->getUrlFor('settings_task_create'));
        $form->handleRequest();
        //
        $breadcrumbs = $this->breadcrumbs;
        $breadcrumbs->addCrumb('Accueil', $this->getUrlFor('dash_home'));
        $breadcrumbs->addCrumb('Paramètres', false);
        $breadcrumbs->addCrumb("Tâches", false);
        $breadcrumbs->render();
        //

        return $this->render($response, 'backoffice/settings/tasks/index.html.twig', [
            'currentMenu' => 'settings',
            'tasks' => $tasks,
            'setting_current' => 'tasks',
            'form' => $form,
            'breadcrumbs' => $breadcrumbs
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
                $data = $request->getParsedBody()['form_setting_task'];
            }
            $validator = $this->validator->validate($data, [
                'name' => [
                    'rules' => Validator::notBlank()
                ]
            ]);
            if($validator->count() === 0) {
                $checkIfExist = $this->getRepository(TaskRepository::class)->ifExist('name', $data['name']);
                if($id != 0){
                    $this->getRepository(TaskRepository::class)->update($data, $id);
                } else {
                    if($checkIfExist === 0) {
                        $this->getRepository(TaskRepository::class)->add($data);
                        $this->addFlash('panel-info', "Action effectuée avec succès");
                    } else {
                        $this->addFlash('panel-error', "Cet élément existe déjà !");
                    }
                }
                return $this->redirectToReferer($request);
            }
        }

        return $response;
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
                $this->getRepository(TaskRepository::class)->delete((int)$data['id']);
                $this->addFlash('panel-info', "Tâche supprimée.");
                return $this->redirectToRoute('settings_task');
            }
        }
    }
}