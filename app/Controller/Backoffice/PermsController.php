<?php
declare(strict_types=1);
namespace App\Controller\Backoffice;

use App\AbstractController;

class PermsController extends AbstractController
{

    /**
     * @param $request
     * @param $response
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function not_perms($request, $response)
    {
        return $this->render($response, 'backoffice/perms/perms.html.twig');
    }

    /**
     * @param $request
     * @param $response
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function not_perms_tech($request, $response)
    {
        return $this->render($response, 'backoffice/perms/perms_tech.html.twig');
    }
}