<?php
declare(strict_types=1);
namespace App\Controller\Frontoffice\Portal;

use App\AbstractController;
use App\Repository\CustomerRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PortalParameterController extends AbstractController
{

    public function index(Request $request, Response $response): Response
    {
        $c = $this->getRepository(CustomerRepository::class)->find('id', $this->session->get('customer')['id']);

        return $this->render($response, '/frontoffice/portal/parameter/index.html.twig', [
            'customer' => $c
        ]);
    }

    public function address(Request $request, Response $response): Response
    {
        return $this->render($response, '/frontoffice/portal/parameter/address.html.twig');
    }
}