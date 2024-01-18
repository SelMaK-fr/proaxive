<?php
declare(strict_types=1);
namespace App\Controller\Backoffice;

use App\AbstractController;
use App\Repository\CustomerRepository;
use App\Service\GeneratePdfService;
use Knp\Snappy\Pdf;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class IndexController extends AbstractController
{

    public function index(Request $request, Response $response): Response
    {
        $customers = $this->getRepository(CustomerRepository::class)->all()->limit(5);
        return $this->render($response, 'backoffice/index.html.twig', [
            'customers' => $customers,
            'currentMenu' => 'home'
        ]);
    }
}