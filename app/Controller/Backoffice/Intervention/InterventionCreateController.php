<?php
declare(strict_types=1);
namespace App\Controller\Backoffice\Intervention;

use App\AbstractController;
use App\Repository\CustomerRepository;
use App\Repository\EquipmentRepository;
use App\Type\InterventionCreateNextType;
use App\Type\InterventionCreateType;
use App\Type\InterventionFastType;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
class InterventionCreateController extends AbstractController
{

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index(Request $request, Response $response, array $args): Response
    {
        $form = $this->createForm(InterventionFastType::class);
        return $this->render($response, 'backoffice/intervention/index_create.html.twig', [
            'currentMenu' => 'intervention',
            'form' => $form
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function regular(Request $request, Response $response, array $args): Response
    {

        $customer = '';
        $equipment_id = null;
        $customer_id = (int)$args['id'];
        $equipment = (int)$request->getQueryParams()['e'];

        if($equipment != null) {
            $e = $this->getRepository(EquipmentRepository::class)->find('id', $equipment);
            $customer_id = $e->customers_id;
            $equipment_id = $e->id;
        }
        if($customer_id) {
            $customer = $this->getRepository(CustomerRepository::class)->find('id', $customer_id);
        }
        $form = $this->createForm(InterventionCreateType::class);
        $form->handleRequest();
        if($form->isSubmitted() && $form->isValid()) {
            $data = $form->getRequestData()['form_intervention'];
            if($customer_id){$data_customer_id = $customer_id;}else{$data_customer_id = $data['customers_id'];}
            $findEquipment = $this->getRepository(EquipmentRepository::class)->countRowWhere((int)$data_customer_id, 'customers_id');
            $arrayData = [
                'name' => $data['name'],
                'customers_id' => $data_customer_id,
                'a_priority' => $data['a_priority'],
                'equipments_id' => $equipment_id,
                'sort' => $data['sort'],
                'company_id' => $data['company_id'],
                'nb_equipments' => $findEquipment
            ];
            // Save array in the session
            $this->session->set('form_intervention_next', $arrayData);
            return $this->redirectToRoute('intervention_create_customer_regular_complete');
        }
        return $this->render($response, 'backoffice/intervention/create/start.html.twig', [
            'currentMenu' => 'intervention',
            'form' => $form,
            'customer' => $customer,
        ]);
    }


    public function next(Request $request, Response $response, array $args): Response
    {
        $e = null;
        // if not valid session, stop treatment and return to Create
        if(!$this->session->get('form_intervention_next')){
            $this->session->getFlash()->add('panel-error', "Aucune session n'est disponbile pour cette demande, veuillez reprendre le formulaire.");
            return $response->withStatus(302)->withHeader('Location', $this->routeParser->urlFor('intervention_create_index'));
        }
        // Retrieve session
        $dataSession = $this->session->get('form_intervention_next');
        // Retrieve customer
        $c = $this->getRepository(CustomerRepository::class)->find('id', (int)$dataSession['customers_id']);
        // Check if ID equipment exist
        if($dataSession['equipments_id']){
            $e = $this->getRepository(EquipmentRepository::class)->find('id', $dataSession['equipments_id']);
        }
        // Create Form
        $form = $this->createForm(InterventionCreateNextType::class, null, $dataSession);
        $form->setAction($this->routeParser->urlFor('intervention_create_save'));
        $form->handleRequest();

        return $this->render($response, 'backoffice/intervention/create/next.html.twig', [
            'currentMenu' => 'intervention',
            'form' => $form,
            'c' => $c,
            'e' => $e,
            'session' => $dataSession
        ]);
    }

    public function save(Request $request, Response $response, array $args): Response
    {
        if($request->getMethod() === 'POST') {
            $this->session->get('form_intervention_next');
            $data = $request->getParsedBody();
            dd($data);
        }
    }
}