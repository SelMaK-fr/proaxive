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
            $interventions = $this->getRepository(InterventionRepository::class)->all()->orderBy('created_at DESC');
            $template = 'backoffice/intervention/index_all.html.twig';
        } else {
            $interventions = $this->getRepository(InterventionRepository::class)->all()->orderBy('created_at DESC')->limit(9);
            $template = 'backoffice/intervention/index.html.twig';
        }
        $formSearch = $this->createForm(InterventionSearchType::class);
        $formSearch->handleRequest();
        if($formSearch->isSubmitted() && $formSearch->isValid()) {
            $data = $formSearch->getRequestData()['form_intervention_search'];
            $result = $this->getRepository(InterventionRepository::class)->searchByFields($data);
            $interventions = $result;
        }
        //
        $breadcrumbs = $this->app->getContainer()->get('breadcrumbs');
        $breadcrumbs->addCrumb('Accueil', $this->routeParser->urlFor('dash_home'));
        $breadcrumbs->addCrumb('Interventions', false);
        $breadcrumbs->render();
        //
        $form = $this->createForm(InterventionFastType::class);
        $form->handleRequest();
        return $this->render($response, $template, [
            'currentMenu' => 'intervention',
            'interventions' => $interventions,
            'form' => $form,
            'formSearch' => $formSearch,
            'breadcrumbs' => $breadcrumbs
        ]);
    }
}