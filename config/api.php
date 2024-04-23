<?php
declare(strict_types=1);

use Selmak\Proaxive2\Http\API\Middleware\ApiAuth;
use Selmak\Proaxive2\Http\API\V1\Auth\ApiLoginController;
use Selmak\Proaxive2\Http\API\V1\Customer\ApiGetAll;
use Selmak\Proaxive2\Http\API\V1\Customer\ApiGetOne;
use Selmak\Proaxive2\Http\API\V1\Statistic\ApiGetIntervention;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app){
    $app->post('/api/v1/login', [ApiLoginController::class, 'postApiLogin'])->setName('api_login');
    $app->get('/api/v1/stats/interventions', ApiGetIntervention::class)->setName('api_stats_interventions');
    $app->group('/api/v1', function (RouteCollectorProxy $group) {
        $group->get('/customers/{id:[0-9]+}', ApiGetOne::class)->setName('api_customer_show');
        $group->get('/customers', ApiGetAll::class)->setName('api_customer_all');
    })->add(ApiAuth::class);
};