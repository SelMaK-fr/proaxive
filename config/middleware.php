<?php
declare(strict_types=1);

use App\Middleware\Auth\RedirectIfNotAuthMiddleware;
use App\Middleware\Auth\RegenSessionIfCookieExistMiddleware;
use DI\NotFoundException;
use Odan\Session\Middleware\SessionStartMiddleware;
use Selmak\Proaxive2\Middleware\RespectValidationMiddleware;
use Selmak\Proaxive2\Middleware\SessionMiddleware;
use Selmak\Proaxive2\Middleware\TwigFlashMiddleware;
use Slim\App;
use Slim\Middleware\MethodOverrideMiddleware;
use Slim\Views\TwigMiddleware;

return function (App $app) {
    if (!($container = $app->getContainer())) {
        throw new NotFoundException('Could not get the container.');
    }
    // Parse json, form data and xml
    $app->addBodyParsingMiddleware();
    $app->add(TwigFlashMiddleware::class);
    $app->add(TwigMiddleware::createFromContainer($app));
    // Add the Slim built-in routing middleware
    $app->addRoutingMiddleware();
    $app->add(new MethodOverrideMiddleware());
    // Respect Validator
    $app->add(RespectValidationMiddleware::class);
    //$app->add($container->get('csrf'));
    $app->add(RedirectIfNotAuthMiddleware::class);
    $app->add(RegenSessionIfCookieExistMiddleware::class);
    $app->addErrorMiddleware(true,true,true, $container->get('logger'));
    // Session by Odan for Slim
    $app->add(SessionStartMiddleware::class);
};