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
use Selmak\Proaxive2\Http\Type\Admin\WorkshopType;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class WorkshopActionController extends AbstractController
{

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws ContainerExceptionInterface
     * @throws Exception
     * @throws NotFoundExceptionInterface
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $form = $this->createForm(WorkshopType::class);
        $form->handleRequest();
        if($form->isSubmitted() && $form->isValid()) {
            $data = $form->getRequestData()['form_workshop'];
            $save = $this->getRepository(WorkshopRepository::class)->add($data, true);
            if($save) {
                $this->addFlash('panel-info', sprintf("Le nouveau magasin/atelier - %s - a bien été créé", $data['name']));
            }
        }
        // Breadcrumbs
        $bds = $this->breadcrumbs;
        $bds->addCrumb('Accueil', $this->getUrlFor('dash_home'));
        $bds->addCrumb('Magasins', $this->getUrlFor('dash_workshop'));
        $bds->addCrumb('Actions', false);
        $bds->render();
        // .Breadcrumbs
        return $this->render($response, 'backoffice/workshop/create.html.twig', [
            'form' => $form,
            'breadcrumbs' => $bds,
            'currentMenu' => 'workshop'
        ]);
    }
}