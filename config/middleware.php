<?php
declare(strict_types=1);

use DI\NotFoundException;
use Odan\Session\Middleware\SessionStartMiddleware;
use Selmak\Proaxive2\Domain\Application\Middleware\RespectValidationMiddleware;
use Selmak\Proaxive2\Domain\Application\Middleware\TwigFlashMiddleware;
use Selmak\Proaxive2\Domain\Auth\Middleware\RedirectIfNotAuthMiddleware;
use Selmak\Proaxive2\Domain\Auth\Middleware\RegenSessionIfCookieExistMiddleware;
use Selmak\Proaxive2\Infrastructure\Module\AddonLoaderMiddleware;
use Selmak\Proaxive2\Settings\SettingsInterface;
use Slim\App;
use Slim\Middleware\MethodOverrideMiddleware;
use Slim\Views\TwigMiddleware;

return function (App $app) {
    if (!($container = $app->getContainer())) {
        throw new NotFoundException('Could not get the container.');
    }
    // Parse json, form data and xml
    $app->addBodyParsingMiddleware();
    // Twig flash message (success, error etc.)
    $app->add(TwigFlashMiddleware::class);
    $app->add(TwigMiddleware::createFromContainer($app));
    // Add the Slim built-in routing middleware
    $app->addRoutingMiddleware();
    $app->add(new MethodOverrideMiddleware());
    // Launch Modules
    $app->add(new AddonLoaderMiddleware($app));
    // Respect Validator
    $app->add(RespectValidationMiddleware::class);
    //$app->add($container->get('csrf'));
    $app->add(RedirectIfNotAuthMiddleware::class);
    $app->add(RegenSessionIfCookieExistMiddleware::class);
    $app->addErrorMiddleware(true,true,true, $container->get('logger'));
    // Session by Odan for Slim
    $app->add(SessionStartMiddleware::class);
};