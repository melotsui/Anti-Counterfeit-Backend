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


Route::group(['middleware' => ['jwt.auth']], function() {
    Route::apiResources([
        '/users' => UserController::class,
    ]);
    Route::resource('/reports', ReportController::class);
});

Route::resource('/categories', CategoryController::class);
Route::resource('/districts', DistrictController::class);
Route::resource('/sub-districts', SubDistrictController::class);
Route::get('/sub-districts/getSubDistrictsByDistrictId/{district_id}', [SubDistrictController::class, 'getSubDistrictsByDistrictId']);
Route::post('/reports/uploadFile', [ReportController::class, 'uploadFile']);

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);
Route::post('refresh', [AuthController::class, 'refresh']);
Route::post('me', [AuthController::class, 'me']);
