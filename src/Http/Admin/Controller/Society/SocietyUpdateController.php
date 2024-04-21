<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Admin\Controller\Society;

use Envms\FluentPDO\Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\Customer\Repository\CustomerRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Selmak\Proaxive2\Http\Type\SocietyType;

class SocietyUpdateController extends AbstractController
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
                $this->addFlash('panel-info', "Mise à jour effectuée.");
            }
        }
        // Breadcrumbs
        $bds = $this->app->getContainer()->get('breadcrumbs');
        $bds->addCrumb('Accueil', $this->getUrlFor('dash_home'));
        $bds->addCrumb('Client (société)', $this->getUrlFor('dash_customer'));
        $bds->addCrumb($customer->fullname, $this->getUrlFor('customer_read', ['id' => $customer->id]));
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