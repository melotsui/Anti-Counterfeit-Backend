<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\SubDistrictController;
use App\Http\Controllers\ReportController;

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


Route::resource('/categories', CategoryController::class);
Route::resource('/districts', DistrictController::class);
Route::resource('/sub-districts', SubDistrictController::class);
Route::get('/sub-districts/getSubDistrictsByDistrictId/{district_id}', [SubDistrictController::class, 'getSubDistrictsByDistrictId']);
Route::get('/reports/{report_id}', [ReportController::class, 'show']);
Route::post('/users/forgotPassword', [UserController::class, 'forgotPassword']);
Route::post('/users/resetPassword', [UserController::class, 'resetPassword']);


Route::group(['middleware' => ['jwt.auth']], function () {
    Route::put('/users/editProfile', [UserController::class, 'editProfile']);
    Route::post('/reports/history', [ReportController::class, 'history']);
    Route::apiResources([
        '/users' => UserController::class,
        '/reports' => ReportController::class
    ]);
    Route::post('/reports/uploadFile', [ReportController::class, 'uploadFile']);
    Route::post('/users/verifyEmail', [UserController::class, 'verifyEmail']);

});
Route::post('/reports/search', [ReportController::class, 'search']);
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);
Route::post('refresh', [AuthController::class, 'refresh']);
Route::post('me', [AuthController::class, 'me']);
