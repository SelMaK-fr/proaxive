<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Admin\Controller\Settings;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\Parameter\ParameterDTO;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Selmak\Proaxive2\Http\Type\Admin\ParametersType;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class ParametersController extends AbstractController
{

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function parameters(Request $request, Response $response): Response
    {
        $s = $this->parameter->getParams();
        $form = $this->createForm(ParametersType::class, $s);
        $form->handleRequest();

        if($form->isSubmitted() && $form->isValid()) {

            $data = $form->getRequestData()['form_parameters'];
            $data['api_nominatim'] = $data['api_nominatim'] ?? 0;
            $data['api_address'] = $data['api_address'] ?? 0;
            $data['php_error'] = $data['php_error'] ?? 0;
            $data['full_error'] = $data['full_error'] ?? 0;
            $data['mail_auto'] = $data['mail_auto'] ?? 0;
            $this->addFlash('panel-info', "Paramètres mis à jour avec succès.");
            $this->parameter->save($data);
            return $this->redirectToReferer($request);
        }
        // Breadcrumbs
        $bds = $this->breadcrumbs;
        $bds->addCrumb('Accueil', $this->getUrlFor('dash_home'));
        $bds->addCrumb('Paramètres', false);
        $bds->addCrumb('Préférences', false);
        $bds->render();
        // .Breadcrumbs
        return $this->render($response, 'backoffice/settings/preference/index.html.twig', [
            'setting_current' => 'preference',
            'form' => $form,
            'breadcrumbs' => $bds
        ]);
    }
}