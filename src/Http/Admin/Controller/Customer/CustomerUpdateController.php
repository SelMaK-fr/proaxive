<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Admin\Controller\Customer;

use Envms\FluentPDO\Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator;
use Selmak\Proaxive2\Domain\Customer\Repository\CustomerRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Selmak\Proaxive2\Http\Type\Customer\CustomerParametersType;
use Selmak\Proaxive2\Http\Type\Customer\CustomerPasswordType;
use Selmak\Proaxive2\Http\Type\Customer\CustomerType;
use Slim\App;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class CustomerUpdateController extends AbstractController
{

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws ContainerExceptionInterface
     * @throws Exception
     * @throws NotFoundExceptionInterface
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function update(Request $request, Response $response, array $args): Response
    {
        $customer_id = (int)$args['id'];
        $customer = $this->getRepository(CustomerRepository::class)->find('id', $customer_id);
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest();
        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getRequestData()['form_customer'];
            $data['fullname'] = $data['firstname'] . ' ' . $data['lastname'];
            $save = $this->getRepository(CustomerRepository::class)->update($data, $customer_id);
            // To Do : refresh customer name of relation database (Service class ?)
            if($save){
                $this->addFlash('panel-info', 'Mise à jour effectuée.');
                return $this->redirectToReferer($request);
            }
        }
        // Breadcrumbs
        $bds = $this->breadcrumbs;
        $bds->addCrumb('Accueil', $this->getUrlFor('dash_home'));
        $bds->addCrumb('Clients', $this->getUrlFor('dash_customer'));
        $bds->addCrumb($this->sanitize($customer->fullname), false);
        $bds->addCrumb('Modification', false);
        $bds->render();
        // .Breadcrumbs
        return $this->render($response, 'backoffice/customer/update.html.twig', [
            'currentMenu' => 'customer',
            'customer' => $customer,
            'breadcrumbs' => $bds,
            'form' => $form
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws ContainerExceptionInterface
     * @throws Exception
     * @throws LoaderError
     * @throws NotFoundExceptionInterface
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function updateParameters(Request $request, Response $response, array $args): Response
    {
        $customer_id = (int)$args['id'];
        $customer = $this->getRepository(CustomerRepository::class)->find('id', $customer_id);
        $form = $this->createForm(CustomerParametersType::class, $customer);
        $form->handleRequest();
        $formPassword = $this->createForm(CustomerPasswordType::class);
        $formPassword->setAction($this->getUrlFor('customer_update_parameters_password', ['id' => $customer_id]));
        $formPassword->handleRequest();
        // Here, do not use isValid and isSubmit of FormBuilder
        if($request->getMethod() === 'POST'){
            $data = $request->getParsedBody()['form_customer_parameters'];
            $data['is_blacklisted'] = $data['is_blacklisted'] ?? null;
            $data['enable_portal'] = $data['enable_portal'] ?? null;
            if(empty($data['on_sale'])){$data['on_sale'] = null;}
            $save = $this->getRepository(CustomerRepository::class)->update($data, $customer_id);
            // To Do : refresh customer name of relation database (Service class ?)
            if($save){
                $this->addFlash('panel-info', 'Sauvegarde effectuée.');
                return $this->redirectToReferer($request);
            }
        }
        // Breadcrumbs
        $bds = $this->breadcrumbs;
        $bds->addCrumb('Accueil', $this->getUrlFor('dash_home'));
        $bds->addCrumb('Clients', $this->getUrlFor('dash_customer'));
        $bds->addCrumb($this->sanitize($customer->fullname), $this->getUrlFor('customer_read', ['id' => $customer->id]));
        $bds->addCrumb('Modification', $this->getUrlFor('customer_update', ['id' => $customer->id]));
        $bds->addCrumb('Paramètres', false);
        $bds->render();
        // .Breadcrumbs
        return $this->render($response, 'backoffice/customer/update_parameters.html.twig', [
            'currentMenu' => 'customer',
            'customer' => $customer,
            'form' => $form,
            'breadcrumbs' => $bds,
            'formPassword' => $formPassword
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws Exception
     */
    public function updateType(Request $request, Response $response, array $args): Response
    {
        $customer_id = (int)$args['id'];
        $customer = $this->getRepository(CustomerRepository::class)->find('id', $customer_id);
        if($request->getMethod() === 'POST'){
            $data = $request->getParsedBody();
            if(!is_null($customer->is_society))
            {
                $data['is_society'] = null;
            } else {
                $data['is_society'] = 1;
            }
            $save = $this->getRepository(CustomerRepository::class)->update($data, $customer_id);
            if($save){
                $this->addFlash('panel-info', 'Paramètres "Type" mis à jour');
                return $this->redirectToReferer($request);
            }
        }
        return $this->redirectToReferer($request);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws Exception
     */
    public function updatePortalPassword(Request $request, Response $response, array $args): Response
    {
        if($request->getMethod() === 'POST') {
            $customer_id = (int)$args['id'];
            $data = $request->getParsedBody()['form_customer_password'];
            $validator = $this->validator->validate($data, [
                'passwd' => [
                    'rules' => Validator::length(6,20)
                ],
                'passwd_2' => [
                    'rules' => Validator::equals($data['passwd'])->length(6,20)
                ]
            ]);
            if($validator->count() === 0) {
                unset($data['passwd_2']);
                $passHash = password_hash($data['passwd'], PASSWORD_BCRYPT);
                $data['passwd'] = $passHash;
                $save = $this->getRepository(CustomerRepository::class)->update($data, $customer_id);
                if($save){
                    $this->addFlash('panel-info', sprintf('Le mot de passe pour ID %s a été enregistré', $customer_id));
                    return $this->redirectToReferer($request);
                }
            } else {
                foreach ($validator as $v){
                    $this->addFlash('panel-error', sprintf('%s', $v->getMessage()));
                }
                return $this->redirectToReferer($request);
            }
        }
        return $this->redirectToReferer($request);
    }
}