<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;

use App\Http\Controllers\SuperAdmin\UserController as AdminTypeUserController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\UserActivityController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');


// Admin/Super Admin Auth
Route::any('signin', [AuthController::class, 'signin'])->name('signin');
Route::get('signout', [AuthController::class, 'signOut'])->name('signout');

// Route::get('/home', 'HomeController@index')->name('home');

//Super Admin Routes
Route::group(['middleware' => ['SuperAdminUserType']], function () {
    Route::get('admins', [AdminTypeUserController::class, 'index'])->name('admins');
    Route::any('add-admin', [AdminTypeUserController::class, 'addUser'])->name('add-admin');
    Route::post('save-admin', [AdminTypeUserController::class, 'saveUser'])->name('save-admin');
    Route::get('delete-admin/{id}', [AdminTypeUserController::class, 'deleteUser'])->name('delete-admin');
    Route::any('change-password/{id}', [AdminTypeUserController::class, 'changePassword'])->name('change-password');
});

Route::group(['middleware' => ['CommonUserType']], function ()
{
    // user
    Route::any('users', [UserController::class, 'index'])->name('users');
    Route::get('add-user', [UserController::class, 'addUser'])->name('add-user');
    Route::post('save-user', [UserController::class, 'saveUser'])->name('save-user');
    Route::get('delete-user/{id}', [UserController::class, 'deleteUser'])->name('delete-user');
    Route::any('user-change-password/{id}', [UserController::class, 'changePassword'])->name('user-change-password');

    // Acitivity
    Route::get('activities', [ActivityController::class, 'index'])->name('activities');
    Route::get('add-activity', [ActivityController::class, 'add'])->name('add-activity');
    Route::post('save-activity', [ActivityController::class, 'save'])->name('save-activity');
    Route::get('edit-activity/{id}', [ActivityController::class, 'edit'])->name('edit-activity');
    Route::patch('update-activity', [ActivityController::class, 'update'])->name('update-activity');
    Route::get('update-activity-status/{id}/{status}', [ActivityController::class, 'updateStatus'])->name('update-activity-status');

    // User Acitivity
    Route::get('user-activities/{id}', [UserActivityController::class, 'index'])->name('user-activities');
    Route::get('add-user-activity/{user_id}', [UserActivityController::class, 'add'])->name('add-user-activity');
    Route::post('save-user-activity', [UserActivityController::class, 'save'])->name('save-user-activity');
    Route::get('edit-user-activity/{id}', [UserActivityController::class, 'edit'])->name('edit-user-activity');
    Route::put('update-user-activity', [UserActivityController::class, 'update'])->name('update-user-activity');
    Route::any('delete-user-acitivity/{id}', [UserActivityController::class, 'delete'])->name('delete-user-activity');

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::fallback(function () {
    return redirect()->route('welcome');
});