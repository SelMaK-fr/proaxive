<?php
declare(strict_types=1);
namespace App\Controller\Backoffice;

use App\AbstractController;
use App\Type\AccountType;
use App\Type\TestForm;
use App\Repository\UserRepository;
use App\UserEntity;
use Dflydev\FigCookies\FigRequestCookies;
use Dflydev\FigCookies\FigResponseCookies;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\FormBuilder\BuilderInterface;
use Selmak\Proaxive2\FormBuilder\TemplateForm;
use function DI\add;

class IndexController extends AbstractController
{

    public function index(Request $request, Response $response): Response
    {
        return $this->render($response, 'backoffice/index.html.twig');
    }
}