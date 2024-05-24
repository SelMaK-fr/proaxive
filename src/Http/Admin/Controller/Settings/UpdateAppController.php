<?php
declare(strict_types=1);

namespace Selmak\Proaxive2\Http\Admin\Controller\Settings;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use SensioLabs\AnsiConverter\AnsiToHtmlConverter;
use SensioLabs\AnsiConverter\Theme\SolarizedXTermTheme;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class UpdateAppController extends AbstractController
{
    public function __invoke(Request $request, Response $response): Response
    {
        $data = 'En attente...';
        if($request->getMethod() === 'POST'){
            $command = 'cd ' . escapeshellarg($this->getParameter('settings', 'rootPath')) . ' && git status 2>&1';
            $returnVar = 0;
            $output = [];
            exec($command, $output, $returnVar);
            if ($returnVar === 0) {
                $message = 'Git pull exécuté avec succès: ';
            } else {
                $message = 'Erreur lors de l\'exécution du git pull: ';
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