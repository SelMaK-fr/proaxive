<?php
declare(strict_types=1);
namespace App\Controller\Backoffice\Settings;

use App\AbstractController;
use App\Type\ParametersType;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Service\SerialNumberFormatterService;

class ParametersController extends AbstractController
{

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

        return $this->render($response, 'backoffice/settings/preference/index.html.twig', [
            'setting_current' => 'preference',
            'form' => $form,
            'getNumber' => $getNumber
        ]);
    }
}