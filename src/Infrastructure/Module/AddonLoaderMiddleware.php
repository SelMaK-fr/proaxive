<?php
declare(strict_types=1);

namespace Selmak\Proaxive2\Infrastructure\Module;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Selmak\Proaxive2\Settings\SettingsInterface;
use Slim\App;
use Slim\Views\Twig;

class AddonLoaderMiddleware implements MiddlewareInterface
{

    public function __construct(private readonly App $app)
    {
    }
    public function process(Request $request, RequestHandler $handler): ResponseInterface
    {
        $modules = (require $this->app->getContainer()->get(SettingsInterface::class)->get('settings')['rootPath'] . '/config/addon.php');
        // Vérifier si des addons existent
        if ($modules && is_array($modules) && count($modules) > 0) {
            foreach ($modules as $moduleName => $modulePath) {
                // Vérifier l'architecture des fichiers/dossier
                $addonDirectory = $this->app->getContainer()->get(SettingsInterface::class)->get('settings')['rootPath'] . '/src/Addon/' . $moduleName;
                // Vérifier si la classe de configuration Addon.php existe
                $path = $this->app->getContainer()->get(SettingsInterface::class)->get('settings')['rootPath'];
                $addonClassFile = $addonDirectory . '/'.$moduleName.'Module.php';

                if (file_exists($addonClassFile)) {

                    // Charger la classe de configuration Addon.php
                    (require_once $addonClassFile);
                    $addonClass = '\\Selmak\\Proaxive2\\Addon\\' . basename($moduleName) . '\\' . $moduleName.'Module';

                    if (class_exists($addonClass)) {
                        // Si la classe existe, instancier
                        /* @var \Selmak\Proaxive2\Addon\Rental\Module $addonInstance */
                        $addonInstance = new $addonClass($this->app, $this->app->getContainer()->get(Twig::class));
                        // Vérifier si la méthode initRoute existe dans la classe
                        if (method_exists($addonInstance, 'initRoute')) {
                            // Appeler la méthode initRoute pour gérer les routes de l'addon
                            $addonInstance->initRoute();
                        }
                        if (method_exists($addonInstance, 'initMenu')) {
                            // Appeler la méthode initMenu pour ajouter l'item au menu
                            $addonInstance->initMenu();
                        }
                        // Vous pouvez également effectuer d'autres tâches avec l'addon si nécessaire
                    }
                }
            }
        }

        return $handler->handle($request);
    }

}