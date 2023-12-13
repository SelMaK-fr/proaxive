<?php
declare(strict_types=1);
use DI\ContainerBuilder;
use Slim\App;

require_once __DIR__ . '/../vendor/autoload.php';
Dotenv\Dotenv::createImmutable(dirname(__DIR__))->load();
// Build DI container instance
$container = (new ContainerBuilder())
    ->addDefinitions(__DIR__ . '/definitions.php')
    ->useAutowiring(true)
    ->build();
$app = $container->get(App::class);
(require dirname(__DIR__) . '/config/web.php')($app);

(require dirname(__DIR__) . '/config/middleware.php')($app);
// Create App instance
return $app;