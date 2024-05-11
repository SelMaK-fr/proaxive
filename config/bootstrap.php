<?php
declare(strict_types=1);
use DI\ContainerBuilder;
use Slim\App;

require_once __DIR__ . '/../vendor/autoload.php';
Dotenv\Dotenv::createImmutable(dirname(__DIR__))->load();
$modules = (require dirname(__DIR__) . '/config/addon.php');
// Build DI container instance
try {
    $containerBuilder = (new ContainerBuilder());
    $containerBuilder->addDefinitions(__DIR__ . '/definitions.php');
    foreach ($modules as $module) {
        if($module::DEFINITIONS) {
            $containerBuilder->addDefinitions($module::DEFINITIONS);
        }
    }
    $containerBuilder->useAutowiring(true);
    $container = $containerBuilder->build();
} catch (Exception $e) {
    return $e->getMessage();
}
// APP to Container
$app = $container->get(App::class);
// Routing
(require dirname(__DIR__) . '/config/web.php')($app);
// Routing for API
(require dirname(__DIR__) . '/config/api.php')($app);
// Middleware
(require dirname(__DIR__) . '/config/middleware.php')($app);
// Create App instance
return $app;