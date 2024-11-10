<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Admin\Controller\Intervention;

use Awurth\Validator\StatefulValidator;
use Envms\FluentPDO\Exception;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator as V;
use Selmak\Proaxive2\Domain\Customer\CustomerFastDTO;
use Selmak\Proaxive2\Domain\Customer\Repository\CustomerRepository;
use Selmak\Proaxive2\Domain\Equipment\Repository\EquipmentRepository;
use Selmak\Proaxive2\Domain\Intervention\Repository\InterventionRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Selmak\Proaxive2\Infrastructure\Mailing\MailInterventionService;
use Selmak\Proaxive2\Infrastructure\Mailing\MailService;
use Selmak\Proaxive2\Infrastructure\Security\RandomStringGeneratorFactory;
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
                // Génération des informations liées à l'intervention
                $arrayData = [
                    'ref_number' => $numberFormatter->generateSerialNumber(),
                    'name' => $data['name'],
                    'ref_for_link' => bin2hex(random_bytes(5)),
                    'state' => 'PENDING',
                    'sort' => $data['sort'],
                    'company_id' => $data['company_id'],
                    'customer_name' => $data['customer_name'],
                    'status_id' => 3,
                    'a_priority' => 'AVERAGE',
                    'users_id' => $this->getUser()->getId()
                ];
                // Check si la création de client est cochée
                $data['create_customer'] = $data['create_customer'] ?? null;
                // Si oui, création du client avec le nom complet rentré
                if(!is_null($data['create_customer'])) {
                    // Appel du factory RamdomStringGeneratorFactory
                    // permet de générer un numéro client aléatoir et unique
                    $generateClientId = new RandomStringGeneratorFactory();
                    // Extrait le nom complet
                    $fullname = self::extractFullname($data['fullname']);
                    // Sauvegarde du nom
                    $lastname = $fullname['lastname'];
                    // Sauvegarde du prénom
                    $firstname = $fullname['firstname'];
                    // Création de l'objet Customer via son DTO
                    $newCustomer = new CustomerFastDTO(['firstname' => $firstname, 'lastname' => $lastname]);
                    // Récupération du repository Customer
                    $customerRepository = $this->getRepository(CustomerRepository::class);
                    // Set le nom complet
                    $newCustomer->setFullname($newCustomer->getFullname());
                    // Set le nuéro client (login ID)
                    $newCustomer->setLoginId('C-' . $generateClientId->generate(9));
                    // Sauvegarder le client en base de données
                    $customerRepository->add($newCustomer, true);
                    // Récupération du dernier ID insérer et le fullname généré et les envoyer au tableau arrayData (intervention).
                    $arrayData['customers_id'] = $customerRepository->lastInsertId();
                    $arrayData['customer_name'] = $newCustomer->getFullname();
                } else {
                    // Dans le cas de la création via selection de client (select)
                    $arrayData['customers_id'] = $data['customers_id'];
                }
                // Destruction du champ create_customer dans le tableau data du formulaire.
                unset($data['create_customer']);
                // Sauvegarde de l'intervention en base de données.
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
                'state' => 'PROGRESS',
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
                'is_closed' => 1,
                'status_id' => 4,
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
     * AT DELETED
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

    public function updateCustomerName(Request $request, Response $response, array $args): Response
    {
        $intervention_id = (int)$args['id'];
        $i = $this->getRepository(InterventionRepository::class)->find('id', $intervention_id);
        $customer = $this->getRepository(CustomerRepository::class)->find('id', $i->customers_id);
        if($request->getMethod() === 'POST') {
            $data = [
                'customer_name' => $customer->fullname
            ];
            $this->getRepository(InterventionRepository::class)->update($data, $intervention_id);
        }
        return new \Slim\Psr7\Response();
    }

    /**
     * AT DELETED
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
            if($currentStep === 3){
                $data = [
                    'way_steps' => 4,
                    'status_id' => 2
                ];
            } elseif ($currentStep === 4) {
                $data = [
                    'end_date' => date('Y-m-d h:i:s'),
                    'state' => 'COMPLETED',
                    'way_steps' => 5,
                    'status_id' => 4,
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

    /**
     * Permet de mettre à jour l'état de l'intervention (steps/progress css)
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws Exception
     */
    public function updateSteps(Request $request, Response $response, array $args): Response
    {
        $intervention_id = (int)$args['id'];
        if($request->getMethod() === 'POST') {
            $i = $this->getRepository(InterventionRepository::class)->find('id', $intervention_id);
            $customer = $this->getRepository(CustomerRepository::class)->find('id', $i->customers_id);
            $data = $request->getParsedBody();
            if($data['way_steps'] === 5){
                $data = [
                    'end_date' => date('Y-m-d h:i:s'),
                    'state' => 'COMPLETED',
                    'way_steps' => 5,
                    'status_id' => 4,
                    'is_closed' => 1
                ];
                // Todo : send mail
                if($customer->mail){
                    $mail = new MailService($this->getParameters('mailer'));
                    $mail->sendMail($customer->mail, $this->view('mailer/intervention/end.html.twig', ['data' => $i]), 'Intervention terminée.');
                }
            } elseif ($data['way_steps'] === 4) {
                $data['status_id'] = 2;
            }
            $i = $this->getRepository(InterventionRepository::class)->update($data, $intervention_id);
            if(!$i){
                return new JsonResponse(['error' => 'Impossible de poursuivre cette action'], 500, []);
            }
        }
        return new \Slim\Psr7\Response();
    }

    public function updateStatus(Request $request, Response $response, array $args): Response
    {
        $intervention_id = (int)$args['id'];
        if($request->getMethod() === 'POST') {
            $data = $request->getParsedBody();
            if($data['status_id'] === 4){
                $data = [
                    'end_date' => date('Y-m-d h:i:s'),
                    'state' => 'COMPLETED',
                    'way_steps' => 5,
                    'status_id' => 4,
                    'is_closed' => 1
                ];
                // Todo : send mail
            }
            $i = $this->getRepository(InterventionRepository::class)->update($data, $intervention_id);
            if(!$i){
                return new JsonResponse(['error' => 'Impossible de poursuivre cette action'], 500, []);
            }
        }
        return new \Slim\Psr7\Response();
    }

    /*
     * Permet de découper le nom complet.
     */
    private function extractFullname(string $fullname): array
    {
        $value = preg_split("/\s+/", $fullname);
        $firstname = array_shift($value);
        $lastname = implode('', $value);
        return [
            'firstname' => $firstname,
            'lastname' => $lastname,
        ];
    }
}