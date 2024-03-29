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
Route::post('verify',[\App\Http\Controllers\AuthController::class,'verify']);
Route::post('authotp',[\App\Http\Controllers\AuthController::class,'authotp']);
Route::post('login',[\App\Http\Controllers\AuthController::class,'login']);
Route::post('logout',[\App\Http\Controllers\AuthController::class,'logoutApi']);
Route::post('request_reset_password',[\App\Http\Controllers\AuthController::class,'request_reset_password']);
Route::post('reset_password',[\App\Http\Controllers\AuthController::class,'reset_password']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('get-user',[AuthController::class,'user_details']);
    Route::post('company',[CompanyController::class,'store']);
    Route::post('hosting_details',[CompanyController::class,'hosting_details']);
    Route::post('language',[CompanyController::class,'language']);
    //user centered
    Route::get('recommenduser',[\App\Http\Controllers\UserCenteredController::class,'recommenduser']);
    Route::post('application',[\App\Http\Controllers\UserCenteredController::class,'application']);

});
Route::get('select_company',[CompanyController::class,'select_company']);
Route::get('select_language',[CompanyController::class,'select_language']);
Route::get('showrandom',[CompanyController::class,'showrandom']);
Route::get('showothers',[CompanyController::class,'showothers']);
Route::get('besthosting',[CompanyController::class,'besthosting']);
Route::get('specificlanguages',[CompanyController::class,'specificlanguages']);

//show companydetails
Route::get('companydetails/{id}',[CompanyController::class,'companydetails']);
Route::get('companydetailslanguages/{id}',[CompanyController::class,'companydetailslanguages']);
Route::get('showmoreCompanydetails/{name}',[CompanyController::class,'showmoreCompanydetails']);
//search
Route::get('/search',[\App\Http\Controllers\GeneralUserController::class,'search']);

//show best frontend
Route::get('bestfrontend',[\App\Http\Controllers\GeneralUserController::class,'bestfrontend']);
Route::get('bestbackend',[\App\Http\Controllers\GeneralUserController::class,'bestbackend']);

//send email notification
Route::get('sendmail',[\App\Http\Controllers\MailController::class,'index']);

//show all companys
Route::get('show_all_companies',[\App\Http\Controllers\GeneralUserController::class,'show_all_companies']);
Route::get('hosting_details',[\App\Http\Controllers\GeneralUserController::class,'hosting_details']);
//edit companys
Route::post('update_company/{id}',[\App\Http\Controllers\GeneralUserController::class,'update_company']);
Route::post('update_hosting_details/{id}',[\App\Http\Controllers\GeneralUserController::class,'update_hosting_details']);
Route::post('delete_host_details/{id}',[\App\Http\Controllers\GeneralUserController::class,'delete_host_details']);
Route::get('delete_company/{id}',[\App\Http\Controllers\GeneralUserController::class,'delete_company']);

//show companies with the language
Route::get('show_all_companies/{name}',[CompanyController::class,'show_all_companies']);
Route::get('editit/{id}',[CompanyController::class,'edit_company']);
Route::get('edithostingdeail/{id}',[CompanyController::class,'edithostingdeail']);
//all_frontend_host
Route::get('all_frontend_host',[CompanyController::class,'all_frontend_host']);
//all_backend_host
Route::get('all_backend_host',[CompanyController::class,'all_backend_host']);
Route::get('count',[CompanyController::class,'count']);
//Applications
Route::get('show_application',[\App\Http\Controllers\AdminController::class,'show_application']);
//accept application
Route::get('accept_application/{id}',[\App\Http\Controllers\AdminController::class,'accept_application']);
