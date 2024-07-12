<?php
declare(strict_types=1);

use Selmak\Proaxive2\Domain\Application\Middleware\CheckDateCodeInitMiddleware;
use Selmak\Proaxive2\Domain\Company\Middleware\IfUpdateSocietyMiddleware;
use Selmak\Proaxive2\Domain\Customer\Middleware\RedirectIfNotAuthMiddleware;
use Selmak\Proaxive2\Domain\Equipment\Middleware\IfUpdatePeripheralMiddleware;
use Selmak\Proaxive2\Domain\Intervention\Middleware\CheckUrlCreateMiddleware;
use Selmak\Proaxive2\Domain\Intervention\Middleware\IfDarftMiddleware;
use Selmak\Proaxive2\Domain\Intervention\Middleware\IfIdIsNullMiddleware;
use Selmak\Proaxive2\Domain\Intervention\Middleware\IfLinkExpirateMiddleware;
use Selmak\Proaxive2\Http\Admin\Controller\Booking\BookingController;
use Selmak\Proaxive2\Http\Admin\Controller\Booking\BookingCreateController;
use Selmak\Proaxive2\Http\Admin\Controller\Booking\BookingDeleteController;
use Selmak\Proaxive2\Http\Admin\Controller\Booking\BookingUpdateController;
use Selmak\Proaxive2\Http\Admin\Controller\Customer\CustomerAjaxController;
use Selmak\Proaxive2\Http\Admin\Controller\Customer\CustomerController;
use Selmak\Proaxive2\Http\Admin\Controller\Customer\CustomerCreateController;
use Selmak\Proaxive2\Http\Admin\Controller\Customer\CustomerDeleteController;
use Selmak\Proaxive2\Http\Admin\Controller\Customer\CustomerDocumentController;
use Selmak\Proaxive2\Http\Admin\Controller\Customer\CustomerReadController;
use Selmak\Proaxive2\Http\Admin\Controller\Customer\CustomerUpdateController;
use Selmak\Proaxive2\Http\Admin\Controller\Deposit\DepositCreateController;
use Selmak\Proaxive2\Http\Admin\Controller\Deposit\DepositReadController;
use Selmak\Proaxive2\Http\Admin\Controller\Deposit\DepositSignController;
use Selmak\Proaxive2\Http\Admin\Controller\Deposit\DepositToPdfController;
use Selmak\Proaxive2\Http\Admin\Controller\Document\DocumentController;
use Selmak\Proaxive2\Http\Admin\Controller\Document\DocumentAddController;
use Selmak\Proaxive2\Http\Admin\Controller\Document\DocumentCreateController;
use Selmak\Proaxive2\Http\Admin\Controller\Document\DocumentDeleteController;
use Selmak\Proaxive2\Http\Admin\Controller\Document\DocumentReadController;
use Selmak\Proaxive2\Http\Admin\Controller\Equipment\EquipmentAjaxController;
use Selmak\Proaxive2\Http\Admin\Controller\Equipment\EquipmentController;
use Selmak\Proaxive2\Http\Admin\Controller\Equipment\EquipmentCreateController;
use Selmak\Proaxive2\Http\Admin\Controller\Equipment\EquipmentDeleteController;
use Selmak\Proaxive2\Http\Admin\Controller\Equipment\EquipmentReadController;
use Selmak\Proaxive2\Http\Admin\Controller\Equipment\EquipmentUpdateController;
use Selmak\Proaxive2\Http\Admin\Controller\Equipment\PeripheralDevice\PeripheralCreateController;
use Selmak\Proaxive2\Http\Admin\Controller\Equipment\PeripheralDevice\PeripheralUpdateController;
use Selmak\Proaxive2\Http\Admin\Controller\Equipment\Upload\EquipmentUploadPictureController;
use Selmak\Proaxive2\Http\Admin\Controller\Intervention\Create\InterventionCreateArgsController;
use Selmak\Proaxive2\Http\Admin\Controller\Intervention\InterventionAjaxController;
use Selmak\Proaxive2\Http\Admin\Controller\Intervention\InterventionArchiveController;
use Selmak\Proaxive2\Http\Admin\Controller\Intervention\InterventionController;
use Selmak\Proaxive2\Http\Admin\Controller\Intervention\InterventionCreateController;
use Selmak\Proaxive2\Http\Admin\Controller\Intervention\Create\InterventionCreateByStepsController as CreateIntervention;
use Selmak\Proaxive2\Http\Admin\Controller\Intervention\InterventionDeleteController;
use Selmak\Proaxive2\Http\Admin\Controller\Intervention\InterventionReadController;
use Selmak\Proaxive2\Http\Admin\Controller\Intervention\InterventionSearchController;
use Selmak\Proaxive2\Http\Admin\Controller\Intervention\InterventionToPdfController;
use Selmak\Proaxive2\Http\Admin\Controller\Intervention\InterventionUpdateController;
use Selmak\Proaxive2\Http\Admin\Controller\Intervention\InterventionValidatedController;
use Selmak\Proaxive2\Http\Admin\Controller\Outlay\OutlayController;
use Selmak\Proaxive2\Http\Admin\Controller\Outlay\OutlayCreateController;
use Selmak\Proaxive2\Http\Admin\Controller\Outlay\OutlayUpdateController;
use Selmak\Proaxive2\Http\Admin\Controller\PermsController;
use Selmak\Proaxive2\Http\Admin\Controller\Settings\Account\AccountController;
use Selmak\Proaxive2\Http\Admin\Controller\Settings\BrandController;
use Selmak\Proaxive2\Http\Admin\Controller\Settings\OperatingSystemController;
use Selmak\Proaxive2\Http\Admin\Controller\Settings\ParametersController;
use Selmak\Proaxive2\Http\Admin\Controller\Settings\StatusController;
use Selmak\Proaxive2\Http\Admin\Controller\Settings\TaskController as SettingTask;
use Selmak\Proaxive2\Http\Admin\Controller\Settings\TypeEquipmentController;
use Selmak\Proaxive2\Http\Admin\Controller\Settings\UpdateAppController;
use Selmak\Proaxive2\Http\Admin\Controller\Settings\Version\VersionAppUpdateController;
use Selmak\Proaxive2\Http\Admin\Controller\Society\SocietyUpdateController;
use Selmak\Proaxive2\Http\Admin\Controller\Task\AddTaskToInterventionController;
use Selmak\Proaxive2\Http\Admin\Controller\Task\DeleteTaskOfInterventionController;
use Selmak\Proaxive2\Http\Admin\Controller\User\UserActionController;
use Selmak\Proaxive2\Http\Admin\Controller\User\UserController;
use Selmak\Proaxive2\Http\Admin\Controller\User\UserReadController;
use Selmak\Proaxive2\Http\Admin\Controller\Workshop\Upload\WorkshopUpdateLogoController;
use Selmak\Proaxive2\Http\Admin\Controller\Workshop\Upload\WorkshopUpdateSignatureController;
use Selmak\Proaxive2\Http\Admin\Controller\Workshop\WorkshopCreateController;
use Selmak\Proaxive2\Http\Admin\Controller\Workshop\WorkshopController;
use Selmak\Proaxive2\Http\Admin\Controller\Workshop\WorkshopDeleteController;
use Selmak\Proaxive2\Http\Admin\Controller\Workshop\WorkshopUpdateController;
use Selmak\Proaxive2\Http\Controller\Account\UserAccountController;
use Selmak\Proaxive2\Http\Controller\Account\UserResetController;
use Selmak\Proaxive2\Http\Controller\IndexController;
use Selmak\Proaxive2\Http\Controller\Intervention\InterventionReadController as FrontInterventionRead;
use Selmak\Proaxive2\Http\Controller\Intervention\InterventionSearchController as FrontInterventionSearch;
use Selmak\Proaxive2\Http\Controller\Portal\LoginController;
use Selmak\Proaxive2\Http\Controller\Portal\LogoutController;
use Selmak\Proaxive2\Http\Controller\Portal\PortalController;
use Selmak\Proaxive2\Http\Controller\Portal\PortalDocumentController;
use Selmak\Proaxive2\Http\Controller\Portal\PortalInterventionController;
use Selmak\Proaxive2\Http\Controller\Portal\PortalParameterController;
use Selmak\Proaxive2\Security\Middleware\IfDataNullOrEmptyMiddleware;
use Selmak\Proaxive2\Security\Middleware\IfMailerIsNotActivateMiddleware;
use Selmak\Proaxive2\Security\Middleware\Perms\RedirectIfNotAdminMiddleware;
use Selmak\Proaxive2\Security\Middleware\Perms\RedirectIfNotAdminOrTechMiddleware;
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
        $group->any('2fa', [UserAccountController::class, 'signTotp'])->setName('auth_user_2fa');
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
    $app->get('/admin', [\Selmak\Proaxive2\Http\Admin\Controller\IndexController::class, 'index'])->setName('dash_home');
    // Account Admin/Tech/Manager
    $app->group('/admin/settings/account', function (RouteCollectorProxy $group) {
        $group->any('', [AccountController::class, 'index'])->setName('dash_account');
        $group->post('/2fa', [AccountController::class, 'on2fa'])->setName('dash_account_on_2fa');
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
        $group->get('/{id:[0-9]+}/documents', CustomerDocumentController::class)->setName('customer_documents');
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
        $group->any('/create', WorkshopCreateController::class)->setName('workshop_create');
        $group->any('/{id:[0-9]+}/update', WorkshopUpdateController::class)->setName('workshop_update');
        $group->post('/{id:[0-9]+}/ajax/logo', WorkshopUpdateLogoController::class)->setName('workshop_update_logo');
        $group->post('/{id:[0-9]+}/ajax/signature', WorkshopUpdateSignatureController::class)->setName('workshop_update_signature');
        $group->any('/{id:[0-9]+}/delete', WorkshopDeleteController::class)->setName('workshop_delete');
    })->add(RedirectIfNotAdminMiddleware::class);
    /* User (worker) */
    $app->group('/admin/users', function (RouteCollectorProxy $group)
    {
       $group->any('', [UserController::class, 'index'])->setName('dash_user');
        $group->any('/create', [UserActionController::class, 'action'])->setName('user_create')->add(IfMailerIsNotActivateMiddleware::class);
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
        $group->post('/{id:[0-9]+}/picture/upload', EquipmentUploadPictureController::class)->setName('equipment_upload_picture');
        $group->delete('/{id}/delete', [EquipmentDeleteController::class, 'delete'])->setName('equipment_delete');
        $group->delete('/delete/selected', [EquipmentDeleteController::class, 'deleteSelected'])->setName('equipment_delete_selected');
    })->add(RedirectIfNotAdminOrTechMiddleware::class);
    /* Intervention */
    $app->group('/admin/interventions', function (RouteCollectorProxy $group){
       $group->get('', [InterventionController::class, 'index'])->setName('dash_intervention');
       $group->get('/[:{args}]', [InterventionController::class, 'index'])->setName('dash_intervention_all');
       $group->get('/search[:{args}]', [InterventionSearchController::class, 'searchByFields'])->setName('intervention_search_fields');
       $group->get('/ajax/search/{key}', [InterventionAjaxController::class, 'search'])->setName('intervention_search');
       $group->post('/ajax/add/fast', [InterventionAjaxController::class, 'addFast'])->setName("intervention_create_fast");
       $group->any('/{id:[0-9]+}/ajax/start', [InterventionAjaxController::class, 'start'])->setName("intervention_ajax_start");
       $group->post('/{id:[0-9]+}/ajax/end', [InterventionAjaxController::class, 'end'])->setName("intervention_ajax_end");
       $group->post('/{id:[0-9]+}/ajax/e-update/{eid:[0-9]+}', [InterventionAjaxController::class, 'updateEquipmentName'])->setName('intervention_ajax_u_equipment_name');
       $group->post('/{id:[0-9]+}/ajax/next-step', [InterventionAjaxController::class, 'nextStep'])->setName('ajax_intervention_next_step');
       $group->get('/create', [InterventionCreateController::class, 'index'])->setName('intervention_create_index');
       $group->any('/create-regular[:{id:[0-9]+}]', [CreateIntervention::class, 'create'])->setName('intervention_create_regular');
       $group->any('/create-regular/client-and-tech', [CreateIntervention::class, 'createStep2'])->setName('intervention_create_regular_step2');
       $group->any('/create-regular/client/{id:[0-9]+}/equipments[:{args}]', [CreateIntervention::class, 'createStep3'])->setName('intervention_create_regular_step3')->add(CheckUrlCreateMiddleware::class);
       $group->any('/create-regular/client/{id:[0-9]+}/observations[:{args}]', [CreateIntervention::class, 'createStep4'])->setName('intervention_create_regular_step4')->add(CheckUrlCreateMiddleware::class);
       $group->any('/create-regular/client/{id:[0-9]+}/setting[:{args}]', [CreateIntervention::class, 'createStep5'])->setName('intervention_create_regular_step5')->add(CheckUrlCreateMiddleware::class);
       $group->any('/create/c-{id:[0-9]+}', InterventionCreateArgsController::class)->setName('intervention_create_args');
       $group->post('/create-regular/save', [InterventionCreateController::class, 'save'])->setName('intervention_create_save');
       $group->get('/{id:[0-9]+}', [InterventionReadController::class, 'read'])->setName('intervention_read')->add(IfIdIsNullMiddleware::class)->add(IfDarftMiddleware::class);
       $group->post('/{id:[0-9]+}/update', [InterventionUpdateController::class, 'update'])->setName('intervention_update');
       $group->any('/{id:[0-9]+}/validation', InterventionValidatedController::class)->setName('intervention_validation');
       $group->any('/{id:[0-9]+}/archive', [InterventionArchiveController::class, 'index'])->setName('intervention_archive');
       $group->any('/{id:[0-9]+}/archive/read', [InterventionArchiveController::class, 'readArchive'])->setName('intervention_archive_read');
       $group->get('/{id:[0-9]+}/pdf', InterventionToPdfController::class)->setName('intervention_open_pdf');
       $group->delete('/{id:[0-9]+}/delete', [InterventionDeleteController::class, 'delete'])->setName('intervention_delete')->add(RedirectIfNotAdminMiddleware::class);
    })->add(RedirectIfNotAdminOrTechMiddleware::class)->add(IfDataNullOrEmptyMiddleware::class);
    /* Deposit */
    $app->group('/admin/deposit', function (RouteCollectorProxy $group) {
       $group->post('/add/i-{id:[0-9]+}', [DepositCreateController::class, 'create'])->setName('deposit_create');
       $group->any('/{reference}/sign', [DepositSignController::class, 'index'])->setName('deposit_sign');
       $group->get('/[:{args}]', [DepositReadController::class, 'read'])->setName('deposit_read');
       $group->get('/pdf/{reference}', [DepositToPdfController::class, 'viewDepositPdf'])->setName('deposit_read_pdf');
    })->add(RedirectIfNotAdminOrTechMiddleware::class);
    /* Outlay */
    $app->group('/admin/outlay', function (RouteCollectorProxy $group) {
        //$group->post('', [DepositCreateController::class, 'create'])->setName('deposit_create');
        $group->get('', OutlayController::class)->setName('dash_outlay');
        $group->any('/create', OutlayCreateController::class)->setName('outlay_create');
        $group->any('/{id:[0-9]+}/update', OutlayUpdateController::class)->setName('outlay_update');
    })->add(RedirectIfNotAdminOrTechMiddleware::class);
    /* Document */
    $app->group('/admin/documents', function (RouteCollectorProxy $group) {
        $group->get('', DocumentController::class)->setName('dash_documents');
        $group->any('/create', DocumentCreateController::class)->setName('document_create');
        $group->any('/add[:{args}]', DocumentAddController::class)->setName('document_add');
        $group->get('/read/{id:[0-9]+}', DocumentReadController::class)->setName('document_read');
        $group->delete('/{id:[0-9]+}/delete', DocumentDeleteController::class)->setName('document_delete');
    })->add(RedirectIfNotAdminOrTechMiddleware::class);
    /* Task */
    $app->group('/admin/tasks', function (RouteCollectorProxy $group) {
        $group->post('/add/i-{id:[0-9]+}', [AddTaskToInterventionController::class, 'addToIntervention'])->setName('task_add_intervention');
        $group->post('/delete/i-{id:[0-9]+}_t-{task:[0-9]+}', [DeleteTaskOfInterventionController::class, 'deleteOfI'])->setName('task_delete_intervention');
    })->add(RedirectIfNotAdminOrTechMiddleware::class);
    /** E-Calendar */
    $app->group('/admin/booking', function (RouteCollectorProxy $group) {
        $group->get('', [BookingController::class, 'fullcalendar'])->setName('dash_booking');
        $group->any('/get/all', [BookingController::class, 'getAll'])->setName('booking_get_all');
        $group->get('/all', [BookingController::class, 'all'])->setName('booking_all');
        $group->any('/c-i', [BookingCreateController::class, 'createForIntervention'])->setName('add_booking_for_intervention');
        $group->post('/create', [BookingCreateController::class, 'create'])->setName('booking_create');
        $group->any('/{id:[0-9]+}/update', BookingUpdateController::class)->setName('booking_update');
        $group->delete('/delete/{id:[0-9]+}', BookingDeleteController::class)->setName('booking_delete');
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
       $group->get('/status', [StatusController::class, 'index'])->setName('settings_status');
       $group->post('/status/create', [StatusController::class, 'actionForm'])->setName('settings_status_create');
       $group->post('/status/update[:{args}]', [StatusController::class, 'actionForm'])->setName('settings_status_update');
       $group->post('/status/delete', [StatusController::class, 'delete'])->setName('settings_status_delete');
       $group->any('/update', UpdateAppController::class)->setName('settings_update');
       $group->post('/command/app-update', VersionAppUpdateController::class)->setName('settings_app_update')->add(RedirectIfNotAdminMiddleware::class);
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
        $group->get('/documents', [PortalDocumentController::class, 'index'])->setName('portal_documents');
        $group->post('/documents/dl/{reference}', [PortalDocumentController::class, 'download'])->setName('portal_documents_download');
    })->add(RedirectIfNotAuthMiddleware::class);
    $app->get('/wxy/customers/logout', LogoutController::class)->setName('portal_logout');
};