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
use Random\RandomException;
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
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class InterventionCreateController extends AbstractController
{

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function index(Request $request, Response $response, array $args): Response
    {
        $form = $this->createForm(InterventionFastType::class);
        $pendingInterventions = $this->getRepository(InterventionRepository::class)->allBy('state', 'PENDING', 8, true);
        // Breadcrumbs
        $bds = $this->breadcrumbs;
        $bds->addCrumb('Accueil', $this->getUrlFor('dash_home'));
        $bds->addCrumb('Interventions', $this->getUrlFor('dash_intervention'));
        $bds->addCrumb('Création', false);
        $bds->render();
        // .Breadcrumbs
        return $this->render($response, 'backoffice/intervention/index_create.html.twig', [
            'currentMenu' => 'intervention',
            'pendingInterventions' => $pendingInterventions,
            'breadcrumbs' => $bds,
            'form' => $form
        ]);
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
     * @throws RandomException
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