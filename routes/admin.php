<?php

use App\Events\ReverbTestEvent;
use App\Http\Controllers\Admin\QuestionTypeController;
use App\Http\Middleware\LookerStudioReportViewStatus;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;


//?? For Admin Portal =============================================================================
Route::namespace('App\Http\Controllers\Admin')->group(function () {

    // Facility Admin
    Route::get('/dt/facilities', 'FacilityController@dataTable')->name('facilities.datatable');
    Route::get('/dt/question-bank-organization', 'QuestionBankOrganizationController@dataTable')->name('question-bank-organization.datatable');
    // FacilityEmployee Admin
    // Route::get('/dt/facility-employees/{facility_id}', 'FacilityEmployeeController@datatable')->name('facility_employee.datatable');
    // Form Admin
    Route::put('forms/update', 'FormController@update')->name('forms.update');
    Route::get('/facilities-report/{facility_uuid}/{output?}',  'FacilityController@pdfReport')->name('facilities.report');

    Route::post('/cancel-assist',  'AssistController@cancel')->name('assist.cancel');

    // Order Admin
    Route::controller(OrderController::class)->group(function () {
        Route::get("/dt/orders", "datatable")->name('orders.datatable');
        Route::post("/orders-cancel/{order}", "cancel")->name('orders.update.cancel');
        Route::post("/orders-status", "update_status")->name('orders.update.status');
        Route::get('/orders-notes', 'get_notes')->name('orders.notes.get');
        Route::post('/orders-notes', 'update_notes')->name('orders.notes.update');
        Route::get('/orders-report/{order_uuid}/{output?}',  'pdfReport')->name('orders.report');
    });

    Route::controller(OrderReportController::class)->group(function () {
        Route::get('/orders-details/order/{order_uuid}/{output?}',  'orderPdfReport')->name('order-details.order-report');
        Route::get('/order-details/facility/{order_uuid}/{output?}',  'facilityPdfReport')->name('order-details.facility-report');
        Route::get('/order-details/ticket/{order_uuid}/{order_sector_id}/{output?}',  'ticketPdfReport')->name('order-details.ticket-report');
        Route::get('/order-details/support/{order_uuid}/{order_sector_id}/{output?}',  'supportPdfReport')->name('order-details.support-report');
        Route::get('/order-details/fine/{order_uuid}/{order_sector_id}/{output?}',  'finePdfReport')->name('order-details.fine-report');
        Route::get('/order-details/meal/{order_uuid}/{order_sector_id}/{output?}',  'mealPdfReport')->name('order-details.meal-report');
        Route::get('/order-details/operation-summary/{order_uuid}/{order_sector_id}/{output?}',  'operationSummaryReport')->name('order-details.operation-summary-report');
        Route::get('/order-details/submitted-form/{submitted_form_id}/{output?}',  'submittedFormPdfReport')->name('order-details.submitted-form-report');
    });

    Route::controller(OrderAssignController::class)->group(function () {
        Route::get('/orders-assigns',  'index')->name('order-assigns.index');
        Route::get('/dt/order-assigns',  'datatable')->name('order-assigns.datatable');
        Route::post('/order-assigns','store')->name('order-assigns.store');
        Route::post('/orders-assigns','store_multiple')->name('orders-assigns.store');
        Route::delete('/orders-unassigns','unassigns')->name('orders-assigns.destroy');
    });
    Route::controller(MonitorController::class)->group(function () {
        Route::get('/monitors/meals/{monitor_id}/{output?}',  'mealsPdfReport')->name('monitors.meals-report');
        Route::get('/monitors/submitted-forms/{submitted_form_id}/{output?}',  'submittedFormsPdfReport')->name('monitors.submitted-forms-report');
        Route::get('/monitors/tickets/{monitor_id}/{output?}',  'ticketsPdfReport')->name('monitors.tickets-report');
        Route::get('/monitors/supports/{monitor_id}/{output?}',  'supportsPdfReport')->name('monitors.supports-report');
        Route::get('/monitors/general-info/{monitor_id}/{output?}',  'infoPdfReport')->name('monitors.info-report');
    });
    Route::controller(SubmittedFormController::class)->group(function () {
        Route::get('/submitted-forms/user/{user_id}',  'storeUserId')->name('submitted-forms.store-user-id');
        Route::get('/submitted-forms/order-sector/{order_sector_id}',  'storeOrderSectorId')->name('submitted-forms.store-order-sector-id');
    });

    //Fine Admin
    Route::post('/fines-status', 'FineController@update_status')->name('fines.update.status');

    // change danger level in reason-danger
    Route::post("/update-danger-level", 'ReasonDangerController@levelUpdate')->name('update-danger-level');


    // OrganizationService Admin
    Route::controller(OrganizationServiceController::class)->group(function () {
        Route::get("/dt/organization-services/{organization_service_id}", "datatable")->name('organization_services.datatable');
        // Route::get("/organization-services", "index")->name('admin.organization_service.index');
    });

    Route::delete("/organizations/delete-portfolio", "OrganizationController@deletePortfolio")->name('portfolio.delete');

    // Question Admin
    Route::post("/question", "QuestionController@store")->name('questions.store');
    Route::post("/question", "QuestionController@destroy")->name('questions.destroy');
    Route::post("/question", "QuestionController@update")->name('questions.update');
    Route::get("dt/questions", "QuestionController@dataTable")->name('questions.datatable');

    Route::get("dt/food-weights", "FoodWeightController@dataTable")->name('food-weights.datatable');

    Route::get('dt/question-bank-organizations', 'QuestionBankOrganizationController@dataTable')->name('question-bank-organizations.datatable');
    Route::get('dt/fine-organizations', 'FineOrganizationController@dataTable')->name('fine-organizations.datatable');
    Route::get('dt/fines', 'FineController@dataTable')->name('fines.datatable');
    Route::get("dt/nationalities-organization", "NationalityOrganizationController@dataTable")->name('nationality-organizations.datatable');

    // Section Admin
    Route::post('sections', 'SectionController@store')->name('sections.store');
    Route::put('sections/update', 'SectionController@update')->name('sections.update');
    Route::get('/dt/sections/{section_id}', 'SectionController@datatable')->name('sections.datatable');

    Route::post('/monitor-order-sectors/move', 'MonitorOrderSectorController@move')->name('monitor-order-sectors.move');
    Route::post('/monitor/roles', 'MonitorController@setRoles')->name('monitor.roles');
    Route::post('/monitor-order-sectors/swap', 'MonitorOrderSectorController@swap')->name('monitor-order-sectors.swap');

    Route::post('/order-sectors/set-active/{order_sector_id}', 'OrderSectorController@setActive')->name('order-sectors.set-active');

    //User Admin
    Route::controller(UserController::class)->group(function () {
        Route::get('/dt/users', 'dataTable')->name('users.datatable');
    });

    // QuestionType Admin
    // Route::get('/dt/question-types', [QuestionTypeController::class, 'dataTable'])->name('question-types.datatable');
    // Route::get('/delete-question-type/{question_type_id}', [QuestionTypeController::class, 'destroy'])->name('question-types.destroy');

    // Status Admin
    // Route::get('/dt/statuses', 'StatusController@dataTable')->name('statuses.datatable');

    // Status Admin
    // Route::get('/dt/regexes', 'RegexController@dataTable')->name('regexes.datatable');

    //Ticket Admin
    Route::get('/dt/tickets', 'TicketController@datatable')->name('tickets.datatable');
    Route::post("/ticket-status", "TicketController@update_status")->name('tickets.update.status');

    //Support Admin
    Route::controller(SupportController::class)->group(function () {
        Route::get('/dt/supports', 'datatable')->name('supports.datatable');
        Route::post('/supports-status', 'changeStatus')->name('supports.change_status');
        Route::get('/supports-report/{support_uuid}/{output?}',  'pdfReport')->name('supports.report');
        Route::get('/looker-supports-report/{support_uuid}/{output?}',  'pdfReport')
        ->middleware(LookerStudioReportViewStatus::class)
        ->withoutMiddleware('auth')
        ->name('supports.looker_report');
    });
    // sector Admin
    Route::controller(SectorController::class)->group(function () {
        Route::get('/dt/sectors', 'datatable')->name('sectors.datatable');
    });

    // meal organization stage Admin
    Route::controller(MealOrganizationStageController::class)->group(function () {
        Route::get('/{stage_id}/answers', 'questions_and_answers')->name('mos.answers');
    });

    //Messages Admin
    Route::get('/messages/recievers/{roles?}', 'MessageController@recievers')->name('messages.recievers');
    Route::post('/messages/send', 'MessageController@send')->name('messages.send');
    //Ticket
    Route::get('/ticket-report/{ticket_uuid}', 'TicketController@pdfReport')->name('ticket.report');
    Route::get('/looker-ticket-report/{ticket_uuid}', 'TicketController@pdfReport')
    ->middleware(LookerStudioReportViewStatus::class)
    ->withoutMiddleware('auth')
    ->name('ticket.looker_report');

    Route::get('/submitted-form-report/{submitted_form_uuid}/{output?}', 'SubmittedFormController@pdfReport')->name('submitted-form.report');
    Route::get('/looker-submitted-form-report/{submitted_form_uuid}/{output?}', 'SubmittedFormController@pdfReport')
    ->middleware(LookerStudioReportViewStatus::class)
    ->withoutMiddleware('auth')
    ->name('submitted-form.looker_report');
    Route::get('/submitted-form-report-gov/{submitted_form_uuid}/{output?}', 'SubmittedFormController@pdfGovReport')->name('submitted-form.report-gov');

    Route::controller(MealController::class)->group(function () {
        Route::get('/dt/meals', 'datatable')->name('meals.datatable');
        Route::get('/meal-report/{meal_uuid}/{output?}',  'pdfReport')->name('meal.report');
        Route::get('/looker-meal-report/{meal_uuid}/{output?}',  'pdfReport')
        ->middleware(LookerStudioReportViewStatus::class)
        ->withoutMiddleware('auth')
        ->name('meal.looker_report');
    });

    //Candidate
    Route::controller(CandidateController::class)->group(function () {
        Route::post('/candidates-status', 'changeStatus')->name('candidates.change_status');
        Route::post('/candidates-message', 'sendMessage')->name('candidates.message.get');
        Route::post('clone-candidate-to-users', 'CloneCandidate')->name('candidates.clone_candidate');
    });

    // PDF Admin
    Route::get('/test-pdf/{path}/{output?}', 'PdfController@test');
    Route::get('/contracts/generate/{order_sector_id}/{contract_template}', 'ContractController@generate_contract')->name('contracts.generate');
    Route::post('/contracts/regenerate/{contract}', 'ContractController@regenerate_contract')->name('contracts.regenerate');
    Route::post('/contracts/store', 'ContractController@store')->name('contracts.store');
    Route::post('/api/contracts/store', 'ContractController@store')->name('api.contracts.store');
    Route::get('/contracts/preview', 'ContractController@preview')->name('contracts.preview');
    Route::delete('/contracts/destroy/{contract}', 'ContractController@destroy')->name('contracts.destroy');
    Route::get('/contracts/download', 'ContractController@download')->name('contracts.download');
    Route::get('/contracts/blade', 'ContractController@blade')->name('contracts.blade');
    Route::get('/users/contracts', 'UserController@employeesDataTable')->name('employee-contracts.datatable');
    Route::post('/contracts/signed-contract', 'ContractController@storeSignedContract')->name('signed-contracts.store');
    Route::delete('/contracts/delete-signed-contract/{contract_id}', 'ContractController@destroySignedContract')->name('signed-contracts.destroy');

    Route::controller(NoteController::class)->group(function () {
        Route::post('/store-notes', 'store');
    });

    Route::controller(InterviewStandardOrderController::class)->group(function () {
        // Route::get('/interview-standard-orders/create/{order_id}', 'create')->name('interview-standard-orders.create');
        // Route::post('/interview-standard-orders/store', 'store')->name('interview-standard-orders.store');
        // Route::get('/interview-standard-orders/edit/{order_id}', 'edit')->name('interview-standard-orders.edit');
        // Route::post('/interview-standard-orders/edit/{order}', 'update')->name('interview-standard-orders.update');
        // Route::get('/interview-standard-orders/show/{order_id}', 'show')->name('interview-standard-orders.show');
    });

    Route::controller(GalleryController::class)->group(function(){
        Route::get('/gallery','index')->name('gallery.index');
        Route::get('/api/gallery','getGalleries')->name('api.gallery.index');
    });


    // Reverb socket test
    Route::prefix('reverb')->group(function () {
        Route::get('/', function () {
            return view('others.reverb-test');
        });

        Route::get('/send', function () {
            ReverbTestEvent::dispatch(
                request()->message,
                request()->userId,
            );
            return response()->json([
                'message' => 'success'
            ], 200);
        })->withoutMiddleware('auth');
    });
}); // end namespace \Controlers\Admin
