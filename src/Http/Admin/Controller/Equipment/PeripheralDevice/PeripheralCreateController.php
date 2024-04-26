<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Admin\Controller\Equipment\PeripheralDevice;

use Envms\FluentPDO\Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\Customer\Repository\CustomerRepository;
use Selmak\Proaxive2\Domain\Equipment\Repository\EquipmentRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Selmak\Proaxive2\Http\Type\Admin\PeripheralType;

class PeripheralCreateController extends AbstractController
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
        $form = $this->createForm(PeripheralType::class);
        $form->handleRequest();
        if($form->isSubmitted() && $form->isValid()) {
            $data = $form->getRequestData()['form_equipment'];
            if($customer_id){$data_customer_id = $customer_id;$data['customers_id'] = $customer_id;}else{$data_customer_id = $data['customers_id'];}
            if(empty($data['end_guarantee'])){$data['end_guarantee'] = null;}
            $cName = $this->getRepository(CustomerRepository::class)->find('id', (int)$data_customer_id);
            $data['customer_name'] = $cName->fullname;
            $this->getRepository(EquipmentRepository::class)->add($data, true);
            $this->addFlash('panel-info', sprintf("Le nouveau périphérique - %s - a bien été créé.", $data['name']));
            return $this->redirectToRoute('dash_equipment');
        }
        // Breadcrumbs
        $bds = $this->app->getContainer()->get('breadcrumbs');
        $bds->addCrumb('Accueil', $this->getUrlFor('dash_home'));
        $bds->addCrumb('Équipements', $this->getUrlFor('dash_equipment'));
        $bds->addCrumb('Périphérique', false);
        $bds->addCrumb('Création', false);
        $bds->render();
        // .Breadcrumbs
        return $this->render($response, 'backoffice/equipment/device/create.html.twig', [
            'form' => $form,
            'c' => $c,
            'breadcrumbs' => $bds,
            'currentMenu' => 'equipment'
        ]);
    }
}