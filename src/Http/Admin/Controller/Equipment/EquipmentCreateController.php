<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Admin\Controller\Equipment;

use Envms\FluentPDO\Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\Customer\Repository\CustomerRepository;
use Selmak\Proaxive2\Domain\Equipment\Repository\EquipmentRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Selmak\Proaxive2\Http\Type\Admin\Equipment\EquipmentSpecsType;
use Selmak\Proaxive2\Http\Type\Admin\Equipment\EquipmentType;

class EquipmentCreateController extends AbstractController
{

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws Exception
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function create(Request $request, Response $response, array $args): Response
    {
        // init var customer
        $c = '';
        // check if id passed in parameter
        $customer_id = (int)$args['id'];
        if($customer_id){
            $c = $this->getRepository(CustomerRepository::class)->find('id', $customer_id);
        }
        $form = $this->createForm(EquipmentType::class);
        $form->handleRequest();
        if($form->isSubmitted()) {
            $data = $form->getRequestData()['form_equipment'];
            // If end_guarantee is empty = null
            if(empty($data['end_guarantee'])){$data['end_guarantee'] = null;}
            if($customer_id){$data_customer_id = $customer_id;$data['customers_id'] = $customer_id;}else{$data_customer_id = $data['customers_id'];}
            $cName = $this->getRepository(CustomerRepository::class)->find('id', $data_customer_id);
            $data['customer_name'] = $cName->fullname;
            $this->getRepository(EquipmentRepository::class)->add($data, true);
            $this->addFlash('panel-info', sprintf("Le nouvel équipement <b> %s </b> a bien été créé.", $data['name']));
            return $this->redirectToRoute('dash_equipment');
        }
        // Breadcrumbs
        $bds = $this->app->getContainer()->get('breadcrumbs');
        $bds->addCrumb('Accueil', $this->getUrlFor('dash_home'));
        $bds->addCrumb('Équipements', $this->getUrlFor('dash_equipment'));
        $bds->addCrumb('Création', false);
        $bds->render();
        // .Breadcrumbs
        return $this->render($response, 'backoffice/equipment/create.html.twig', [
            'form' => $form,
            'c' => $c,
            'breadcrumbs' => $bds,
            'currentMenu' => 'equipment'
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws ContainerExceptionInterface
     * @throws Exception
     * @throws NotFoundExceptionInterface
     */
    public function createSpecificities(Request $request, Response $response): Response
    {
        if(!$this->getSession('form_equipment_specs')) {
            $this->addFlash('panel-error', "La session a expirée pour ce formulaire.");
            return $this->redirectToRoute('equipment_create');
        }

        $dataSession = $this->getSession('form_equipment_specs');

        $session = $dataSession[1];
        $dataEquipment = $dataSession[0];
        $form = $this->createForm(EquipmentSpecsType::class);
        $form->handleRequest();
        if($form->isSubmitted() && $form->isValid()) {
            $data = $form->getRequestData()['form_equipment_specs'];
            $pushAllData = $data + $session + $dataEquipment;
            $this->getRepository(EquipmentRepository::class)->add($pushAllData, true);
            $this->addFlash('panel-info', sprintf("Le nouvel équipement %s a bien été créé.", $pushAllData['name']));
            $this->deleteSession('form_equipment_specs');
            return $this->redirectToRoute('dash_equipment');
        }

        return $this->render($response, 'backoffice/equipment/create_specs.html.twig', [
            'form' => $form,
            'session' => $session,
            'dataEquipment' => $dataEquipment,
            'currentMenu' => 'equipment'
        ]);
    }

}