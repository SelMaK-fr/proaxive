<?php
declare(strict_types=1);

use App\Controller\Account\UserAccountController;
use App\Controller\Account\UserResetController;
use App\Controller\Backoffice\Customer\CustomerActionController;
use App\Controller\Backoffice\Customer\CustomerAjaxController;
use App\Controller\Backoffice\Customer\CustomerController;
use App\Controller\Backoffice\Customer\CustomerCreateController;
use App\Controller\Backoffice\Customer\CustomerDeleteController;
use App\Controller\Backoffice\Customer\CustomerReadController;
use App\Controller\Backoffice\Customer\CustomerUpdateController;
use App\Controller\Backoffice\Deposit\DepositCreateController;
use App\Controller\Backoffice\Deposit\DepositReadController;
use App\Controller\Backoffice\Deposit\DepositSignController;
use App\Controller\Backoffice\Deposit\DepositToPdfController;
use App\Controller\Backoffice\Equipment\EquipmentAjaxController;
use App\Controller\Backoffice\Equipment\EquipmentController;
use App\Controller\Backoffice\Equipment\EquipmentCreateController;
use App\Controller\Backoffice\Equipment\EquipmentDeleteController;
use App\Controller\Backoffice\Equipment\EquipmentReadController;
use App\Controller\Backoffice\Equipment\EquipmentUpdateController;
use App\Controller\Backoffice\Equipment\PeripheralDevice\PeripheralCreateController;
use App\Controller\Backoffice\Equipment\PeripheralDevice\PeripheralUpdateController;
use App\Controller\Backoffice\Intervention\InterventionAjaxController;
use App\Controller\Backoffice\Intervention\InterventionArchiveController;
use App\Controller\Backoffice\Intervention\InterventionController;
use App\Controller\Backoffice\Intervention\InterventionCreateController;
use App\Controller\Backoffice\Intervention\InterventionDeleteController;
use App\Controller\Backoffice\Intervention\InterventionReadController;
use App\Controller\Backoffice\Intervention\InterventionUpdateController;
use App\Controller\Backoffice\Intervention\InterventionValidatedController;
use App\Controller\Backoffice\PermsController;
use App\Controller\Backoffice\Settings\Account\AccountController;
use App\Controller\Backoffice\Settings\BrandController;
use App\Controller\Backoffice\Settings\OperatingSystemController;
use App\Controller\Backoffice\Settings\ParametersController;
use App\Controller\Backoffice\Settings\TaskController as SettingTask;
use App\Controller\Backoffice\Settings\TypeEquipmentController;
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
use App\Controller\Frontoffice\Portal\LoginController;
use App\Controller\Frontoffice\Portal\PortalController;
use App\Controller\Frontoffice\Portal\PortalInterventionController;
use App\Controller\Frontoffice\Portal\PortalParameterController;
use App\Middleware\Auth\RedirectAuthIfCookieMiddleware;
use App\Middleware\Equipment\IfUpdatePeripheralMiddleware;
use App\Middleware\IfUpdateSocietyMiddleware;
use App\Middleware\Intervention\IfDarftMiddleware;
use App\Middleware\Intervention\IfIdIsNullMiddleware;
use App\Middleware\Intervention\IfLinkExpirateMiddleware;
use App\Middleware\Perms\RedirectIfNotAdminMiddleware;
use App\Middleware\Perms\RedirectIfNotAdminOrManagerMiddleware;
use App\Middleware\Perms\RedirectIfNotAdminOrTechMiddleware;
use App\Middleware\Perms\RedirectNotPermitDemo;
use Selmak\Proaxive2\Middleware\CheckDateCodeInitMiddleware;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    $app->get('/', IndexController::class)->setName('app_home');
    // Authentificator Panel
    $app->group('/auth/', function (RouteCollectorProxy $group) {
        $group->any('login', [UserAccountController::class, 'getSignIn'])->setName('auth_user_login');
        $group->any('confirm-account/[:{args}]', [UserAccountController::class, 'firstSignIn'])->setName('auth_first_login');
        $group->any('reset', [UserResetController::class, 'index'])->setName('auth_reset_account');
        $group->any('reset/code/{token}-{id:[0-9]+}', [UserResetController::class, 'validCodeReset'])->setName('auth_reset_valid_code')->add(CheckDateCodeInitMiddleware::class);
        $group->any('reset/password/{token}', [UserResetController::class, 'newPassword'])->setName('auth_reset_password')->add(CheckDateCodeInitMiddleware::class);
        $group->any('logout', [UserAccountController::class, 'logout'])->setName('auth_user_logout');
    });

    $app->get('/search/i/', [FrontInterventionSearch::class, 'search'])->setName('app_search_intervention');
    $app->group('/i/', function (RouteCollectorProxy $group) {
        $group->get('{ref_for_link}', [FrontInterventionRead::class, 'read'])->setName('app_read_intervention')->add(IfLinkExpirateMiddleware::class);
    });
    // Administration
    // Home and Perms page
    $app->group('/admin', function (RouteCollectorProxy $group) {
        $group->get('/auth/perms', [PermsController::class, 'not_perms'])->setName('admin_perms');
        $group->get('/auth/perms/tech', [PermsController::class, 'not_perms_tech'])->setName('admin_perms_tech');
    });
    $app->get('/admin', [\App\Controller\Backoffice\IndexController::class, 'index'])->setName('dash_home');
    // Account Admin/Tech/Manager
    $app->group('/admin/settings/account', function (RouteCollectorProxy $group) {
        $group->any('', [AccountController::class, 'index'])->setName('dash_account');
    });
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
    }); // ->add(RedirectNotPermitDemo::class)
    /* Equipment */
    $app->group('/admin/equipments', function (RouteCollectorProxy $group){
        $group->get('', [EquipmentController::class, 'index'])->setName('dash_equipment');
        $group->get('/ajax/search/{key}', [EquipmentAjaxController::class, 'search'])->setName('equipment_search');
        $group->post('/ajax/add/fast', [EquipmentAjaxController::class, 'addFast'])->setName('equipment_create_fast');
        $group->any('/create', [EquipmentCreateController::class, 'create'])->setName('equipment_create');
        $group->any('/create/device', [PeripheralCreateController::class, 'create'])->setName('equipment_create_device');
        $group->any('/c/{id:[0-9]+}/create/device', [PeripheralCreateController::class, 'create'])->setName('equipment_create_customer_device');
        $group->any('/device/{id:[0-9]+}/update', [PeripheralUpdateController::class, 'update'])->setName('equipment_update_device');
        $group->any('/c/{id:[0-9]+}/create', [EquipmentCreateController::class, 'create'])->setName('equipment_create_customer');
        $group->any('/create/specs', [EquipmentCreateController::class, 'createSpecificities'])->setName('equipment_create_specs');
        $group->get('/{id:[0-9]+}', [EquipmentReadController::class, 'read'])->setName('equipment_read');
        $group->any('/{id:[0-9]+}/update', [EquipmentUpdateController::class, 'update'])->setName('equipment_update')->add(IfUpdatePeripheralMiddleware::class);
        $group->any('/ajax/{id:[0-9]+}/update-note', [EquipmentAjaxController::class, 'updateNote'])->setName('equipment_update_ajax_note');
        $group->any('/{id:[0-9]+}/update/specs', [EquipmentUpdateController::class, 'specificies'])->setName('equipment_update_specs');
        $group->post('/{id:[0-9]+}/update/specs/bao/upload', [EquipmentUpdateController::class, 'baoUpload'])->setName('equipment_update_specs_upload');
        $group->delete('/{id}/delete', [EquipmentDeleteController::class, 'delete'])->setName('equipment_delete');
        $group->delete('/delete/selected', [EquipmentDeleteController::class, 'deleteSelected'])->setName('equipment_delete_selected');
    })->add(RedirectIfNotAdminOrTechMiddleware::class);
    /* Intervention */
    $app->group('/admin/interventions', function (RouteCollectorProxy $group){
       $group->get('', [InterventionController::class, 'index'])->setName('dash_intervention');
       $group->get('/[:{args}]', [InterventionController::class, 'index'])->setName('dash_intervention_all');
       $group->get('/search[:{args}]', [\App\Controller\Backoffice\Intervention\InterventionSearchController::class, 'searchByFields'])->setName('intervention_search_fields');
       $group->get('/ajax/search/{key}', [InterventionAjaxController::class, 'search'])->setName('intervention_search');
       $group->post('/ajax/add/fast', [InterventionAjaxController::class, 'addFast'])->setName("intervention_create_fast");
       $group->post('/{id:[0-9]+}/ajax/start', [InterventionAjaxController::class, 'start'])->setName("intervention_ajax_start");
       $group->post('/{id:[0-9]+}/ajax/end', [InterventionAjaxController::class, 'end'])->setName("intervention_ajax_end");
       $group->post('/{id:[0-9]+}/ajax/e-update/{eid:[0-9]+}', [InterventionAjaxController::class, 'updateEquipmentName'])->setName('intervention_ajax_u_equipment_name');
       $group->post('/{id:[0-9]+}/ajax/next-step', [InterventionAjaxController::class, 'nextStep'])->setName('ajax_intervention_next_step');
       $group->get('/create', [InterventionCreateController::class, 'index'])->setName('intervention_create_index');
       $group->any('/create-regular[:{id:[0-9]+}]', [InterventionCreateController::class, 'regular'])->setName('intervention_create_regular');
       $group->any('/create-regular/c-{id:[0-9]+}', [InterventionCreateController::class, 'regular'])->setName('intervention_create_customer_regular');
       $group->any('/create-regular/complete', [InterventionCreateController::class, 'next'])->setName('intervention_create_customer_regular_complete');
       $group->post('/create-regular/save', [InterventionCreateController::class, 'save'])->setName('intervention_create_save');
       $group->get('/{id:[0-9]+}', [InterventionReadController::class, 'read'])->setName('intervention_read')->add(IfIdIsNullMiddleware::class)->add(IfDarftMiddleware::class);
       $group->post('/{id:[0-9]+}/update', [InterventionUpdateController::class, 'update'])->setName('intervention_update');
       $group->any('/{id:[0-9]+}/validation', [InterventionValidatedController::class, 'validated'])->setName('intervention_validation');
       $group->any('/{id:[0-9]+}/archive', [InterventionArchiveController::class, 'index'])->setName('intervention_archive');
       $group->any('/{id:[0-9]+}/archive/read', [InterventionArchiveController::class, 'readArchive'])->setName('intervention_archive_read');
       $group->delete('/{id:[0-9]+}/delete', [InterventionDeleteController::class, 'delete'])->setName('intervention_delete')->add(RedirectIfNotAdminMiddleware::class);
    })->add(RedirectIfNotAdminOrTechMiddleware::class);
    /* Deposit */
    $app->group('/admin/deposit', function (RouteCollectorProxy $group) {
       $group->post('/add/i-{id:[0-9]+}', [DepositCreateController::class, 'create'])->setName('deposit_create');
       $group->any('/{reference}/sign', [DepositSignController::class, 'index'])->setName('deposit_sign');
       $group->get('/[:{args}]', [DepositReadController::class, 'read'])->setName('deposit_read');
       $group->get('/pdf/{reference}', [DepositToPdfController::class, 'viewDepositPdf'])->setName('deposit_read_pdf');
    })->add(RedirectIfNotAdminOrTechMiddleware::class);
    /* Task */
    $app->group('/admin/tasks', function (RouteCollectorProxy $group) {
        $group->post('/add/i-{id:[0-9]+}', [AddTaskToInterventionController::class, 'addToIntervention'])->setName('task_add_intervention');
        $group->post('/delete/i-{id:[0-9]+}_t-{task:[0-9]+}', [DeleteTaskOfInterventionController::class, 'deleteOfI'])->setName('task_delete_intervention');
    })->add(RedirectIfNotAdminOrTechMiddleware::class);
    /** Settings */
    $app->group('/admin/settings', function (RouteCollectorProxy $group) {
       $group->any('/preferences', [ParametersController::class, 'parameters'])->setName('settings_preference')->add(RedirectIfNotAdminMiddleware::class);
       $group->get('/tasks', [SettingTask::class, 'index'])->setName('settings_task');
       $group->post('/tasks/create', [SettingTask::class, 'actionForm'])->setName('settings_task_create');
       $group->post('/tasks/update[:{args}]', [SettingTask::class, 'actionForm'])->setName('settings_task_update');
       $group->delete('/tasks/delete', [SettingTask::class, 'delete'])->setName('settings_task_delete');
       $group->get('/types', [TypeEquipmentController::class, 'index'])->setName('settings_type_equipment');
       $group->post('/types/create', [TypeEquipmentController::class, 'actionForm'])->setName('settings_type_equipment_create');
       $group->post('/types/update[:{args}]', [TypeEquipmentController::class, 'actionForm'])->setName('settings_type_equipment_update');
       $group->delete('/types/delete', [TypeEquipmentController::class, 'delete'])->setName('settings_type_equipment_delete');
       $group->get('/brands', [BrandController::class, 'index'])->setName('settings_brand');
       $group->post('/brands/create', [BrandController::class, 'actionForm'])->setName('settings_brand_create');
       $group->post('/brands/update[:{args}]', [BrandController::class, 'actionForm'])->setName('settings_brand_update');
       $group->delete('/brands/delete', [BrandController::class, 'delete'])->setName('settings_brand_delete');
       $group->get('/operating-system', [OperatingSystemController::class, 'index'])->setName('settings_os');
       $group->post('/operating-system/create', [OperatingSystemController::class, 'actionForm'])->setName('settings_os_create');
       $group->post('/operating-system/update[:{args}]', [OperatingSystemController::class, 'actionForm'])->setName('settings_os_update');
       $group->delete('/operating_system/delete', [OperatingSystemController::class, 'delete'])->setName('settings_os_delete');
    })->add(RedirectIfNotAdminOrTechMiddleware::class);
    /** Portal */
    $app->any('/wxy/customers/login', [LoginController::class, 'index'])->setName('portal_login');
    $app->group('/wxy/customers', function (RouteCollectorProxy $group){
        $group->get('', [PortalController::class, 'index'])->setName('portal_home');
        $group->get('/interventions', [PortalInterventionController::class, 'index'])->setName('portal_interventions');
        $group->get('/i/r/{ref_number}', [PortalInterventionController::class, 'read'])->setName('portal_intervention_read');
        $group->any('/parameters', [PortalParameterController::class, 'index'])->setName('portal_parameters');
        $group->any('/parameters/address', [PortalParameterController::class, 'address'])->setName('portal_parameters_address');
        $group->any('/parameters/security', [])->setName('portal_parameters_security');
    });
};