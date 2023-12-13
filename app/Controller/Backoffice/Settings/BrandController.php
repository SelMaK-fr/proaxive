<?php
declare(strict_types=1);
namespace App\Controller\Backoffice\Settings;

use App\AbstractController;
use App\Repository\BrandRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class BrandController extends AbstractController
{

    public function index(Request $request, Response $response): Response
    {
        $brands = $this->getRepository(BrandRepository::class)->all();

    }
}