<?php

use App\Http\Controllers\Platform\Api\AuthController;
use App\Http\Controllers\Platform\Api\CountryController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('check-phone', [AuthController::class, 'sendOtp']);
        Route::post('resend-Otp', [AuthController::class, 'resendOtp']);
        Route::post('confirm-phone', [AuthController::class, 'verifyOtp']);
        Route::post('register', [AuthController::class, 'completeRegistration']);
        Route::post('login', [AuthController::class, 'login']);
        Route::post('forget-password', [AuthController::class, 'sendResetCode']);
        Route::post('reset-password', [AuthController::class, 'verifyCodeAndResetPassword']);
    });

});
Route::get('general_data/countries', [CountryController::class, 'index']);
