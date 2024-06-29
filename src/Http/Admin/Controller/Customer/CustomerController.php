<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Admin\Controller\Customer;

use Envms\FluentPDO\Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\Customer\Repository\CustomerRepository;
use Selmak\Proaxive2\Domain\Equipment\Repository\EquipmentRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Selmak\Proaxive2\Infrastructure\Paginator\Paginator;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class CustomerController extends AbstractController
{

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws Exception
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function index(Request $request, Response $response, array $args): Response
    {
        if($request->getQueryParams()['view'] === 'list'){
            $template = 'backoffice/customer/list.html.twig';
            $paginator = new Paginator('15', 'p', $request);
            $paginator->set_total($this->getRepository(CustomerRepository::class)->count());
            $customers = $this->getRepository(CustomerRepository::class)->allArrayForPaginator($paginator->get_limit());
            $dataPaginate = $paginator->page_links();
        } else {
            $template = 'backoffice/customer/index.html.twig';
            $dataPaginate = [];
            $customers = $this->getRepository(CustomerRepository::class)->all()->orderBy('created_at DESC')->limit(16);
        }
        // Breadcrumbs
        $bds = $this->breadcrumbs;
        $bds->addCrumb('Accueil', $this->getUrlFor('dash_home'));
        $bds->addCrumb('Clients', false);
        $bds->render();
        // .Breadcrumbs

        return $this->render($response, $template, [
            'customers' => $customers,
            'breadcrumbs' => $bds,
            'dataPaginate' => $dataPaginate,
            'currentMenu' => 'customer'
        ]);
    }
}