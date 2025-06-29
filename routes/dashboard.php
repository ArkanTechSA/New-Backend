<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
*/

Route::middleware(['admin.dashboard'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::post('set-locale', [DashboardController::class, 'setLocale'])->name('admin.setLocale');

    Route::post('logout', [AuthController::class, 'logout'])->name('admin.logout');

    Route::get('profile/edit', [AuthController::class, 'editProfile'])->name('admin.profile.edit');
    Route::POST('profile/update', [AuthController::class, 'updateProfile'])->name('admin.profile.update');
    Route::get('change-password', [AuthController::class, 'changePasswordForm'])->name('admin.change-password.form');
    Route::post('change-password', [AuthController::class, 'changePassword'])->name('admin.change-password');

    Route::get('providers', [DashboardController::class, 'serviceProviders'])->name('admin.providers');
    Route::get('requesters', [DashboardController::class, 'serviceRequesters'])->name('admin.requesters');

    foreach (['providers', 'requesters'] as $prefix) {
        Route::prefix($prefix)->group(function () use ($prefix) {
            Route::get('/create', [DashboardController::class, 'create'])->name("{$prefix}.create");
            Route::post('/', [DashboardController::class, 'store'])->name("{$prefix}.store");
            Route::get('/search-providers', [DashboardController::class, 'searchProviders'])->name('admin.providers.search');
            Route::get('/search-requesters', [DashboardController::class, 'searchRequesters'])->name('admin.requesters.search');
        });
    }

    Route::get('users/{user}/edit', [DashboardController::class, 'edit'])->name('users.edit');
    Route::put('users/{user}', [DashboardController::class, 'update'])->name('users.update');
    Route::delete('users/{user}', [DashboardController::class, 'destroy'])->name('users.destroy');

    Route::get('/admin/get-countries', [DashboardController::class, 'getCountries'])->name('admin.getCountries');
    Route::get('/regions/{countryId}', [DashboardController::class, 'getRegions'])->name('admin.getRegions');
    Route::get('/cities/{regionId}', [DashboardController::class, 'getCities'])->name('admin.getCities');

    Route::get('newsletters', [DashboardController::class, 'newsletterIndex'])->name('admin.newsletters');
    Route::post('newsletters', [DashboardController::class, 'newsletterPost'])->name('admin.newsletters.send');
});
