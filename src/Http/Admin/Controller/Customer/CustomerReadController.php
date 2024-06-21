<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Admin\Controller\Customer;

use Envms\FluentPDO\Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\Customer\Repository\CustomerRepository;
use Selmak\Proaxive2\Domain\EDocument\Repository\EDocumentRepository;
use Selmak\Proaxive2\Domain\Equipment\Repository\EquipmentRepository;
use Selmak\Proaxive2\Domain\Intervention\Repository\InterventionRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class CustomerReadController extends AbstractController
{

    /**
     * @param Request $request
     * @param Response $response
     * @param array $arg
     * @return Response
     * @throws Exception
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function read(Request $request, Response $response, array $arg): Response
    {
        $customer_id = (int)$arg['id'];
        $customer = $this->getRepository(CustomerRepository::class)->find('id', $customer_id);
        $stats = $this->getRepository(CustomerRepository::class)->statsChartsProfil($customer_id);

        // Breadcrumbs
        $bds = $this->breadcrumbs;
        $bds->addCrumb('Accueil', $this->getUrlFor('dash_home'));
        $bds->addCrumb('Clients', $this->getUrlFor('dash_customer'));
        $bds->addCrumb($customer->fullname, false);
        $bds->render();
        // .Breadcrumbs
        return $this->render($response, 'backoffice/customer/read.html.twig', [
            'customer' => $customer,
            'breadcrumbs' => $bds,
            'stats' => $stats,
            'currentMenu' => 'customer'
        ]);
    }
}