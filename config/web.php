<?php
declare(strict_types=1);

use App\Controller\Account\UserAccountController;
use App\Controller\Backoffice\Customer\CustomerAjaxController;
use App\Controller\Backoffice\Customer\CustomerController;
use App\Controller\Backoffice\Customer\CustomerCreateController;
use App\Controller\Backoffice\Customer\CustomerDeleteController;
use App\Controller\Backoffice\Customer\CustomerReadController;
use App\Controller\Backoffice\Customer\CustomerUpdateController;
use App\Controller\Backoffice\Equipment\EquipmentAjaxController;
use App\Controller\Backoffice\Equipment\EquipmentController;
use App\Controller\Backoffice\Equipment\EquipmentCreateController;
use App\Controller\Backoffice\Equipment\EquipmentReadController;
use App\Controller\Backoffice\Equipment\EquipmentUpdateController;
use App\Controller\Backoffice\Equipment\PeripheralDevice\PeripheralCreateController;
use App\Controller\Backoffice\Equipment\PeripheralDevice\PeripheralUpdateController;
use App\Controller\Backoffice\Intervention\InterventionAjaxController;
use App\Controller\Backoffice\Intervention\InterventionController;
use App\Controller\Backoffice\Intervention\InterventionCreateController;
use App\Controller\Backoffice\Intervention\InterventionDeleteController;
use App\Controller\Backoffice\Intervention\InterventionReadController;
use App\Controller\Backoffice\Intervention\InterventionUpdateController;
use App\Controller\Backoffice\Intervention\InterventionValidatedController;
use App\Controller\Backoffice\Settings\ParametersController;
use App\Controller\Backoffice\Society\SocietyUpdateController;
use App\Controller\Backoffice\Task\AddTaskToInterventionController;
use App\Controller\Backoffice\Task\DeleteTaskOfInterventionController;
use App\Controller\Backoffice\User\UserActionController;
use App\Controller\Backoffice\User\UserController;
use App\Controller\Backoffice\User\UserReadController;
use App\Controller\Backoffice\Workshop\WorkshopActionController;
use App\Controller\Backoffice\Workshop\WorkshopController;
use App\Controller\Frontoffice\IndexController;
use App\Controller\Frontoffice\Intervention\InterventionReadController as FrontInterventionRead;
use App\Controller\Frontoffice\Intervention\InterventionSearchController as FrontInterventionSearch;
use App\Controller\Backoffice\Settings\TaskController as SettingTask;
use App\Controller\Frontoffice\Portal\LoginController;
use App\Controller\Frontoffice\Portal\PortalController;
use App\Middleware\IfUpdateSocietyMiddleware;
use App\Middleware\Intervention\IfDarftMiddleware;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    $app->get('/', IndexController::class)->setName('app_home');
    $app->any('/auth/login', [UserAccountController::class, 'getSignUp'])->setName('auth_user_login')->add(\App\Middleware\RedirectUserIfAuthMiddleware::class);
    $app->get('/search/i/', [FrontInterventionSearch::class, 'search'])->setName('app_search_intervention');
    $app->group('/i/', function (RouteCollectorProxy $group) {
        $group->get('{ref_for_link}', [FrontInterventionRead::class, 'read'])->setName('app_read_intervention');
    });
    // Administration
    $app->get('/admin', [\App\Controller\Backoffice\IndexController::class, 'index'])->setName('dash_home');
    /* Customer */
    $app->group('/admin/customers', function (RouteCollectorProxy $group) {
        $group->get('', [CustomerController::class, 'index'])->setName('dash_customer');
        $group->any('/create', [CustomerCreateController::class, 'particular'])->setName('customer_create_particular');
        $group->any('/create/society', [CustomerCreateController::class, 'society'])->setName('customer_create_society');
        $group->get('/ajax/search/{key}', [CustomerAjaxController::class, 'search'])->setName('customer_search');
        $group->any('/{id:[0-9]+}/ajax/note-update', [CustomerAjaxController::class, 'updateNote']);
        $group->get('/{id:[0-9]+}', [CustomerReadController::class, 'read'])->setName('customer_read');
        $group->any('/{id:[0-9]+}/update', [CustomerUpdateController::class, 'update'])->setName('customer_update')->add(IfUpdateSocietyMiddleware::class);
        $group->any('/{id:[0-9]+}/update/parameters', [CustomerUpdateController::class, 'updateParameters'])->setName('customer_update_parameters');
        $group->post('/{id:[0-9]+}/update/parameters/password', [CustomerUpdateController::class, 'updatePortalPassword'])->setName('customer_update_parameters_password');
        $group->delete('/{id:[0-9]+}/delete', [CustomerDeleteController::class, 'delete'])->setName('customer_delete');
        // Update type Customer
        $group->post('/{id:[0-9]+}/update/parameters/type', [CustomerUpdateController::class, 'updateType'])->setName('customer_update_parameters_type');
    });
    /* For Society */
    $app->group('/admin/society', function (RouteCollectorProxy $group){
        $group->any('/{id:[0-9]+}/update', [SocietyUpdateController::class, 'update'])->setName('society_update');
    });
    /* Workshop */
    $app->group('/admin/workshop', function (RouteCollectorProxy $group){
        $group->any('', [WorkshopController::class, 'index'])->setName('dash_workshop');
        $group->any('/create', [WorkshopActionController::class, 'action'])->setName('workshop_create');
        $group->any('/{id:[0-9]+}/update', [WorkshopActionController::class, 'action'])->setName('workshop_update');
    });
    /* User (worker) */
    $app->group('/admin/users', function (RouteCollectorProxy $group)
    {
       $group->any('', [UserController::class, 'index'])->setName('dash_user');
        $group->any('/create', [UserActionController::class, 'action'])->setName('user_create');
        $group->any('/{id:[0-9]+}/update', [UserActionController::class, 'action'])->setName('user_update');
        $group->get('/{id:[0-9]+}', [UserReadController::class, 'read'])->setName('user_read');
    });
    /* Equipment */
    $app->group('/admin/equipments', function (RouteCollectorProxy $group){
        $group->get('', [EquipmentController::class, 'index'])->setName('dash_equipment');
        $group->get('/ajax/search/{key}', [EquipmentAjaxController::class, 'search'])->setName('equipment_search');
        $group->post('/ajax/add/fast', [EquipmentAjaxController::class, 'addFast'])->setName('equipment_create_fast');
        $group->any('/create', [EquipmentCreateController::class, 'create'])->setName('equipment_create');
        $group->any('/create/device', [PeripheralCreateController::class, 'create'])->setName('equipment_create_device');
        $group->any('/device/{id:[0-9]+}/update', [PeripheralUpdateController::class, 'update'])->setName('equipment_update_device');
        $group->any('/c/{id:[0-9]+}/create', [EquipmentCreateController::class, 'create'])->setName('equipment_create_customer');
        $group->any('/create/specs', [EquipmentCreateController::class, 'createSpecificities'])->setName('equipment_create_specs');
        $group->get('/{id:[0-9]+}', [EquipmentReadController::class, 'read'])->setName('equipment_read');
        $group->any('/{id:[0-9]+}/update', [EquipmentUpdateController::class, 'update'])->setName('equipment_update');
        $group->any('/ajax/{id:[0-9]+}/update-note', [EquipmentAjaxController::class, 'updateNote'])->setName('equipment_update_ajax_note');
        $group->any('/{id:[0-9]+}/update/specs', [EquipmentUpdateController::class, 'specificies'])->setName('equipment_update_specs');
        $group->post('/{id:[0-9]+}/update/specs/bao/upload', [EquipmentUpdateController::class, 'baoUpload'])->setName('equipment_update_specs_upload');
    });
    /* Intervention */
    $app->group('/admin/interventions', function (RouteCollectorProxy $group){
       $group->get('', [InterventionController::class, 'index'])->setName('dash_intervention');
       $group->get('/search[:{args}]', [\App\Controller\Backoffice\Intervention\InterventionSearchController::class, 'searchByFields'])->setName('intervention_search_fields');
        $group->get('/ajax/search/{key}', [InterventionAjaxController::class, 'search'])->setName('intervention_search');
       $group->post('/ajax/add/fast', [InterventionAjaxController::class, 'addFast'])->setName("intervention_create_fast");
       $group->get('/create', [InterventionCreateController::class, 'index'])->setName('intervention_create_index');
       $group->any('/create-regular[:{id:[0-9]+}]', [InterventionCreateController::class, 'regular'])->setName('intervention_create_regular');
       $group->any('/create-regular/c-{id:[0-9]+}', [InterventionCreateController::class, 'regular'])->setName('intervention_create_customer_regular');
       $group->any('/create-regular/complete', [InterventionCreateController::class, 'next'])->setName('intervention_create_customer_regular_complete');
       $group->post('/create-regular/save', [InterventionCreateController::class, 'save'])->setName('intervention_create_save');
       $group->get('/{id:[0-9]+}', [InterventionReadController::class, 'read'])->setName('intervention_read')->add(IfDarftMiddleware::class);
       $group->post('/{id:[0-9]+}/update', [InterventionUpdateController::class, 'update'])->setName('intervention_update');
       $group->any('/{id:[0-9]+}/validation', [InterventionValidatedController::class, 'validated'])->setName('intervention_validation');
       $group->delete('/{id:[0-9]+}/delete', [InterventionDeleteController::class, 'delete'])->setName('intervention_delete');
    });
    /* Task */
    $app->group('/admin/tasks', function (RouteCollectorProxy $group) {
        $group->post('/add/i-{id:[0-9]+}', [AddTaskToInterventionController::class, 'addToIntervention'])->setName('task_add_intervention');
        $group->post('/delete/i-{id:[0-9]+}_t-{task:[0-9]+}', [DeleteTaskOfInterventionController::class, 'deleteOfI'])->setName('task_delete_intervention');
    });
    /** Settings */
    $app->group('/admin/settings', function (RouteCollectorProxy $group) {
       $group->any('/preferences', [ParametersController::class, 'parameters'])->setName('settings_preference');
       $group->get('/tasks', [SettingTask::class, 'index'])->setName('settings_task');
       $group->post('/tasks/create', [SettingTask::class, 'actionForm'])->setName('settings_task_create');
       $group->post('/tasks/update[:{args}]', [SettingTask::class, 'actionForm'])->setName('settings_task_update');
       $group->delete('/tasks/delete', [SettingTask::class, 'delete'])->setName('settings_task_delete');
    });
    /** Portal */
    $app->post('/wxy/customers/login', [LoginController::class, 'index'])->setName('portal_login');
    $app->group('/wxy/customers', function (RouteCollectorProxy $group){
        $group->get('', [PortalController::class, 'index'])->setName('portal_home');
    });
};