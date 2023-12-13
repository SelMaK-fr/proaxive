<?php
declare(strict_types=1);
namespace App\Controller\Backoffice\Society;

use App\AbstractController;
use App\Repository\CustomerRepository;
use App\Type\SocietyType;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class SocietyUpdateController extends AbstractController
{
    public function update(Request $request, Response $response, array $args): Response
    {
        $customer_id = (int)$args['id'];
        $customer = $this->getRepository(CustomerRepository::class)->find('id', $customer_id);
        $form = $this->createForm(SocietyType::class, $customer);
        $form->handleRequest();
        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getRequestData();
            dd($data['form_customer']);
        }
        return $this->render($response, 'backoffice/society/update.html.twig', [
            'currentMenu' => 'customer',
            'customer' => $customer,
            'form' => $form
        ]);
    }

}