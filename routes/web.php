<?php

use App\Models\Nationality;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/health',function(){
    return 'ok';
});

Auth::routes(['verify' => true]);

// Route::get('/migrate-seed', function () {
//     Artisan::call('migrate:fresh --seed');;
// })->name('seed');

Route::namespace('App\Http\Controllers\Admin')->group(function () {
    Route::group(['middleware' => ['restrict.role:monitor|boss|supervisor|providor']], function () {

        Route::get('/', [App\Http\Controllers\Admin\HomeController::class, 'home'])->name('root');
        Route::get('index/{locale}', [App\Http\Controllers\Admin\HomeController::class, 'lang']);
        Route::get('/profile', [App\Http\Controllers\Admin\HomeController::class, 'profile'])->name('profile');
        Route::controller(MealDashboardController::class)->group(function () {
            Route::get('meals-dashboard/new-tickets','fetchNewTickets')->name('meals-dashboard.new-tickets');
            Route::get('meals-dashboard/new-supports','fetchNewSupports')->name('meals-dashboard.new-supports');
            Route::get('meals-dashboard/{organization_slug}/{date}', 'index')->name('meals-dashboard.index');
            Route::get('meals-dashboard/meals','fetchMeals')->name('meals-dashboard.meals');
            Route::get('meals-dashboard/daily-report/{organization_slug}/{date}/{output?}','dailyOrganizationOperationalSummaryReport')->name('meals-dashboard.daily-report');
        });

        Route::middleware(['auth', 'role:superadmin|admin|organization chairman|government'])->group(function () {

            Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'root'])->name('dashboard');
            Route::get('/dashboard-data', [App\Http\Controllers\Admin\DashboardController::class, 'dashboard_data'])->name('dashboard_data');
            Route::get('/dashboard-looker-studio', [App\Http\Controllers\Admin\DashboardController::class, 'dashboard_looker_studio'])->name('dashboard_looker_studio');
            Route::get('/dashboard-filament', [App\Http\Controllers\Admin\DashboardController::class, 'dashboard_filament'])->name('dashboard_filament');
            Route::get('/api/sector-info', [App\Http\Controllers\Admin\DashboardController::class, 'sector_info'])->name('api.sector_info')->middleware('webLocalization');

            Route::middleware(['auth', 'role:superadmin|admin'])->group(function () {
                Route::resource('facilities', FacilityController::class);
                Route::resource('organization-services', OrganizationServiceController::class);
                Route::resource('organization-countries', CountryOrganizationController::class);
                Route::resource('organizations.services', OrganizationServiceController::class);
                Route::resource('organizations', OrganizationController::class);
                Route::resource('services', ServiceController::class);
                Route::resource('organization-categories', OrganizationCategoryController::class);
                Route::resource('organization-news', OrganizationNewController::class);
                Route::resource('nationality-organizations', NationalityOrganizationController::class);
                Route::resource('order-sectors', OrderSectorController::class);
                Route::resource('monitor-order-sectors', MonitorOrderSectorController::class);
                Route::resource('forms.sections', SectionController::class);
                Route::resource('orders', OrderController::class);
                Route::resource('users', UserController::class);
                Route::resource('questions', QuestionController::class);
                Route::resource('food-weights', FoodWeightController::class);
                Route::resource('forms', FormController::class);
                Route::resource('reason-dangers', ReasonDangerController::class);
                Route::resource('fine-organizations', FineOrganizationController::class);
                //Route::resource('messages', MessageController::class);
                // Route::resource('question-types', QuestionTypeController::class);
                Route::resource('tickets', TicketController::class);
                Route::resource('supports', SupportController::class);
                Route::resource('question-bank-organizations', QuestionBankOrganizationController::class);
                Route::resource('bravos', BravoController::class);
                Route::resource('assists', AssistController::class);
                Route::resource('candidates', CandidateController::class);
                Route::resource('contact_us', ContactUsController::class);
                Route::get('meal-preparations/{organization_id}','MealPreparationController@show')->name('meal-preparation.show');

                // Route::resource('statuses', StatusController::class);
                // Route::resource('regexes', RegexController::class);
                // foreach (collect(crud_routes())->flatten(1) as $key => $controller) {
                //     echo "Route::resource('{$controller['route']}', {$controller['controller']}::class);\n";
                //     echo "Route::get('dt/" . $controller['route'] .", " . $controller['controller'] . "@dataTable')->name('{$controller['route']}.datatable');\n\n";
                // }

                require __DIR__ . '/crud.php';

                Route::get('/not-secured/{any}', [App\Http\Controllers\Admin\HomeController::class, 'index'])->name('index');
            });
        });
        Route::middleware(['auth', 'role:superadmin|admin|organization chairman|government'])->group(function () {

            // dashboad DT
            Route::get("/dt/orders", "OrderController@datatable")->name('orders.datatable');
            Route::get('dt/meals', 'MealController@dataTable')->name('meals.datatable');
            Route::get('dt/tickets', 'TicketController@dataTable')->name('tickets.datatable');
            Route::get('dt/supports', 'SupportController@dataTable')->name('supports.datatable');
            Route::get('dt/assists', 'AssistController@dataTable')->name('assists.datatable');
            Route::get('dt/submitted-forms', 'SubmittedFormController@dataTable')->name('submitted-forms.datatable');
        });
    });
});

Route::view('/docs/wafir', 'scribe_wafir.index')->name('scribe-wafir');
Route::view('/docs/external', 'scribe_external.index')->name('scribe-external');
