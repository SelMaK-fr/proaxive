<?php
declare(strict_types=1);
namespace App\Controller\Frontoffice\Portal;

use App\AbstractController;
use App\Repository\CustomerRepository;
use App\Repository\EquipmentRepository;
use Envms\FluentPDO\Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PortalController extends AbstractController
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
        $customer = $this->getRepository(CustomerRepository::class)->find('id', $this->getSession('customer')['id']);
        $e = $this->getRepository(EquipmentRepository::class)->allBy('customers_id', $customer->id);

        return $this->render($response, '/frontoffice/portal/index.html.twig', [
            'customer' => $customer,
            'e' => $e
        ]);
    }
}