<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Controller\Portal;

use Envms\FluentPDO\Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\Customer\Repository\CustomerRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Selmak\Proaxive2\Http\Type\Customer\CustomerPortalAddressType;
use Selmak\Proaxive2\Http\Type\Customer\CustomerPortalType;

class PortalParameterController extends AbstractController
{

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws Exception
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function index(Request $request, Response $response): Response
    {
        $c = $this->getRepository(CustomerRepository::class)->find('id', $this->getSession('customer')['id']);

        $form = $this->createForm(CustomerPortalType::class, $c);
        $form->handleRequest();

        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getRequestData()['form_customer'];
            $save = $this->getRepository(CustomerRepository::class)->update($data, $this->getSession('customer')['id']);
            if($save){
                $this->addFlash('success', 'Votre compte a bien été mis à jour.');
            }
        }

        return $this->render($response, '/frontoffice/portal/parameter/index.html.twig', [
            'customer' => $c,
            'form' => $form
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
    public function address(Request $request, Response $response): Response
    {
        $c = $this->getRepository(CustomerRepository::class)->find('id', $this->getSession('customer')['id']);
        $form = $this->createForm(CustomerPortalAddressType::class, $c);
        $form->handleRequest();
        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getRequestData()['form_customer'];
            $save = $this->getRepository(CustomerRepository::class)->update($data, $this->getSession('customer')['id']);
            if($save){
                $this->addFlash('success', 'Votre adresse a bien été mise à jour.');
            }
        }

        return $this->render($response, '/frontoffice/portal/parameter/address.html.twig', [
            'form' => $form
        ]);
    }
}