<?php
declare(strict_types=1);

namespace Selmak\Proaxive2\Http\Admin\Controller\Intervention\Create;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\Customer\Repository\CustomerRepository;
use Selmak\Proaxive2\Domain\Equipment\Repository\EquipmentRepository;
use Selmak\Proaxive2\Domain\Intervention\Intervention;
use Selmak\Proaxive2\Domain\Intervention\Repository\InterventionRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Selmak\Proaxive2\Http\Type\Admin\Intervention\InterventionArgsType;
use Selmak\Proaxive2\Infrastructure\Security\SerialNumberFormatterService;

class InterventionCreateArgsController extends AbstractController
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $e = (int)$request->getQueryParams()['e'];
        $auth = $this->getSession('auth');
        $intervention = new Intervention();
        $form = $this->createForm(InterventionArgsType::class, $intervention, $auth);
        $form->handleRequest();
        // return customer
        $customer = $this->getRepository(CustomerRepository::class)->find('id', $args['id']);
        // return equipment
        $equipment = $this->getRepository(EquipmentRepository::class)->find('id', $e);

        if($form->isSubmitted() && $form->isValid()) {
            $numberFormatter = new SerialNumberFormatterService($this->parameter);
            $intervention->setCompanyId($this->getSession('auth')['company_id']);
            $intervention->setCustomersId($args['id']);
            $intervention->setEquipmentsId($e);
            $intervention->setRefForLink(bin2hex(random_bytes(5)));
            $intervention->setRefNumber($numberFormatter->generateSerialNumber());
            $intervention->setState('VALIDATED');
            $intervention->setCustomerName($customer->fullname);
            $intervention->setEquipmentName($equipment->name);

            $this->getRepository(InterventionRepository::class)->createOject($intervention);
            $this->addFlash('panel-success', sprintf("Intervention N°%s créée avec succès.", $intervention->getRefNumber()));
            $this->setLogger()->info(sprintf("[Add Intervention] Création de l'intervention N°%s.", $intervention->getRefNumber()));
            return $this->redirectToRoute('dash_intervention');
        }

        $bds = $this->breadcrumbs;
        $bds->addCrumb('Accueil', $this->getUrlFor('dash_home'));
        $bds->addCrumb('Interventions', $this->getUrlFor('dash_intervention'));
        $bds->addCrumb($customer->fullname, false);
        $bds->addCrumb($equipment->name, false);
        $bds->addCrumb('Création', $this->getUrlFor('intervention_create_index'));
        $bds->render();

        return $this->render($response, 'backoffice/intervention/create/create_args.html.twig', [
            'currentMenu' => 'intervention',
            'form' => $form,
            'auth' => $auth,
            'breadcrumbs' => $bds,
            'c' => $customer,
            'e' => $equipment
        ]);
    }
}