<?php

declare(strict_types=1);

namespace Selmak\Proaxive2\Http\Admin\Controller\Intervention\Create;

use Creitive\Breadcrumbs\Breadcrumbs;
use Envms\FluentPDO\Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Random\RandomException;
use Selmak\Proaxive2\Domain\Customer\Repository\CustomerRepository;
use Selmak\Proaxive2\Domain\Equipment\Repository\EquipmentRepository;
use Selmak\Proaxive2\Domain\Intervention\Intervention;
use Selmak\Proaxive2\Domain\Intervention\Repository\InterventionRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Selmak\Proaxive2\Http\Type\Admin\Intervention\InterventionCreateStep2Type;
use Selmak\Proaxive2\Http\Type\Admin\Intervention\InterventionCreateStep3Type;
use Selmak\Proaxive2\Http\Type\Admin\Intervention\InterventionCreateStep4Type;
use Selmak\Proaxive2\Http\Type\Admin\Intervention\InterventionCreateStep5Type;
use Selmak\Proaxive2\Http\Type\Admin\Intervention\InterventionCreateType;
use Selmak\Proaxive2\Infrastructure\Security\SerialNumberFormatterService;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class InterventionCreateController extends AbstractController
{
    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function create(Request $request, Response $response): Response
    {
        $auth = $this->getSession('auth');
        $form = $this->createForm(InterventionCreateType::class, null, $auth);
        $form->handleRequest();

        if($form->isSubmitted() && $form->isValid()) {
            $data = $request->getParsedBody()['form_intervention'];
            $this->setSession('data_intervention_step1', $data);
            return $this->redirectToRoute('intervention_create_regular_step2');
        }

        return $this->render($response, 'backoffice/intervention/create/start.html.twig', [
            'currentMenu' => 'intervention',
            'form' => $form,
            'auth' => $auth,
            'breadcrumbs' => $this->breadcrumbs(),
        ]);
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws SyntaxError
     * @throws RandomException
     * @throws ContainerExceptionInterface
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function createStep2(Request $request, Response $response): Response
    {
        $auth = $this->getSession('auth');
        $session = $this->getSession('data_intervention_step1');
        $session['roles'] = $auth['roles'];
        $inter = new Intervention();
        $form = $this->createForm(InterventionCreateStep2Type::class, $inter, $session);
        $form->handleRequest();
        if($form->isSubmitted() && $form->isValid()) {

            if(!$session){
                $this->addFlash('panel-error', 'La session a expirée !');
                return $this->redirectToRoute('dash_intervention');
            }
            $numberFormatter = new SerialNumberFormatterService($this->parameter);
            // Generate data
            $inter->setCompanyId($session['company_id']);
            $inter->setName($session['name']);
            $inter->setDescription($session['description']);
            $inter->setBeforeBreakdown($session['before_breakdown']);
            $inter->setRefForLink(bin2hex(random_bytes(5)));
            $inter->setRefNumber($numberFormatter->generateSerialNumber());
            if($auth['roles'] != "SUPER_ADMIN") {
                $inter->setUsersId($auth['id']);
            }
            // save to database
            $save = $this->getRepository(InterventionRepository::class)->createOject($inter);
            // Retrieve last ID
            $lastId = $this->getRepository(InterventionRepository::class)->lastInsertId();
            if($save){
                $this->deleteSession('data_intervention_step1');
                $this->setLogger()->info(sprintf("[Add Intervention] N°%s créée en tant que brouillon.", $inter->getRefNumber()));
                return $this->redirectToRoute('intervention_create_regular_step3', ['id' => $inter->getCustomersId()], ['inter' => $lastId]);
            }
        }
        return $this->render($response, 'backoffice/intervention/create/steps/steps2.html.twig', [
            'currentMenu' => 'intervention',
            'form' => $form,
            'auth' => $auth,
            'breadcrumbs' => $this->breadcrumbs(),
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws ContainerExceptionInterface
     * @throws Exception
     * @throws LoaderError
     * @throws NotFoundExceptionInterface
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function createStep3(Request $request, Response $response, array $args): Response
    {
        $id = (int)$args['id'];
        $i_id = (int)$request->getQueryParams()['inter'];
        $customer = $this->getRepository(CustomerRepository::class)->find('id', $id);
        $form = $this->createForm(InterventionCreateStep3Type::class, null, ['customers_id' => $id]);
        $form->handleRequest();
        if($form->isSubmitted() && $form->isValid()) {
            $data = $request->getParsedBody()['form_intervention_step3'];
            $equipment = $this->getRepository(EquipmentRepository::class)->find('id', $data['equipments_id']);
            $data['customer_name'] = $customer->fullname;
            $data['equipment_name'] = $equipment->name;
            $save = $this->getRepository(InterventionRepository::class)->update($data, $i_id);
            if($save){
                return $this->redirectToRoute('intervention_create_regular_step4', ['id' => $id], ['inter' => $i_id]);
            }
        }

        return $this->render($response, 'backoffice/intervention/create/steps/steps3.html.twig', [
            'currentMenu' => 'intervention',
            'form' => $form,
            'breadcrumbs' => $this->breadcrumbs(),
        ]);
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws SyntaxError
     * @throws ContainerExceptionInterface
     * @throws RuntimeError
     * @throws Exception
     * @throws LoaderError
     */
    public function createStep4(Request $request, Response $response, array $args): Response
    {
        $id = (int)$args['id'];
        $i_id = (int)$request->getQueryParams()['inter'];
        $form = $this->createForm(InterventionCreateStep4Type::class);
        $form->handleRequest();
        if($form->isSubmitted() && $form->isValid()) {
            $data = $request->getParsedBody()['form_intervention_step4'];
            $save = $this->getRepository(InterventionRepository::class)->update($data, $i_id);
            if($save){
                return $this->redirectToRoute('intervention_create_regular_step5', ['id' => $id], ['inter' => $i_id]);
            }
        }
        return $this->render($response, 'backoffice/intervention/create/steps/steps4.html.twig', [
            'currentMenu' => 'intervention',
            'form' => $form,
            'breadcrumbs' => $this->breadcrumbs(),
        ]);
    }

    /**
     * @throws SyntaxError
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     * @throws RuntimeError
     * @throws Exception
     * @throws LoaderError
     */
    public function createStep5(Request $request, Response $response, array $args): Response
    {
        $i_id = (int)$request->getQueryParams()['inter'];
        $form = $this->createForm(InterventionCreateStep5Type::class);
        $form->handleRequest();
        if($form->isSubmitted() && $form->isValid()) {
            $data = $request->getParsedBody()['form_intervention_step5'];
            $data['state'] = 'VALIDATED';
            $save = $this->getRepository(InterventionRepository::class)->update($data, $i_id);
            if($save){
                return $this->redirectToRoute('intervention_read', ['id' => $i_id]);
            }
        }
        return $this->render($response, 'backoffice/intervention/create/steps/steps5.html.twig', [
            'currentMenu' => 'intervention',
            'form' => $form,
            'breadcrumbs' => $this->breadcrumbs(),
        ]);
    }

    private function breadcrumbs(): Breadcrumbs
    {
        $bds = $this->breadcrumbs;
        $bds->addCrumb('Accueil', $this->getUrlFor('dash_home'));
        $bds->addCrumb('Interventions', $this->getUrlFor('dash_intervention'));
        $bds->addCrumb('Création', $this->getUrlFor('intervention_create_index'));
        $bds->addCrumb('Complète', false);
        $bds->render();
        return $bds;
    }
}