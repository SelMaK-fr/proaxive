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
        if(!empty($filter)){
            $interventions = $this->getRepository(InterventionRepository::class)->allWithUser()->where('state = ?', $filter)->orderBy('interventions.created_at DESC');
        } else {
            $paginator = new Paginator('15', 'p', $request);
            $paginator->set_total($this->getRepository(InterventionRepository::class)->count());
            $interventions = $this->getRepository(InterventionRepository::class)->allArrayForPaginator($paginator->get_limit());
            $dataPaginate = $paginator->page_links();
        }
        $formSearch = $this->createForm(InterventionSearchType::class);
        $formSearch->handleRequest();
        if($formSearch->isSubmitted() && $formSearch->isValid()) {
            $data = $formSearch->getRequestData()['form_intervention_search'];
            $interventions = $this->getRepository(InterventionRepository::class)->searchByFields($data);
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
            'form' => $form,
            'formSearch' => $formSearch,
            'dataPaginate' => $dataPaginate,
            'breadcrumbs' => $breadcrumbs,
            'filter' => $filter
        ]);
    }
}