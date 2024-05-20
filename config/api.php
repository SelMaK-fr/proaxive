<?php
declare(strict_types=1);

use Selmak\Proaxive2\Http\API\Middleware\ApiAuth;
use Selmak\Proaxive2\Http\API\V1\ApiInterventionController;
use Selmak\Proaxive2\Http\API\V1\Auth\ApiLoginController;
use Selmak\Proaxive2\Http\API\V1\Customer\ApiGetAll;
use Selmak\Proaxive2\Http\API\V1\Customer\ApiGetOne;
use Selmak\Proaxive2\Http\API\V1\Statistic\ApiGetIntervention;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app){
    $app->post('/api/v1/login', [ApiLoginController::class, 'postApiLogin'])->setName('api_login');
    $app->group('/api/v1/interventions/', function (RouteCollectorProxy $group){
        $group->get('status', [ApiInterventionController::class, 'interventionStatus'])->setName('api_interventions_status');
        $group->get('stats', ApiGetIntervention::class)->setName('api_stats_interventions');
    });
    $app->group('/api/v1/customers/', function (RouteCollectorProxy $group) {
        $group->get('', ApiGetAll::class)->setName('api_customer_all');
        $group->get('{id:[0-9]+}', ApiGetOne::class)->setName('api_customer_show');
    })->add(ApiAuth::class);
};