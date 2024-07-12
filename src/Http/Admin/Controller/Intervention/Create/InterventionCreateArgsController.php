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
        // Récupération de l'ID equipment passé en paramètre
        $e = (int)$request->getQueryParams()['e'];
        $equipment = $this->getRepository(EquipmentRepository::class)->find('id', $e);
        // Récupération des données de l'utilisateur connecté
        $auth = $this->getUser();
        // Construction du formulaire
        $form = $this->createForm(InterventionArgsType::class, null, [
            'auth' => $auth,
            'e' => $e,
            'customers_id' => $args['id']
        ]);
        $form->handleRequest();
        // Récupération du client via l'ID passé dans l'URL
        $customer = $this->getRepository(CustomerRepository::class)->find('id', $args['id']);
        // return equipment
        // Traitement du formulaire
        if($form->isSubmitted() && $form->isValid()) {
            $data = $form->getRequestData()['form_intervention_args'];
            // Initialisation de l'objet Intervention avec les données du formulaire.
            $intervention = new Intervention($data);
            // Initialisation du service de formatage de numéro
            $numberFormatter = new SerialNumberFormatterService($this->parameter);
            // Vérification si une valeur est passée dans le paramètre "e" de l'URL
            if($e == 0) {
                $equipment = $this->getRepository(EquipmentRepository::class)->find('id', $intervention->getEquipmentsId());
            }
            // Construction de l'objet Intervention
            $intervention->setCompanyId($auth->getCompanyId());
            $intervention->setUsersId($auth->getId());
            $intervention->setCustomersId($args['id']);
            if($e > 0) {
                $intervention->setEquipmentsId($e);
            }
            $intervention->setEquipmentName($equipment->name);
            $intervention->setRefForLink(bin2hex(random_bytes(5)));
            $intervention->setRefNumber($numberFormatter->generateSerialNumber());
            $intervention->setCustomerName($customer->fullname);
            // Enregistrement des données en base de données via l'objet Intervention
            $this->getRepository(InterventionRepository::class)->add($intervention, true);
            // Information pour l'utilisateur
            $this->addFlash('panel-success', sprintf("Intervention N°%s créée avec succès.", $intervention->getRefNumber()));
            // Inscription dans les log de Proaxive
            $this->setLogger()->info(sprintf("[Add Intervention] Création de l'intervention N°%s.", $intervention->getRefNumber()));
            // Redirection de fin de traitement
            return $this->redirectToRoute('dash_intervention');
        }

        $bds = $this->breadcrumbs;
        $bds->addCrumb('Accueil', $this->getUrlFor('dash_home'));
        $bds->addCrumb('Interventions', $this->getUrlFor('dash_intervention'));
        $bds->addCrumb($customer->fullname, false);
        $bds->addCrumb('Création', $this->getUrlFor('intervention_create_index'));
        $bds->render();
        return $this->render($response, 'backoffice/intervention/create/create_args.html.twig', [
            'currentMenu' => 'intervention',
            'form' => $form,
            'auth' => $auth,
            'breadcrumbs' => $bds,
            'c' => $customer,
            'e' => $e,
            'equipment' => $equipment
        ]);
    }
}