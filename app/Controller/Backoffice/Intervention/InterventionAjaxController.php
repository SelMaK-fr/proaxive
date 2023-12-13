<?php
declare(strict_types=1);
namespace App\Controller\Backoffice\Intervention;

use App\AbstractController;
use App\Repository\CustomerRepository;
use App\Repository\InterventionRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Validator as V;
use Psr\Http\Message\ServerRequestInterface as Request;
use function DI\string;

class InterventionAjaxController extends AbstractController
{

    /**
     * Add fast intervention in a modal
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws \Envms\FluentPDO\Exception
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
                $arrayData = [
                    'ref_number' => rand(8,999999),
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
                        'activated' => 0
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
                    $this->session->getFlash()->add('panel-info', sprintf("La nouvelle intervention - %s - a bien été créée", $arrayData['ref_number']));
                    return $this->redirectToRoute('dash_intervention');
                }
            }
            $this->session->getFlash()->add('panel-error', "Le formulaire n'est pas rempli correctement !");
            return $this->redirect($request->getServerParams()['HTTP_REFERER']);
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
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
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
}