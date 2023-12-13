<?php
declare(strict_types=1);
namespace App\Controller\Backoffice\Customer;

use App\AbstractController;
use App\Repository\CustomerRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CustomerController extends AbstractController
{

    public function index(Request $request, Response $response): Response
    {
        $customers = $this->getRepository(CustomerRepository::class)->all()->orderBy('created_at DESC')->limit(16);

        return $this->render($response, 'backoffice/customer/index.html.twig', [
            'customers' => $customers,
            'currentMenu' => 'customer'
        ]);
    }
}