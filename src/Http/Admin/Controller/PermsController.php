<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Admin\Controller;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Selmak\Proaxive2\Http\Controller\AbstractController;

class PermsController extends AbstractController
{

    /**
     * @param $request
     * @param $response
     * @return ResponseInterface
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function not_perms($request, $response)
    {
        return $this->render($response, 'backoffice/perms/perms.html.twig');
    }

    /**
     * @param $request
     * @param $response
     * @return ResponseInterface
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function not_perms_tech($request, $response)
    {
        return $this->render($response, 'backoffice/perms/perms_tech.html.twig');
    }
}