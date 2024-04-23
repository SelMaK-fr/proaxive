<?php

declare(strict_types=1);

namespace Selmak\Proaxive2\Http\Admin\Controller;

use Selmak\Proaxive2\Http\Controller\AbstractController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

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
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
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

    public function crudCreate(string $type, string $repository, $response, mixed $breadcrumbs)
    {
        $form = $this->createForm($type);
        $form->handleRequest();
        if($form->isSubmitted() && $form->isValid()) {
            $data = $form->getRequestData()[$this->form_name];
            $this->getRepository($repository)->add($data, true);
            $this->addFlash('panel-info', 'Opération réalisée avec succès.');
            return $this->redirectAfterSave();
        }
        return $this->render($response, "backoffice/{$this->template_path}/create.html.twig", [
            'form' => $form,
            'breadcrumbs' => $breadcrumbs,
            'currentMenu' => $this->menuItem
        ]);
    }

    protected function redirectAfterSave(): Response
    {
        return $this->redirectToRoute($this->routeName);
    }
}