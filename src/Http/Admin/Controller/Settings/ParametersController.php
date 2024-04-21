<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Admin\Controller\Settings;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Selmak\Proaxive2\Http\Type\ParametersType;
use Selmak\Proaxive2\Infrastructure\Security\SerialNumberFormatterService;

class ParametersController extends AbstractController
{

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function parameters(Request $request, Response $response): Response
    {
        $s = $this->app->getContainer()->get('parameters');
        $numberFormatter = new SerialNumberFormatterService($s);
        $getNumber = $numberFormatter->generateSerialNumber();

        $form = $this->createForm(ParametersType::class, $s);
        $form->handleRequest();

        if($form->isSubmitted() && $form->isValid()) {
            $data = $form->getRequestData()['form_parameters'];
            $data['api_nominatim'] = $data['api_nominatim'] ?? 0;
            $data['api_address'] = $data['api_address'] ?? 0;
            $data['php_error'] = $data['php_error'] ?? 0;
            $data['full_error'] = $data['full_error'] ?? 0;
            $file = fopen(dirname(__DIR__, 4).'/config/parameters.json', 'w');
            fwrite($file, json_encode($data));
            //file_put_contents(dirname(__DIR__, 4).'/config/parameters.json', json_encode($data));
            return $this->redirectToReferer($request);
        }
        // Breadcrumbs
        $bds = $this->app->getContainer()->get('breadcrumbs');
        $bds->addCrumb('Accueil', $this->getUrlFor('dash_home'));
        $bds->addCrumb('Paramètres', false);
        $bds->addCrumb('Préférences', false);
        $bds->render();
        // .Breadcrumbs
        return $this->render($response, 'backoffice/settings/preference/index.html.twig', [
            'setting_current' => 'preference',
            'form' => $form,
            'breadcrumbs' => $bds,
            'getNumber' => $getNumber
        ]);
    }
}