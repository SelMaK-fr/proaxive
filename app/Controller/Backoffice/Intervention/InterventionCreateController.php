<?php
declare(strict_types=1);
namespace App\Controller\Backoffice\Intervention;

use App\AbstractController;
use App\Repository\CustomerRepository;
use App\Repository\EquipmentRepository;
use App\Repository\InterventionRepository;
use App\Service\MailInterventionService;
use App\Type\InterventionCreateNextType;
use App\Type\InterventionCreateType;
use App\Type\InterventionFastType;
use Knp\Snappy\Pdf;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator as v;
use Selmak\Proaxive2\Service\SerialNumberFormatterService;

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
        $auth = $this->session->get('auth');

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
            $numberFormatter = new SerialNumberFormatterService($this->app->getContainer()->get('parameters'));
            $arrayData = [
                'name' => $data['name'],
                'customers_id' => $data_customer_id,
                'a_priority' => $data['a_priority'],
                'equipments_id' => $equipment_id,
                'sort' => $data['sort'],
                'company_id' => (int)$data['company_id'],
                'nb_equipments' => $findEquipment,
                'ref_number' => $numberFormatter->generateSerialNumber()
            ];
            // Save array in the session
            $this->session->set('form_intervention_next', $arrayData);
            return $this->redirectToRoute('intervention_create_customer_regular_complete');
        }
        return $this->render($response, 'backoffice/intervention/create/start.html.twig', [
            'currentMenu' => 'intervention',
            'form' => $form,
            'auth' => $auth,
            'customer' => $customer,
        ]);
    }


    public function next(Request $request, Response $response, array $args): Response
    {
        $e = null;
        $auth = $this->session->get('auth');
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
        $data = $form->getRequestData()['form_intervention_2time'];
        $form->handleRequest();
        $this->session->set('intervention_2time', $data);

        return $this->render($response, 'backoffice/intervention/create/next.html.twig', [
            'currentMenu' => 'intervention',
            'form' => $form,
            'c' => $c,
            'e' => $e,
            'auth' => $auth,
            'session' => $dataSession
        ]);
    }

    public function save(Request $request, Response $response, array $args): Response
    {
        if($request->getMethod() === 'POST') {
            $data_one = $this->session->get('form_intervention_next');
            $data = $request->getParsedBody()['form_intervention_2time'];
            unset($data_one['nb_equipments']);
            // Delete equipments_id if exist in the session "form_intervention_next"
            // IMPORTANT
            if($data_one['equipments_id'] === null){
                unset($data_one['equipments_id']);
            }
            $validator = $this->validator->validate($data, [
                'way_type' => [
                    'rules' => v::notBlank()
                ],
                'way_steps' => [
                    'rules' => v::notBlank()
                ],
                'users_id' => [
                    'rules' => v::notBlank()
                ]
            ]);
            if($validator->count() === 0){
                $data_one['ref_for_link'] = bin2hex(random_bytes(5));
                $data_one['state'] = "VALIDATED";
                $customer = $this->getRepository(CustomerRepository::class)->find('id', (int)$data_one['customers_id']);
                $data['customer_name'] = $customer->fullname;
                $merge = $data_one + $data;
                // send the email if the customer has an email address
                if($customer->mail){
                    $mail = new MailInterventionService($this->getParameters('mailer'));
                    $mail->sendMailStart($customer->mail, $this->view('mailer/intervention/start.html.twig', ['data' => $merge]));
                }
                // Generate PDF for deposit if checked
                /*
                if($data['with_deposit']){
                    $settings = $this->app->getContainer()->get('settings');
                    $snappy = new Pdf($settings['settings']['rootPath'] . '/vendor/h4cc/wkhtmltopdf-amd64/bin/wkhtmltopdf-amd64');
                    $path = $settings['storage']['documents'] . 'deposits/';
                    $snappy->generateFromHtml($this->view('snappy/deposit_pdf.html.twig', ['data' => $merge]), $path . $data_one['ref_number'] .'.pdf');
                }
                */
                $save = $this->getRepository(InterventionRepository::class)->add($merge, true);
                if($save){
                    $this->session->getFlash()->add('panel-info', sprintf("L'intervention - %s - a bien été créée.", $data_one['ref_number']));
                    return $this->redirectToRoute('dash_intervention');
                }
            } else {
                $this->session->getFlash()->add('panel-error', 'Tous les champs ne sont pas remplis !');
                return $response->withStatus(302)->withHeader('Location', $request->getServerParams()['HTTP_REFERER']);
            }

        }
        return new \Slim\Psr7\Response();
    }
}