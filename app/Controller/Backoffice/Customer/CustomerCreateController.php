<?php
declare(strict_types=1);
namespace App\Controller\Backoffice\Customer;

use App\AbstractController;
use App\Repository\CustomerRepository;
use App\Type\CustomerType;
use App\Type\SocietyType;
use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Factory\RandomStringGeneratorFactory;

class CustomerCreateController extends AbstractController
{

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws \Envms\FluentPDO\Exception
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function particular(Request $request, Response $response): Response
    {
        $form = $this->createForm(CustomerType::class);
        //$form->setAction($this->routeParser->urlFor('customer_create_action'));
        $form->handleRequest();
        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getRequestData()['form_customer'];
            $data['activated'] = 1;
            $generateClientId = new RandomStringGeneratorFactory();
            $data['login_id'] = 'C-' . $generateClientId->generate(9);
            $checkIfExist = $this->getRepository(CustomerRepository::class)->ifExist('mail', $data['mail']);
            if($checkIfExist == 1){
                $this->session->getFlash()->add('panel-error', "Un compte client existe déjà avec cette adresse courriel.");
            } else {
                $save = $this->getRepository(CustomerRepository::class)->add($data, true);
                if($save){
                    $lastId = $this->getRepository(CustomerRepository::class)->lastInsertId();
                    return $this->redirectToRoute('customer_read', ['id' => $lastId]);
                }
            }
        }
        // Breadcrumbs
        $bds = $this->app->getContainer()->get('breadcrumbs');
        $bds->addCrumb('Accueil', $this->routeParser->urlFor('dash_home'));
        $bds->addCrumb('Clients', $this->routeParser->urlFor('dash_customer'));
        $bds->addCrumb('Création', false);
        $bds->render();
        // .Breadcrumbs
        return $this->render($response, 'backoffice/customer/create.html.twig', [
            'form' => $form,
            'breadcrumbs' => $bds,
            'currentMenu' => 'customer'
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws \Envms\FluentPDO\Exception
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function society(Request $request, Response $response): Response
    {
        $form = $this->createForm(SocietyType::class);
        //$form->setAction($this->routeParser->urlFor('customer_create_action', [], ['s' => 'active']));
        $form->handleRequest();
        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getRequestData()['form_customer'];
            $data['is_society'] = 1;
            $data['activated'] = 1;
            $generateClientId = new RandomStringGeneratorFactory();
            $data['login_id'] = 'C-' . $generateClientId->generate(9);
            $checkIfExist = $this->getRepository(CustomerRepository::class)->ifExist('mail', $data['mail']);
            if($checkIfExist == 1){
                $this->session->getFlash()->add('panel-error', "Un compte client existe déjà avec cette adresse courriel.");
            } else {
                $save = $this->getRepository(CustomerRepository::class)->add($data, true);
                if($save){
                    $lastId = $this->getRepository(CustomerRepository::class)->lastInsertId();
                    return $this->redirectToRoute('customer_read', ['id' => $lastId]);
                }
            }
        }
        // Breadcrumbs
        $bds = $this->app->getContainer()->get('breadcrumbs');
        $bds->addCrumb('Accueil', $this->routeParser->urlFor('dash_home'));
        $bds->addCrumb('Clients (société)', $this->routeParser->urlFor('dash_customer'));
        $bds->addCrumb('Création', false);
        $bds->render();
        // .Breadcrumbs
        return $this->render($response, 'backoffice/society/create.html.twig', [
            'form' => $form,
            'breadcrumbs' => $bds,
            'currentMenu' => 'customer'
        ]);
    }
}