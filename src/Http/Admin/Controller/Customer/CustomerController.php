<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Admin\Controller\Customer;

use Envms\FluentPDO\Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\Customer\Repository\CustomerRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;

class CustomerController extends AbstractController
{

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     * @throws Exception
     */
    public function index(Request $request, Response $response): Response
    {
        $customers = $this->getRepository(CustomerRepository::class)->all()->orderBy('created_at DESC')->limit(16);
        // Breadcrumbs
        $bds = $this->app->getContainer()->get('breadcrumbs');
        $bds->addCrumb('Accueil', $this->getUrlFor('dash_home'));
        $bds->addCrumb('Clients', false);
        $bds->render();
        // .Breadcrumbs

        return $this->render($response, 'backoffice/customer/index.html.twig', [
            'customers' => $customers,
            'breadcrumbs' => $bds,
            'currentMenu' => 'customer'
        ]);
    }
}