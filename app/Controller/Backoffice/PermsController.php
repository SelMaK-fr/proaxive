<?php
declare(strict_types=1);
namespace App\Controller\Backoffice;

use App\AbstractController;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;

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