<?php

// use App\Http\Controllers\Admin\FacilityController;
// use App\Http\Controllers\Admin\OrderController;
// use App\Http\Controllers\Admin\OrganizationServiceController;
// use App\Http\Controllers\CountryController;

use App\Http\Controllers\Admin\NoteController;
use App\Http\Controllers\AssistController;
use App\Http\Controllers\MonitorController;
use App\Models\Status;
use App\Models\Attachment;
use App\Models\OperationType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


//?? For APIs =================================================================================

Route::namespace('App\Http\Controllers')->group(function () {

    //Attachment
    Route::controller(AttachmentController::class)->group(function () {
        Route::middleware('webLocalization')->group(function () {
            Route::get('/attachments/{attachment}', 'show')->name('attachments.show');
            Route::post('/attachments', 'store')->name('attachments.store');
            Route::get('/facility-attachments-labels', 'AttachmentLabelController@showOrgLabels')->name('attachments-label.showOrgLabels');
            Route::get('/attachments-labels/{type}', 'AttachmentLabelController@show')->name('show');
        });
    });
    Route::middleware('webLocalization')->group(function () {
        //Country
        Route::get('/countries', 'CountryController@index')->name('countries.index');
        //Country Organization
        Route::get('/order-countries', 'CountryOrganizationController@index')->name('country-organization.index');
        //City
        Route::get('/saudi-cities', 'CityController@index')->name('cities.index');
        //District
        Route::get('/saudi-districts', 'DistrictController@index')->name('districts.index');
        //Bank
        Route::get('/banks', 'BankController@index')->name('banks.index');
        //Facility Employee Position
        Route::get('/employees-positions', 'FacilityEmployeePositionController@index')->name('facility-employee-positions.index');
        // Organization
        Route::get('/organizations', 'OrganizationController@index')->name('organizations.all');
    });
    //Facility
    Route::controller(FacilityController::class)->group(function () {
        Route::middleware(['auth:sanctum', 'webLocalization'])->group(function () {
            Route::get('/facilities', 'index')->name('facilities.index');
            Route::get('/facilities/report', 'report')->name('facilities.report');
            Route::get('/facilities/{facility}', 'show')->name('facilities.show');
            Route::post('/facilities', 'store')->name('facilities.store');
            Route::post('/facilities/{facility}', 'update')->name('facilities.update');
        });
    });

    Route::get('current-app-version', 'MobileAppInfoController@getCurrentAppVersion')->name('current-app-version');
    Route::post('update-app-version', 'MobileAppInfoController@updateAppVersion')->middleware('auth:sanctum')->name('update-app-version');

    // FacilityEmployee
    Route::controller(FacilityEmployeeController::class)->group(function () {
        Route::middleware(['auth:sanctum', 'webLocalization'])->group(function () {
            Route::get('/facility-employees', 'index')->name('facility-employees.index');
            // Route::get('/facility-employees/{facility_id}', 'index')->name('facility-employees.index');
            Route::post('/facility-employees', 'store')->name('facility-employees.store');
            Route::post('/delete-employee/{id}', 'destroy')->name('facility-employees.destroy');
        });
    });

    // Order
    Route::controller(OrderController::class)->group(function () {
        Route::middleware(['auth:sanctum', 'webLocalization'])->group(function () {
            Route::post("/orders-cancel/{order}", "cancel")->name('orders.update.cancel');
            Route::get("/orders/create", "create")->name('orders.create');
            Route::get("/orders", "index")->name('orders.index');
            Route::get("/orders/{order}", "show")->name('orders.show');
            Route::post("/orders", "store")->name('orders.store');
        });
    });

    Route::middleware(['auth:sanctum', 'webLocalization'])->group(function () {

        // OrganizationService
        // Route::controller(OrganizationServiceController::class)->group(function () {
        Route::get("/organization-services", "OrganizationServiceController@index")->name('organization_service.index');
        // });

        // User
        Route::controller(UserController::class)->group(function () {
            Route::middleware(['auth:sanctum', 'webLocalization'])->group(function () {
                Route::get('/users/info', 'info')->name('users.info');
                Route::post('/users/update', 'update')->name('users.update');
                Route::get('/user-logs', 'userLogs')->name('users.userLogs');
            });
        });
    });


    //Ticket
    Route::controller(TicketController::class)->group(function () {
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/ticket-delete/{ticket_id}', 'cascadeDelete')->name('tickets.cascadeDelete');
            Route::post('/ticket-force-delete/{ticket_id}', 'hardDelete')->name('tickets.hardDelete');
            Route::get('/ticket-reasons/{organization}', 'ticketReasons')->name('ticketReasons');
            Route::get('/ticket/statuses', 'ticketStatuses')->name('ticketStatuses');
            Route::post('/create', 'store')->name('ticket.store');
            Route::get('/all-tickets', 'index')->name('ticket.index');
        });
    });

    //Status
    Route::controller(StatusController::class)->group(function () {
        // Route::middleware('auth:sanctum')->group(function () {
        Route::get('/statuses/{type}', 'statuses')->name('statuses');
        // });
    });

    //Support
    Route::controller(SupportController::class)->group(function () {
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/support-delete/{support_id}', 'cascadeDelete')->name('supports.cascadeDelete');
            Route::post('/support-force-delete/{support_id}', 'hardDelete')->name('supports.hardDelete');
            Route::get('/support/statuses', 'supportStatuses')->name('supportStatuses');
            Route::post('/create-support', 'store')->name('support.store');
            Route::get('/all-supports', 'index')->name('support.index');
            Route::get('/support-types', 'supportType')->name('supportType');
            Route::post('/cancel-support', 'cancelSupport')->name('cancelSupport');
            Route::post('/has-enough-support', 'hasEnough')->name('hasEnough');
        });
    });

    //Assist
    Route::controller(AssistController::class)->group(function () {
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/create-assist', 'store')->name('assist.store');
            Route::get('/assists', 'index')->name('assist.index');
            Route::post('/submit-assist', 'update')->name('assist.update');
        });
    });

    //Period
    Route::controller(PeriodController::class)->group(function () {
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/periods', 'periods')->name('periods');
        });
    });

    //Danger
    Route::controller(DangerController::class)->group(function () {
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/dangers', 'dangers')->name('dangers');
        });
    });

    //Home Page
    Route::controller(HomePageController::class)->group(function () {
        Route::get('/getTime', "getCurrentTime")->name('getCurrentTime');
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/homepage-statistics', "homepageStatistics")->name('homepageStatistics');
            //         Route::get('/homepage', 'index')->name('homepage');
        });
    });

    //Operation types
    Route::controller(OperationTypeController::class)->group(function () {
        Route::get('/all-operations', "index")->name('operation-types.index');
        Route::post('/choose-operation', "chooseOperation")->name('operation-types.chooseOperation');
    });

    //Sector
    Route::controller(SectorController::class)->group(function () {
        Route::get('/sectors', 'index')->name('sectors.index');
        Route::get('/sector/{sector_id}', 'show')->name('sector.show');
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/user-sectors', 'userSectors')->name('userSectors');
            Route::get('/all-sectors-operations', 'sectorsOperations')->name('sectorsOperations');
        });
    });
    //Sector
    Route::controller(FormController::class)->group(function () {
        Route::middleware(['auth:sanctum', 'webLocalization'])->group(function () {
            Route::get('/forms/{type}', 'forms')->name('forms');
            Route::post('/submit-form', 'submit_form')->name('submit_form');
            Route::post('/submit-section', 'submitSection')->name('submitSection');
        });
    });
    //Track Location
    Route::controller(TrackLocationController::class)->group(function () {
        Route::get('/track-locations', 'index')->name('location-track.index');
        Route::get('/refada-statistics', 'statistics')->name('location-track.statistics');
        Route::get('/location/{track_location_id}', 'show')->name('location-track.show');
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/track-location', 'store')->name('location-track.store');
            // Route::get('/all-tracked-locations', "index")->name('track-locations.index');

        });
    });

    //Monitor
    Route::controller(MonitorController::class)->group(function () {
        Route::get('/monitors', 'index')->name('monitor.index');
    });

    //Fines
    Route::controller(FineController::class)->group(function () {
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/issue-fine', 'store')->name('fines.store');
        });
    });

    //departments
    Route::controller(DepartmentController::class)->group(function () {
        Route::get('/all-departments', 'index')->name('departments.index');
    });

    //subjcts
    Route::controller(SubjectController::class)->group(function () {
        Route::get('/all-subjects', 'index')->name('subjects.index');
    });

    // Candidate
    Route::controller(CandidateController::class)->group(function () {
        Route::post('/candidate', "store")->name('candidate.store');
        Route::get('/candidate/{uuid}', "show")->name('candidate.show');
        Route::post('/candidate/{uuid}', "update")->name('candidate.update');
    });

    // ContactUs
    Route::controller(ContactUsController::class)->group(function () {
        Route::post('/contact-us', "store")->name('contact-us.store');
    });

    // Meal
    Route::controller(MealController::class)->middleware('auth:sanctum')->group(function () {
        Route::get('meals', "index")->name('meals.index');
        Route::post('meal-delete/{meal_id}', "cascadeDelete")->name('meals.cascadeDelete');
        Route::post('meal-force-delete/{meal_id}', "hardDelete")->name('meals.hardDelete');
    });

    // Meal
    Route::controller(MealOrganizationStageController::class)->middleware('auth:sanctum')->group(function () {
        Route::get('meal-organization-stage/{id}/questions', "questions")->name('meal_organization_stage.questions');
        Route::post('meal-organization-stage/{id}/answers', "answers")->name('meal_organization_stage.answers');
    });

    // Notification
    Route::controller(NotificationController::class)->middleware('auth:sanctum')->group(function () {
        Route::get('all-notifications', "index")->name('notification.index');
        Route::post('mark-as-read', "markAsRead")->name('notification.markAsRead');
    });

    // SubmittedForm
    Route::controller(SubmittedFormController::class)->middleware('auth:sanctum')->group(function () {
        Route::post('/submitted-form-delete/{submitted_form_id}', 'cascadeDelete')->name('submitted_forms.cascadeDelete');
        Route::post('/submitted-form-force-delete/{submitted_form_id}', 'hardDelete')->name('submitted_forms.hardDelete');
        Route::get('/submitted-form', 'submitted_form')->name('submitted_form');
        Route::get('/submitted-forms', 'all_submitted_forms')->name('all_submitted_forms');
    });

    // Supervisor
    Route::controller(SupervisorController::class)->middleware('auth:sanctum')->group(function () {
        Route::get('/supervisor/statistics', 'statistics')->name('supervisor.statistics');
        Route::get('/supervisor/all_tickets', 'all_tickets')->name('supervisor.all_tickets');
        Route::get('/supervisor/all_supports', 'all_supports')->name('supervisor.all_supports');
    });

    Route::controller(NoteController::class)->group(function () {
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/store-notes', 'store');
        });
    });

    Route::controller(AssistQuestionController::class)->group(function () {
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/assist-questions', 'questions')->name('assist.questions');
            Route::post('/submit-assist/{assist_id}', 'answers')->name('assist.answers');
        });
    });

    // });
}); //?? namespace \Controllers

//?? For API Authontication ============================================================================
Route::middleware(['webLocalization'])->group(function () {
    Route::namespace('App\Http\Controllers\Auth')->group(function () {
        Route::post('/register', 'RegisterController@register')->name('register');

        Route::controller(SendOtpController::class)->group(function () {
            Route::post('/mobile-send-otp', 'mobileSendOtp')
                ->middleware('throttle:api')->name('mobileSendOtp');
            Route::post('/send-otp', 'frontSendOtp')->name('sendOtp');
        });
        Route::controller(LoginController::class)->group(function () {
            Route::post('/login/{type}', 'loginWithOtp')->name('login');
            Route::middleware('auth:sanctum')->group(function () {
                Route::post('/logout', 'logout')->name('logout');
            });
        });


        Route::post('/verify', 'VerificationController@verifyWithOtp')->name('verify.otp');
    });
}); // end namespace \Controlers\Auth

//?? For test API's ============================================================================
Route::namespace('App\Http\Controllers')
    ->prefix('test')
    ->name('test.')
    ->group(function () {
        Route::get('/submitted-forms/answers', 'SubmittedFormController@submittedFormAnswers')->name('get_submitted_forms_answers');

        Route::post('/send-email', 'TestController@sendEmail')->name('send-email');
    }); // end test API's

//?? For Google Sheets =============================================================================
Route::get('dt/candidates-sheets', [App\Http\Controllers\Admin\CandidateController::class, 'dataTableApi'])->name('candidates.datatable-sheets');

//?? For Admin Portal =============================================================================



// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
// Route::group(['middleware' => ['auth']], function () {
// });
