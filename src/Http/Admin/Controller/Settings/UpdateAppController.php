<?php
declare(strict_types=1);

namespace Selmak\Proaxive2\Http\Admin\Controller\Settings;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use SensioLabs\AnsiConverter\AnsiToHtmlConverter;
use SensioLabs\AnsiConverter\Theme\SolarizedXTermTheme;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class UpdateAppController extends AbstractController
{
    public function __invoke(Request $request, Response $response): Response
    {
        if($request->getMethod() === 'POST'){
            $data = $request->getParsedBody()['form_app_command'];
            if (!is_dir($this->getParameter('settings', 'rootPath') . '/.git')) {
                throw new HttpBadRequestException($request, 'Ce dossier ne contient aucun dépôt Git valide !');
            }
            $command = 'cd ' . escapeshellarg($this->getParameter('settings', 'rootPath')) . ' && php -v ';
            // Check if require is checked, if yes, run "make update-dep"
            if(isset($data['require'])){
                $command .= '&& git status';
            }
            // Check if require is checked, if yes, run "make migrate"
            if(isset($data['migrate'])){
                $command .= '&& ls';
            }
            $command .= ' 2>&1';

            $output = [];
            exec($command, $output, $returnVar);

            if ($returnVar === 0) {
                $message = 'Exécution des commandes en cours... ';
            } else {
                $message = 'Erreur lors de l\'exécution : ';
            }
        }
        //
        $breadcrumbs = $this->breadcrumbs;
        $breadcrumbs->addCrumb('Accueil', $this->getUrlFor('dash_home'));
        $breadcrumbs->addCrumb('Paramètres', false);
        $breadcrumbs->addCrumb('Mise à jour', false);
        $breadcrumbs->render();
        //
        return $this->render($response, 'backoffice/settings/update/index.html.twig', [
            'currentMenu' => 'settings',
            'setting_current' => 'update',
            'breadcrumbs' => $breadcrumbs,
            'output' => $output,
            'message' => $message
        ]);
    }
}