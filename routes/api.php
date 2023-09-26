<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserCourseController;
use App\Http\Controllers\UserRegistrationController;
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

Route::group(['prefix' => 'v1/user'], function () {
    Route::post('/login', [UserController::class, 'login']);
    Route::group(['middleware' => ['auth_user']], function () {
        Route::get('/registered-courses', [UserRegistrationController::class, "index"]);
        Route::post('/register-courses', [UserRegistrationController::class, "request"]);
    });
});
Route::group(['prefix' => 'v1/admin'], function () {
    Route::post('/login', [AdminController::class, 'login']);
    // Route::group(['middleware' => ['auth_admin']], function () {
    Route::get('/users/requested-courses', [UserRegistrationController::class, "requests"]);
    Route::get('/user/{id}/registered-courses', [UserRegistrationController::class, "check"]);
    Route::post('/user/{id}/register-courses', [UserRegistrationController::class, "approve"]);
    Route::post('/results', [UserCourseController::class, 'post_results']);
    // });
});
