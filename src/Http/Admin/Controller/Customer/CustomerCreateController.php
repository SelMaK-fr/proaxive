<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Admin\Controller\Customer;

use Envms\FluentPDO\Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\Customer\Customer;
use Selmak\Proaxive2\Domain\Customer\Repository\CustomerRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Selmak\Proaxive2\Http\Type\Admin\SocietyType;
use Selmak\Proaxive2\Http\Type\Customer\CustomerType;
use Selmak\Proaxive2\Infrastructure\Security\RandomStringGeneratorFactory;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class CustomerCreateController extends AbstractController
{

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws ContainerExceptionInterface
     * @throws Exception
     * @throws LoaderError
     * @throws NotFoundExceptionInterface
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function particular(Request $request, Response $response): Response
    {
        $form = $this->createForm(CustomerType::class);
        //$form->setAction($this->routeParser->urlFor('customer_create_action'));
        $form->handleRequest();
        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getRequestData()['form_customer'];
            $customer = new Customer($data);
            $customer->setActivated(1);
            $generateClientId = new RandomStringGeneratorFactory();
            $customer->setLoginId('C-' . $generateClientId->generate(9));
            $customer->setFullname($customer->getFirstName() . ' ' . $customer->getLastName());
            $checkIfExist = $this->getRepository(CustomerRepository::class)->ifExist('mail', $customer->getMail());
            if($checkIfExist == 1){
                $this->addFlash('panel-error', "Un compte client existe déjà avec cette adresse courriel.");
            } else {
                $save = $this->getRepository(CustomerRepository::class)->add($customer, true);
                if($save){
                    $lastId = $this->getRepository(CustomerRepository::class)->lastInsertId();
                    return $this->redirectToRoute('customer_read', ['id' => $lastId]);
                }
            }
        }
        // Breadcrumbs
        $bds = $this->breadcrumbs;
        $bds->addCrumb('Accueil', $this->getUrlFor('dash_home'));
        $bds->addCrumb('Clients', $this->getUrlFor('dash_customer'));
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
     * @throws ContainerExceptionInterface
     * @throws Exception
     * @throws NotFoundExceptionInterface
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function society(Request $request, Response $response): Response
    {
        $form = $this->createForm(SocietyType::class);
        $form->handleRequest();
        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getRequestData()['form_customer'];
            $data['is_society'] = 1;
            $data['activated'] = 1;
            $generateClientId = new RandomStringGeneratorFactory();
            $data['login_id'] = 'C-' . $generateClientId->generate(9);
            $checkIfExist = $this->getRepository(CustomerRepository::class)->ifExist('mail', $data['mail']);
            if($checkIfExist == 1){
                $this->addFlash('panel-error', "Un compte client existe déjà avec cette adresse courriel.");
            } else {
                $save = $this->getRepository(CustomerRepository::class)->add($data, true);
                if($save){
                    $lastId = $this->getRepository(CustomerRepository::class)->lastInsertId();
                    return $this->redirectToRoute('customer_read', ['id' => $lastId]);
                }
            }
        }
        // Breadcrumbs
        $bds = $this->breadcrumbs;
        $bds->addCrumb('Accueil', $this->getUrlFor('dash_home'));
        $bds->addCrumb('Clients (société)', $this->getUrlFor('dash_customer'));
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