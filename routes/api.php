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
    Route::post('language',[CompanyController::class,'language']);
    //user centered
    Route::get('recommendlanguage',[\App\Http\Controllers\UserCenteredController::class,'recommendlanguage']);
    Route::get('languageloved',[\App\Http\Controllers\UserCenteredController::class,'languageloved']);
    Route::get('languagealsoloveds',[\App\Http\Controllers\UserCenteredController::class,'languagealsoloveds']);

});
Route::get('select_company',[CompanyController::class,'select_company']);
Route::get('select_language',[CompanyController::class,'select_language']);
Route::get('showrandom',[CompanyController::class,'showrandom']);
Route::get('showothers',[CompanyController::class,'showothers']);
Route::get('besthosting',[CompanyController::class,'besthosting']);

//show best frontend
Route::get('bestfrontend',[\App\Http\Controllers\GeneralUserController::class,'bestfrontend']);
Route::get('bestbackend',[\App\Http\Controllers\GeneralUserController::class,'bestbackend']);

//user centered

