<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Admin\Controller\Intervention;

use Awurth\Validator\StatefulValidator;
use Envms\FluentPDO\Exception;
use Odan\Session\SessionInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator as v;
use Selmak\Proaxive2\Domain\Customer\Repository\CustomerRepository;
use Selmak\Proaxive2\Domain\Equipment\Repository\EquipmentRepository;
use Selmak\Proaxive2\Domain\Intervention\Repository\InterventionRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Selmak\Proaxive2\Http\Type\Admin\Intervention\InterventionCreateType;
use Selmak\Proaxive2\Http\Type\Admin\Intervention\InterventionFastType;
use Selmak\Proaxive2\Http\Type\InterventionCreateNextType;
use Selmak\Proaxive2\Infrastructure\Mailing\MailInterventionService;
use Selmak\Proaxive2\Infrastructure\Security\SerialNumberFormatterService;
use Slim\App;

class InterventionCreateController extends AbstractController
{

    public function __construct(private readonly StatefulValidator $validator, App $app, private readonly SessionInterface $session)
    {
        parent::__construct($app);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function index(Request $request, Response $response, array $args): Response
    {
        $form = $this->createForm(InterventionFastType::class);
        // Breadcrumbs
        $bds = $this->app->getContainer()->get('breadcrumbs');
        $bds->addCrumb('Accueil', $this->getUrlFor('dash_home'));
        $bds->addCrumb('Interventions', $this->getUrlFor('dash_intervention'));
        $bds->addCrumb('Création', false);
        $bds->render();
        // .Breadcrumbs
        return $this->render($response, 'backoffice/intervention/index_create.html.twig', [
            'currentMenu' => 'intervention',
            'breadcrumbs' => $bds,
            'form' => $form
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    public function regular(Request $request, Response $response, array $args): Response
    {
        $customer = '';
        $equipment_id = null;
        $customer_id = (int)$args['id'];
        $equipment = (int)$request->getQueryParams()['e'];
        $auth = $this->getSession('auth');
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
                'company_id' => $auth['company_id'],
                'nb_equipments' => $findEquipment,
                'ref_number' => $numberFormatter->generateSerialNumber()
            ];
            // Save array in the session
            $this->session->set('form_intervention_next', $arrayData);
            return $this->redirectToRoute('intervention_create_customer_regular_complete');
        }
        // Breadcrumbs
        $bds = $this->app->getContainer()->get('breadcrumbs');
        $bds->addCrumb('Accueil', $this->getUrlFor('dash_home'));
        $bds->addCrumb('Interventions', $this->getUrlFor('dash_intervention'));
        $bds->addCrumb('Création', $this->getUrlFor('intervention_create_index'));
        $bds->addCrumb('Complète', false);
        $bds->render();
        // .Breadcrumbs
        return $this->render($response, 'backoffice/intervention/create/start.html.twig', [
            'currentMenu' => 'intervention',
            'form' => $form,
            'auth' => $auth,
            'breadcrumbs' => $bds,
            'customer' => $customer,
        ]);
    }


    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws ContainerExceptionInterface
     * @throws Exception
     * @throws NotFoundExceptionInterface
     */
    public function next(Request $request, Response $response, array $args): Response
    {
        $e = null;
        $auth = $this->getSession('auth');
        // if not valid session, stop treatment and return to Create
        if(!$this->getSession('form_intervention_next')){
            $this->addFlash('panel-error', "Aucune session n'est disponbile pour cette demande, veuillez reprendre le formulaire.");
            return $this->redirectToRoute('intervention_create_index');
        }
        // Retrieve session
        $dataSession = $this->getSession('form_intervention_next');
        // Retrieve customer
        $c = $this->getRepository(CustomerRepository::class)->find('id', (int)$dataSession['customers_id']);
        // Check if ID equipment exist
        if($dataSession['equipments_id']){
            $e = $this->getRepository(EquipmentRepository::class)->find('id', $dataSession['equipments_id']);
        }
        // Create Form
        $form = $this->createForm(InterventionCreateNextType::class, null, $dataSession);
        $form->setAction($this->getUrlFor('intervention_create_save'));
        $data = $form->getRequestData();
        $form->handleRequest();
        $this->session->set('intervention_2time', $data);

        // Breadcrumbs
        $bds = $this->app->getContainer()->get('breadcrumbs');
        $bds->addCrumb('Accueil', $this->getUrlFor('dash_home'));
        $bds->addCrumb('Interventions', $this->getUrlFor('dash_intervention'));
        $bds->addCrumb('Création', $this->getUrlFor('intervention_create_index'));
        $bds->addCrumb('Etape 2', false);
        $bds->render();
        // .Breadcrumbs

        return $this->render($response, 'backoffice/intervention/create/next.html.twig', [
            'currentMenu' => 'intervention',
            'form' => $form,
            'c' => $c,
            'e' => $e,
            'auth' => $auth,
            'breadcrumbs' => $bds,
            'session' => $dataSession
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws ContainerExceptionInterface
     * @throws Exception
     * @throws NotFoundExceptionInterface
     * @throws \Random\RandomException
     */
    public function save(Request $request, Response $response, array $args): Response
    {
        if($request->getMethod() === 'POST') {
            $data_one = $this->getSession('form_intervention_next');
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
                $save = $this->getRepository(InterventionRepository::class)->add($merge, true);
                if($save){
                    $this->addFlash('panel-info', sprintf("L'intervention - %s - a bien été créée.", $data_one['ref_number']));
                    return $this->redirectToRoute('dash_intervention');
                }
            } else {
                $this->addFlash('panel-error', 'Tous les champs ne sont pas remplis !');
                return $this->redirectToReferer($request);
            }

        }
        return new \Slim\Psr7\Response();
    }
}