<?php

use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;
use Selmak\Proaxive2\Command\ImportCustomerCommand;
use Selmak\Proaxive2\Command\ImportFromProaxiveV1Command;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;

require_once __DIR__ . '/../vendor/autoload.php';

Dotenv\Dotenv::createImmutable(dirname(__DIR__))->load();

$env = (new ArgvInput())->getParameterOption(['--env', '-e'], 'dev');

if ($env) {
    $_ENV['APP_ENV'] = $env;
}

/** @var ContainerInterface $container */
$container = (new ContainerBuilder())
    ->addDefinitions(__DIR__ . '/../config/definitions.php')
    ->build();

try {
    /** @var Application $application */
    $application = $container->get(Application::class);

    // Import Customers
    $application->add($container->get(ImportFromProaxiveV1Command::class));

    exit($application->run());
} catch (Throwable $exception) {
    echo $exception->getMessage();
    exit(1);
}