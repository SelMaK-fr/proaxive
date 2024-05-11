<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Admin\Controller;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class PermsController extends AbstractController
{

    /**
     * @param $request
     * @param $response
     * @return ResponseInterface
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function not_perms($request, $response): ResponseInterface
    {
        return $this->render($response, 'backoffice/perms/perms.html.twig');
    }

    /**
     * @param $request
     * @param $response
     * @return ResponseInterface
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function not_perms_tech($request, $response): ResponseInterface
    {
        return $this->render($response, 'backoffice/perms/perms_tech.html.twig');
    }
}