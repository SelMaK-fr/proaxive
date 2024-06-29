<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Admin\Controller\Intervention;

use Awurth\Validator\StatefulValidator;
use Envms\FluentPDO\Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator as V;
use Selmak\Proaxive2\Domain\Customer\Repository\CustomerRepository;
use Selmak\Proaxive2\Domain\Equipment\Repository\EquipmentRepository;
use Selmak\Proaxive2\Domain\Intervention\Repository\InterventionRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Selmak\Proaxive2\Infrastructure\Mailing\MailInterventionService;
use Selmak\Proaxive2\Infrastructure\Security\SerialNumberFormatterService;
use Slim\App;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class InterventionAjaxController extends AbstractController
{

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws Exception
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws \Random\RandomException
     */
    public function addFast(Request $request, Response $response): Response
    {
        if($request->getMethod() === 'POST') {
            $data = $request->getParsedBody()['form_intervention_add_fast'];
            $validator = $this->validator->validate($data, [
                'name' => [
                   'rules' => v::notBlank(),
                   'messages' => [
                       'notBlank' => "Veuillez renseigner un titre"
                   ]
               ],
                'sort' => [
                    'rules' => v::notBlank(),
                    'messages' => [
                        'notBlank' => "Veuillez sélectionner un type"
                    ]
                ]
            ]);
            if($validator->count() === 0){
                $numberFormatter = new SerialNumberFormatterService($this->parameter);
                $arrayData = [
                    'ref_number' => $numberFormatter->generateSerialNumber(),
                    'name' => $data['name'],
                    'ref_for_link' => bin2hex(random_bytes(5)),
                    'state' => 'DRAFT',
                    'sort' => $data['sort'],
                    'company_id' => $data['company_id'],
                    'customer_name' => $data['customer_name'],
                    'users_id' => $this->getUserId()
                ];
                $data['create_customer'] = $data['create_customer'] ?? null;
                if(!is_null($data['create_customer'])) {
                    $newCustomer = [
                        'fullname' => $data['fullname'],
                        'activated' => 0,
                        'is_draft' => 1
                    ];
                    $customerRepository = $this->getRepository(CustomerRepository::class);
                    $customerRepository->add($newCustomer, true);
                    $arrayData['customers_id'] = $customerRepository->lastInsertId();
                    $arrayData['customer_name'] = $data['fullname'];
                } else {
                    $arrayData['customers_id'] = $data['customers_id'];
                }
                unset($data['create_customer']);
                $save = $this->getRepository(InterventionRepository::class)->add($arrayData, true);
                if($save){
                    $this->addFlash('panel-info', sprintf("La nouvelle intervention - %s - a bien été créée", $arrayData['ref_number']));
                    return $this->redirectToRoute('dash_intervention');
                }
            }
            $this->addFast('panel-error', "Le formulaire n'est pas rempli correctement !");
            return $this->redirectToReferer($request);
        }
        $response->getBody()->write('Error form data - please contact administrator');
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(201);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function search(Request $request, Response $response, array $args): Response
    {
        $i = [];
        if($request->getMethod() === 'GET') {
            $i = $this->getRepository(InterventionRepository::class)->search($request->getAttribute('key'));
        }
        return $this->render($response, 'backoffice/intervention/search.html.twig', [
            'interventions' => $i,
            'currentMenu' => 'customer'
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws Exception
     */
    public function start(Request $request, Response $response, array $args): Response
    {
        $intervention_id = (int)$args['id'];
        if($request->getMethod() === 'POST') {
            $this->getRepository(InterventionRepository::class)->update([
               'start_date' => date('Y-m-d h:i:s'),
                'way_steps' => 2
            ], $intervention_id);
            // If the client at a mail address, send mail
            $i = $this->getRepository(InterventionRepository::class)->findWithCustomer($intervention_id);
            $parameters = $this->parameter->getParams();
            if($i['mail'] && $parameters['mail_auto'] == 1){
                $mail = new MailInterventionService($this->getParameters('mailer'));
                $mail->sendMailStart($i['mail'], $this->view('mailer/intervention/start.html.twig', ['data' => $i]));
            }
        }
        return $response;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws Exception
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function end(Request $request, Response $response, array $args): Response
    {
        $intervention_id = (int)$args['id'];
        if($request->getMethod() === 'POST') {
            $this->getRepository(InterventionRepository::class)->update([
                'end_date' => date('Y-m-d h:i:s'),
                'state' => 'COMPLETED',
                'way_steps' => 5,
                'is_closed' => 1
            ], $intervention_id);
            // If the client at a mail address, send mail
            $i = $this->getRepository(InterventionRepository::class)->findWithCustomer($intervention_id);
            $parameters = $this->parameter->getParams();
            if($i['mail'] && $parameters['mail_auto'] == 1){
                $mail = new MailInterventionService($this->getParameters('mailer'));
                $mail->sendMailStart($i['mail'], $this->view('mailer/intervention/end.html.twig', ['data' => $i]));
            }
        }
        return $response;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws Exception
     */
    public function updateEquipmentName(Request $request, Response $response, array $args): Response
    {
        $intervention_id = (int)$args['id'];
        $i = $this->getRepository(InterventionRepository::class)->find('id', $intervention_id);
        $e = $this->getRepository(EquipmentRepository::class)->find('id', $i->equipments_id);
        if($request->getMethod() === 'POST') {
            $data = [
                'equipment_name' => $e->name
            ];
            $this->getRepository(InterventionRepository::class)->update($data, $intervention_id);
        }
        return new \Slim\Psr7\Response();
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws Exception
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function nextStep(Request $request, Response $response, array $args): Response
    {
        $intervention_id = (int)$args['id'];
        if($request->getMethod() === 'POST'){
            $i = $this->getRepository(InterventionRepository::class)->find('id', $intervention_id);
            $currentStep = $i->way_steps;
            // Checks if way_steps is equal to 4, in this case end the intervention.
            // Otherwise, continue incrementing
            if($currentStep === 4){
                $data = [
                    'end_date' => date('Y-m-d h:i:s'),
                    'state' => 'COMPLETED',
                    'way_steps' => 5,
                    'is_closed' => 1
                ];
                // If the client at a mail address, send mail
                $i = $this->getRepository(InterventionRepository::class)->findWithCustomer($intervention_id);
                $parameters = $this->parameter->getParams();
                if($i['mail'] && $parameters['mail_auto'] == 1){
                    $mail = new MailInterventionService($this->getParameters('mailer'));
                    $mail->sendMailStart($i['mail'], $this->view('mailer/intervention/end.html.twig', ['data' => $i]));
                }
            } else {
                $data = [
                    'way_steps' => $currentStep + 1
                ];
            }
            $this->getRepository(InterventionRepository::class)->update($data, $intervention_id);
        }
        return new \Slim\Psr7\Response();
    }
}