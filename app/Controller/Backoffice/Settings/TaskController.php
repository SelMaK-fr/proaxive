<?php
declare(strict_types=1);
namespace App\Controller\Backoffice\Settings;

use App\AbstractController;
use App\Repository\TaskRepository;
use App\Type\TaskType;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator;

class TaskController extends AbstractController
{

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws \Envms\FluentPDO\Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index(Request $request, Response $response): Response
    {
        $tasks = $this->getRepository(TaskRepository::class)->all();

        // For create
        $form = $this->createForm(TaskType::class);
        $form->setAction($this->routeParser->urlFor('settings_task_create'));
        $form->handleRequest();
        //
        $breadcrumbs = $this->app->getContainer()->get('breadcrumbs');
        $breadcrumbs->addCrumb('Accueil', $this->routeParser->urlFor('dash_home'));
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
                if($id != 0){
                    $this->getRepository(TaskRepository::class)->update($data, $id);
                } else {
                    $this->getRepository(TaskRepository::class)->add($data);
                }
                $this->session->getFlash()->add('panel-info', "Action effectuée avec succès");
                return $response->withStatus(302)->withHeader('Location', $request->getServerParams()['HTTP_REFERER']);
            }
        }

        return $response;
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
                $this->getRepository(TaskRepository::class)->delete((int)$data['id']);
                $this->session->getFlash()->add('panel-info', "Tâche supprimée.");
                return $response->withStatus(302)->withHeader('Location', $this->routeParser->urlFor('settings_task'));
            }
        }
    }
}