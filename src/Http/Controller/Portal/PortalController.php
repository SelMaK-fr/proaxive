<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Controller\Portal;

use Envms\FluentPDO\Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\Customer\Repository\CustomerRepository;
use Selmak\Proaxive2\Domain\Equipment\Repository\EquipmentRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class PortalController extends AbstractController
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
    public function index(Request $request, Response $response): Response
    {
        $customer = $this->getRepository(CustomerRepository::class)->find('id', $this->getSession('customer')['id']);
        $e = $this->getRepository(EquipmentRepository::class)->allBy('customers_id', $customer->id);

        return $this->render($response, '/frontoffice/portal/index.html.twig', [
            'customer' => $customer,
            'e' => $e
        ]);
    }
}