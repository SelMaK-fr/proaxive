<?php
declare(strict_types=1);
namespace App\Controller\Frontoffice\Portal;

use App\AbstractController;
use App\Repository\CustomerRepository;
use App\Type\CustomerPortalAddressType;
use App\Type\CustomerPortalType;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PortalParameterController extends AbstractController
{

    public function index(Request $request, Response $response): Response
    {
        $c = $this->getRepository(CustomerRepository::class)->find('id', $this->session->get('customer')['id']);

        $form = $this->createForm(CustomerPortalType::class, $c);
        $form->handleRequest();

        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getRequestData()['form_customer'];
            $save = $this->getRepository(CustomerRepository::class)->update($data, $this->session->get('customer')['id']);
            if($save){
                $this->session->getFlash()->add('success', 'Votre compte a bien été mis à jour.');
            }
        }

        return $this->render($response, '/frontoffice/portal/parameter/index.html.twig', [
            'customer' => $c,
            'form' => $form
        ]);
    }

    public function address(Request $request, Response $response): Response
    {
        $c = $this->getRepository(CustomerRepository::class)->find('id', $this->session->get('customer')['id']);
        $form = $this->createForm(CustomerPortalAddressType::class, $c);
        $form->handleRequest();
        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getRequestData()['form_customer'];
            $save = $this->getRepository(CustomerRepository::class)->update($data, $this->session->get('customer')['id']);
            if($save){
                $this->session->getFlash()->add('success', 'Votre adresse a bien été mise à jour.');
            }
        }

        return $this->render($response, '/frontoffice/portal/parameter/address.html.twig', [
            'form' => $form
        ]);
    }
}