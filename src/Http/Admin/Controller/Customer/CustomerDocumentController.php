<?php
declare(strict_types=1);

namespace Selmak\Proaxive2\Http\Admin\Controller\Customer;

use Selmak\Proaxive2\Domain\Customer\Repository\CustomerRepository;
use Selmak\Proaxive2\Domain\EDocument\Repository\EDocumentRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CustomerDocumentController extends AbstractController
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $customer_id = (int)$args['id'];
        $customer = $this->getRepository(CustomerRepository::class)->find('id', $customer_id);
        $documents = $this->getRepository(EDocumentRepository::class)->allWithIntervention($customer_id);
        // Breadcrumbs
        $bds = $this->breadcrumbs;
        $bds->addCrumb('Accueil', $this->getUrlFor('dash_home'));
        $bds->addCrumb('Clients', $this->getUrlFor('dash_customer'));
        $bds->addCrumb($customer->fullname, false);
        $bds->addCrumb('Documents', false);
        $bds->render();
        // .Breadcrumbs
        return $this->render($response, 'backoffice/customer/documents.html.twig', [
            'customer' => $customer,
            'documents' => $documents,
            'breadcrumbs' => $bds,
            'currentMenu' => 'customer'
        ]);
    }
}