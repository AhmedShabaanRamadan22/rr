<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;


Route::namespace('App\Http\Controllers\Organization')->group(function () {
    
    Route::controller(OrganizationSettingController::class)->group(function(){
        Route::get('{organization_id}/settings','show')->name('settings');
        Route::get('{organization_id}/settings/{slug}','showVia')->name('show-via');
    });
});
Route::namespace('App\Http\Controllers\Organization\Admin')->group(function () {



    Route::middleware(['auth','organizationMiddleware'])->group(function () {
        
        Route::middleware('organizationAdmin')->group(function () {
            Route::controller(DashboardController::class)->group(function(){
               Route::get('/','index')->name('dashboard'); 
               Route::get('/profile','profile')->name('profile'); 
            });
        });

    });
});