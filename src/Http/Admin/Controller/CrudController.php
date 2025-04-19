<?php

declare(strict_types=1);

namespace Selmak\Proaxive2\Http\Admin\Controller;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class CrudController extends AbstractController
{
    protected string $entity = '';

    protected string $repository = '';
    protected string $form_name = '';
    protected string $routeName = '';
    protected string $template_path = '';
    protected string $menuItem = '';

    /**
     * @param string $type
     * @param mixed $data
     * @param int $id
     * @param $response
     * @param $request
     * @param mixed $breadcrumbs
     * @return Response
     * @throws ContainerExceptionInterface
     * @throws LoaderError
     * @throws NotFoundExceptionInterface
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function crudUpdate(string $type, mixed $data, int $id, $response, $request, mixed $breadcrumbs)
    {
        $form = $this->createForm($type, $data);
        $form->handleRequest();
        if($form->isSubmitted() && $form->isValid()) {
            $data = $form->getRequestData()['form_' .$this->form_name];
            $this->getRepository($this->repository)->update($data, $id);
            $this->addFlash('panel-info', 'Mises à jour effectuée avec succès.');
            return $this->redirectToReferer($request);
        }
        return $this->render($response, "backoffice/{$this->template_path}/update.html.twig", [
            'form' => $form,
            'data' => $data,
            'breadcrumbs' => $breadcrumbs,
            'currentMenu' => 'equipment'
        ]);
    }

    /**
     * @throws SyntaxError
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function crudCreate(string $type, $response, mixed $breadcrumbs, ?array $fields = [])
    {
        $entity = (new $this->entity);
        $form = $this->createForm($type, $entity);
        $form->handleRequest();
        if($form->isSubmitted() && $form->isValid()) {
            if(is_array($fields) && array_key_exists('users', $fields)) {
                $entity->setUsersId($this->getUser()->getId());
                if(array_key_exists('company', $fields)){
                $entity->setCompanyId($this->getUser()->getCompanyId());
                }
            }

            $this->getRepository($this->repository)->createOject($entity, true);
            $this->addFlash('panel-info', 'Opération réalisée avec succès.');
            return $this->redirectAfterSave();
        }
        return $this->render($response, "backoffice/{$this->template_path}/create.html.twig", [
            'form' => $form,
            'breadcrumbs' => $breadcrumbs,
            'currentMenu' => $this->menuItem
        ]);
    }

    /**
     * @param string $repository
     * @param $response
     * @param $request
     * @param array $args
     * @return Response
     */
    public function crudDelete(string $repository, $response, $request, array $args)
    {
        $id = (int)$args['id'];
        if($request->getMethod() === 'DELETE') {
            $this->getRepository($repository)->delete($id);
            $this->addFlash('panel-info', 'Elément supprimé avec succès.');
            return $this->redirectAfterSave();
        }
        return $response;
    }

    protected function redirectAfterSave(): Response
    {
        return $this->redirectToRoute($this->routeName);
    }
}