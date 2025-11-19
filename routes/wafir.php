<?php

use App\Http\Controllers\External\GeneralDataController;
use App\Http\Controllers\External\Wafir\{
    AuthController,
    FacilityController,
    MonitorController
};
use Illuminate\Support\Facades\Route;


Route::namespace('App\Http\Controllers\External\Wafir')->group(function () {

    Route::controller(AuthController::class)->group(function(){
        Route::post('check-user','checkUser')->name('check-user');
    });

    Route::controller(FacilityController::class)->group(function(){
        Route::get('facilities','index')->name('facilities.index');
    });

    Route::prefix('users/{user}')
        ->name('users.')
        ->scopeBindings()
        ->group(function () {
            Route::get('facilities', [FacilityController::class, 'indexByUser'])->name('facilities.index');
            Route::get('facilities/{facility}', [FacilityController::class, 'show'])->name('facilities.show');
        });

        Route::controller(MonitorController::class)->group(function(){
            Route::get('monitors', [MonitorController::class, 'index'])->name('monitors.index');
            Route::get('monitors/{monitor}', [MonitorController::class, 'show'])->name('monitors.show');
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

        Route::get('cities/{city}/districts', 'districts')->name('districts');
    });
