<?php

use App\Http\Controllers\External\{
    AuthController,
    FacilityController,
    OrderController,
    UserController,
    FacilityEmployeeController,
    GeneralDataController,
};
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| External API Routes
|--------------------------------------------------------------------------
|
| All routes in this file are for external clients using API tokens.
| Middleware 'external_api' handles:
|   - token verification
|   - logging requests/responses
|   - per-client throttling (dynamic for read/write)
|
*/

// ----------------------
// Auth Routes
// ----------------------
Route::controller(AuthController::class)->group(function () {
    Route::post('register', 'register')->name('register');
    Route::post('send-otp', 'createOtp')->name('send-otp');
    Route::post('login', 'login')->name('login');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', 'logout')->name('logout');
    });
});

// ----------------------
// User CRUD
// ----------------------
Route::apiResource('users', UserController::class)
    ->only(['index', 'show']);

// ----------------------
// Facilities CRUD
// ----------------------
Route::controller(FacilityController::class)->name('facilities.')->group(function () {
    Route::get('facilities', 'index')->name('index');
    Route::get('facilities/{facility}', 'show')->name('show');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('facilities', [FacilityController::class, 'store'])->name('store');
    });
});

// ----------------------
// Orders CRUD
// ----------------------
Route::controller(OrderController::class)
    ->prefix('facilities/{facility}/')
    ->name('facilities.orders.')
    ->scopeBindings()
    ->group(function () {
        Route::get('orders', 'indexByFacility')->name('index');
        Route::get('orders/{order}', 'show')->name('show');

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('orders', 'store')->name('store');
            Route::patch('orders/{order}/cancel', 'cancel')->name('cancel');
        });
    });

// ----------------------
// Facility Employees CRUD
// ----------------------
Route::controller(FacilityEmployeeController::class)
    ->prefix('facilities/{facility}/')
    ->name('facilities.employees.')
    ->group(function () {
        Route::get('employees', 'indexByFacility')->name('index');
        Route::get('employees/{employee}', 'show')->name('show');

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('employees', 'store')->name('store');
        });
    });

// ----------------------
// General Data
// ----------------------
Route::controller(GeneralDataController::class)
    ->prefix('general/')
    ->name('general.')
    ->group(function () {
        Route::get('cities', 'cities')->name('cities');
        Route::get('countries', 'countries')->name('countries');
        Route::get('cities/{city}/districts', 'districts')->name('districts');
        Route::get('banks', 'banks')->name('banks');
        Route::get('facility-employee-positions', 'facilityEmployeePositions')
            ->name('facility-employee-positions');
        Route::get('attachment-labels', 'attachmentLabels')->name('attachment-labels');
    });


// ----------------------
// User related data
// ----------------------
Route::middleware('auth:sanctum')->prefix('my')->name('my.')->group(function () {
    Route::get('facilities', [FacilityController::class, 'indexMyFacilities'])->name('facilities');
});
