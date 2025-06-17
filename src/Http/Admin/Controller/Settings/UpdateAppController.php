<?php
declare(strict_types=1);

namespace Selmak\Proaxive2\Http\Admin\Controller\Settings;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\Database\Repository\PhinxLogRepository;
use Selmak\Proaxive2\Domain\Settings\Repository\SettingRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Slim\Exception\HttpBadRequestException;


class UpdateAppController extends AbstractController
{
    public function __invoke(Request $request, Response $response): Response
    {
        $lastUpdate = $this->getRepository(PhinxLogRepository::class)->getLastUpdate();

        $setting = $this->getRepository(SettingRepository::class)->find('id', 1);
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
            'setting' => $setting,
            'versionApp' => $this->settings->get('app')['version'],
            'lastUpdate' =>  $lastUpdate,
            'isExecEnabled' => self::isExecEnabled(),
        ]);
    }

    /**
     * Update database
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function migrate(Request $request, Response $response): Response
    {
        if($request->getMethod() === 'POST') {
            $setting = $this->getRepository(SettingRepository::class)->find('id', 1);
            // View PHP version
            $command = 'cd ' . escapeshellarg($this->getParameter('settings', 'rootPath')) . ' && php -v ';
            // Launch migrate
            $command .= '&& make migrate';
            $command .= ' 2>&1';
            $output = [];
            exec($command, $output, $returnVar);

            if ($returnVar === 0) {
                $message = 'Exécution des migrations en cours... ';
            } else {
                $message = 'Erreur lors de l\'exécution : ';
            }

            return $this->render($response, 'backoffice/settings/update/return_command.html.twig', [
                'currentMenu' => 'settings',
                'setting_current' => 'update',
                'setting' => $setting,
                'versionApp' => $this->settings->get('app')['version'],
                'output' => $output,
                'message' => $message
            ]);
        }
        return new \Slim\Psr7\Response();
    }

    public function updateApp(Request $request, Response $response): Response
    {
        if($request->getMethod() === 'POST'){
            $setting = $this->getRepository(SettingRepository::class)->find('id', 1);
            $data = $request->getParsedBody()['form_app_command'];

            if (!is_dir($this->getParameter('settings', 'rootPath') . '/.git')) {
                throw new HttpBadRequestException($request, 'Ce dossier ne contient aucun dépôt Git valide !');
            }
            $command = 'cd ' . escapeshellarg($this->getParameter('settings', 'rootPath')) . ' && php -v ';
            $command .= '&& git pull';
            // Check if require is checked, if yes, run "make update-dep"
            if(isset($data['require'])){
                $command .= '&& make update-dep';
            }
            // Check if require is checked, if yes, run "make migrate"
            if(isset($data['migrate'])){
                $command .= '&& make migrate';
            }
            $command .= ' 2>&1';

            $output = [];
            exec($command, $output, $returnVar);

            if ($returnVar === 0) {
                $message = 'Exécution des commandes en cours... ';
            } else {
                $message = 'Erreur lors de l\'exécution : ';
            }
            return $this->render($response, 'backoffice/settings/update/return_command.html.twig', [
                'currentMenu' => 'settings',
                'setting_current' => 'update',
                'setting' => $setting,
                'versionApp' => $this->settings->get('app')['version'],
                'output' => $output,
                'message' => $message
            ]);
        }
        return new \Slim\Psr7\Response();
    }

    private function isExecEnabled(): bool
    {
        // Vérifie si 'exec' est dans la liste des fonctions désactivées
        $disabled = explode(',', ini_get('disable_functions'));
        $disabled = array_map('trim', $disabled);

        return !in_array('exec', $disabled);
    }
}