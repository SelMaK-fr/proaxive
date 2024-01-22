<?php
declare(strict_types=1);
namespace App\Controller\Backoffice\Intervention;

use App\AbstractController;
use App\Repository\CustomerRepository;
use App\Repository\InterventionRepository;
use App\Type\InterventionFastType;
use App\Type\InterventionSearchType;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Paginator\Paginator;

class InterventionController extends AbstractController
{

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws \Envms\FluentPDO\Exception
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index(Request $request, Response $response, array $args): Response
    {
        $all = $request->getQueryParams()['v'];
        if($all === "all"){
            $paginator = new Paginator('15', 'p', $request);
            $paginator->set_total($this->getRepository(InterventionRepository::class)->count());
            $interventions = $this->getRepository(InterventionRepository::class)->allArrayForPaginator($paginator->get_limit());
            $dataPaginate = $paginator->page_links();
            $template = 'backoffice/intervention/index_all.html.twig';
        } else {
            $interventions = $this->getRepository(InterventionRepository::class)->allWithUser()->orderBy('interventions.created_at DESC')->limit(9);
            $template = 'backoffice/intervention/index.html.twig';
            $dataPaginate = '';
        }
        $formSearch = $this->createForm(InterventionSearchType::class);
        $formSearch->handleRequest();
        if($formSearch->isSubmitted() && $formSearch->isValid()) {
            $data = $formSearch->getRequestData()['form_intervention_search'];
            $result = $this->getRepository(InterventionRepository::class)->searchByFields($data);
            $interventions = $result;
        }
        // Breadcrumbs
        $breadcrumbs = $this->app->getContainer()->get('breadcrumbs');
        $breadcrumbs->addCrumb('Accueil', $this->routeParser->urlFor('dash_home'));
        $breadcrumbs->addCrumb('Interventions', false);
        $breadcrumbs->render();
        // .Breadcrumbs
        $form = $this->createForm(InterventionFastType::class);
        $form->handleRequest();
        return $this->render($response, $template, [
            'currentMenu' => 'intervention',
            'interventions' => $interventions,
            'form' => $form,
            'formSearch' => $formSearch,
            'dataPaginate' => $dataPaginate,
            'breadcrumbs' => $breadcrumbs
        ]);
    }
}