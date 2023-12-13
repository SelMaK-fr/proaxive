<?php
declare(strict_types=1);
namespace App\Controller\Backoffice\Equipment\PeripheralDevice;

use App\AbstractController;
use App\Repository\CustomerRepository;
use App\Repository\EquipmentRepository;
use App\Type\EquipmentType;
use App\Type\PeripheralType;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PeripheralCreateController extends AbstractController
{

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws \Envms\FluentPDO\Exception
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
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
            if($customer_id){$data_customer_id = $customer_id;}else{$data_customer_id = (int)$data['customers_id'];}
            $cName = $this->getRepository(CustomerRepository::class)->find('id', $data_customer_id);
            $data['customer_name'] = $cName->fullname;
            $this->getRepository(EquipmentRepository::class)->add($data, true);
            $this->session->getFlash()->add('panel-info', sprintf("Le nouveau périphérique - %s - a bien été créé.", $data['name']));
            return $this->redirectToRoute('dash_equipment');
        }
        return $this->render($response, 'backoffice/equipment/device/create.html.twig', [
            'form' => $form,
            'c' => $c,
            'currentMenu' => 'equipment'
        ]);
    }
}