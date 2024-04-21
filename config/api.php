<?php
declare(strict_types=1);

use Selmak\Proaxive2\Http\API\Middleware\ApiAuth;
use Selmak\Proaxive2\Http\API\V1\ApiCustomerController;
use Selmak\Proaxive2\Http\API\V1\Auth\ApiLoginController;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app){
    $app->post('/api/v1/login', [ApiLoginController::class, 'postApiLogin'])->setName('api_login');
    $app->group('/api/v1', function (RouteCollectorProxy $group) {
        $group->get('/customers', [ApiCustomerController::class, 'getAllCustomers'])->setName('api_customers_all');
        $group->get('/customers/{id:[0-9]+}', [ApiCustomerController::class, 'getCustomer'])->setName('api_customer_show');
    })->add(ApiAuth::class);
};