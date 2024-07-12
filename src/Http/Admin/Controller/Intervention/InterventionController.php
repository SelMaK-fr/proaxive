<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Admin\Controller\Intervention;

use Envms\FluentPDO\Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\Intervention\Repository\InterventionRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Selmak\Proaxive2\Http\Type\Admin\Intervention\InterventionFastType;
use Selmak\Proaxive2\Http\Type\Admin\Intervention\InterventionSearchType;
use Selmak\Proaxive2\Infrastructure\Paginator\Paginator;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class InterventionController extends AbstractController
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
    public function index(Request $request, Response $response, array $args): Response
    {
        $filter = $request->getQueryParams()['filter'];
        $page = $request->getQueryParams()['p'];
        $interventions_hot = $this->getRepository(InterventionRepository::class)->fullDataIfHot();
        $interventions = $this->getRepository(InterventionRepository::class)->findWithPaginate(12, (int)$page ?: 1, $filter);
        $formSearch = $this->createForm(InterventionSearchType::class);
        $formSearch->handleRequest();
        if($formSearch->isSubmitted() && $formSearch->isValid()) {
            $data = $formSearch->getRequestData()['form_intervention_search'];
            $interventions = $this->getRepository(InterventionRepository::class)->searchByFieldsWithPaginate(12, (int)$page ?: 1, $data);
        }
        // Breadcrumbs
        $breadcrumbs = $this->breadcrumbs;
        $breadcrumbs->addCrumb('Accueil', $this->getUrlFor('dash_home'));
        $breadcrumbs->addCrumb('Interventions', false);
        $breadcrumbs->render();
        // .Breadcrumbs
        $form = $this->createForm(InterventionFastType::class);
        $form->handleRequest();
        return $this->render($response, 'backoffice/intervention/index.html.twig', [
            'currentMenu' => 'intervention',
            'interventions' => $interventions,
            'i_hot' => $interventions_hot,
            'form' => $form,
            'formSearch' => $formSearch,
            'breadcrumbs' => $breadcrumbs,
            'filter' => $filter
        ]);
    }
}