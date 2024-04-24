<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\FeddbackController;
use App\Http\Controllers\ContactesContoller;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\EntrepriseController;
use App\Http\Controllers\EvenementController;
use App\Http\Controllers\ContacteController;

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

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {


});

Route::post('login', [AuthController::class ,'login']);
Route::post('logout', [AuthController::class ,'logout']);
Route::post('refresh', [AuthController::class ,'refresh']);
Route::post('me', [AuthController::class ,'me']);


//Participant
Route::get('/users_participants',[Authcontroller::class,'index']);
//Entreprise
Route::get('/entreprise/{id}',[EntrepriseController::class,'detail']);
Route::get('/entreprises',[EntrepriseController::class,'index']);
//Categorie
Route::get('/categories',[CategorieController::class,'index']);
Route::get('/categorie/{id}',[CategorieController::class,'show']);
//Entreprise
Route::get('/entreprises',[EntrepriseController::class,'index']);
//Evenement
Route::get('/evenements',[EvenementController::class,'index']);
Route::get('/evenement/{id}',[EvenementController::class,'show']);
//Feddback
Route::get('/fedddback/{id}',[FeddbackController::class,'show']);
Route::get('/fedddbacks',[FeddbackController::class,'index']);
//Evaluations
Route::get('/evaluation/{id}',[EvaluationController::class,'show']);
Route::get('/evaluations',[EvaluationController::class,'index']);
//Entreprise
Route::get('/entreprises',[EntrepriseController::class,'index']);
Route::get('/entreprise/{id}',[EntrepriseController::class,'show']);
//Contacte
Route::post('/contacte/create',[ContacteController::class,'create']);


Route::middleware(['auth', 'role:admin'])->group(function () {
    //ADmin_Authentifie
    Route::get('/user_admin',[Authcontroller::class,'user'])->middleware('auth:api');
    //Contacte
    Route::get('/contacte/{id}',[ContacteController::class,'show'])->middleware('auth:api');
    Route::get('/contactes',[ContacteController::class,'index'])->middleware('auth:api');
    Route::delete('/contacte/{id}/soft-delete', [ContacteController::class, 'delete'])->middleware('auth:api');
    //Participant
    Route::post('/participant/create',[Authcontroller::class,'create'])->middleware('auth:api');
    //Categorie
    Route::post('/categorie/create',[CategorieController::class,'create'])->middleware('auth:api');
    Route::post('/categorie/update/{id}', [CategorieController::class, 'update'])->middleware('auth:api');
    Route::delete('/categories/{id}/soft-delete', [CategorieController::class, 'softDelete'])->middleware('auth:api');

    //Entreprise
    Route::post('/entreprise/create',[EntrepriseController::class,'create'])->middleware('auth:api');
    Route::post('/entreprise/update/{id}', [EntrepriseController::class, 'update'])->middleware('auth:api');
    Route::delete('/entreprises/{id}/soft-delete', [EntrepriseController::class, 'softDelete'])->middleware('auth:api');
    //Role
    Route::get('/roles',[RoleController::class,'index'])->middleware('auth:api');
    //Evenement
    Route::post('/evenement/create',[EvenementController::class,'create'])->middleware('auth:api');
    Route::post('/evenement/update/{id}', [EvenementController::class, 'update'])->middleware('auth:api');
    Route::delete('/evenements/{id}/soft-delete', [EvenementController::class, 'softDelete'])->middleware('auth:api');
});

Route::middleware(['auth', 'role:participant'])->group(function () {
    Route::get('/user_participant',[Authcontroller::class,'user'])->middleware('auth:api');
    //Feddback
    Route::post('/fedddback/create',[FeddbackController::class,'create'])->middleware('auth:api');
    Route::post('/fedddback/update/{id}', [FeddbackController::class, 'update'])->middleware('auth:api');
    Route::delete('/fedddbacks/{id}/soft-delete', [FeddbackController::class, 'softDelete'])->middleware('auth:api');

    //Evaluations
    Route::post('/evaluation/create',[EvaluationController::class,'create'])->middleware('auth:api');
    Route::post('/evaluation/update/{id}', [EvaluationController::class, 'update'])->middleware('auth:api');
    Route::delete('/evaluations/{id}/soft-delete', [EvenementController::class, 'softDelete'])->middleware('auth:api');
});