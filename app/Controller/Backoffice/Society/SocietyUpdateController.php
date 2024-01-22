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
            $data = $form->getRequestData()['form_customer'];
            $save = $this->getRepository(CustomerRepository::class)->update($data, $customer_id);
            if($save){
                $this->session->getFlash()->add('panel-info', "Mise à jour effectuée.");
            }
        }
        // Breadcrumbs
        $bds = $this->app->getContainer()->get('breadcrumbs');
        $bds->addCrumb('Accueil', $this->routeParser->urlFor('dash_home'));
        $bds->addCrumb('Client (société)', $this->routeParser->urlFor('dash_customer'));
        $bds->addCrumb($customer->fullname, $this->routeParser->urlFor('customer_read', ['id' => $customer->id]));
        $bds->addCrumb('Modification', false);
        $bds->render();
        // .Breadcrumbs
        return $this->render($response, 'backoffice/society/update.html.twig', [
            'currentMenu' => 'customer',
            'customer' => $customer,
            'breadcrumbs' => $bds,
            'form' => $form
        ]);
    }

}