<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
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
Route::post('register',[\App\Http\Controllers\AuthController::class,'register']);
Route::post('login',[\App\Http\Controllers\AuthController::class,'login']);
Route::post('logout',[\App\Http\Controllers\AuthController::class,'logoutApi']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('get-user',[AuthController::class,'user_details']);
    Route::post('company',[CompanyController::class,'store']);
    Route::post('hosting_details',[CompanyController::class,'hosting_details']);
});
Route::get('showhosting',[CompanyController::class,'show']);
Route::get('showhostingphp',[CompanyController::class,'showphp']);


