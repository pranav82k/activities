<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\User\RegisterController;
use App\Http\Controllers\API\User\LoginController;
use App\Http\Controllers\API\User\ForgotController;
use App\Http\Controllers\API\User\ProfileController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


    // Alternate Way - Route::post('customer-register','App\Http\Controllers\API\Customer\RegisterController@customer_register');

// User Authentication Part Start
    Route::post('registration', [RegisterController::class, 'registration']);
    Route::post('login', [LoginController::class, 'login']);

    // As Email Account have been not setup so email will not work.
    Route::post('forgot-password', [ForgotController::class, 'forgot']);
    Route::post('reset-password', [ForgotController::class, 'resetPassword']);
// User Authentication Part Ends

    Route::group(['middleware' => 'checkAppAuth'], function()
    {
        // User Profile
            Route::post('logout', [LoginController::class, 'logout']);
            Route::get('profile', [ProfileController::class, 'profile']);
            Route::post('profile-update', [ProfileController::class, 'profileupdate']);
            Route::post('change-password', [ProfileController::class, 'changePassword']);
            Route::get('activity-list', [ProfileController::class, 'activityList']);
        // User Profile Ends
    });