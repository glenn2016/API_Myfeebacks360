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
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\QuestionsfeedbackController;
use App\Http\Controllers\ReponsefeedbackController;
use App\Http\Controllers\QuestionsEvaluationController;
use App\Http\Controllers\ReponsesEvaluationController;








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
//Newsletter
Route::post('/newsletter/create',[NewsletterController::class,'create']);
//Questionsfeedback
Route::get('/Questionsfeedback/listes',[QuestionsfeedbackController::class,'index']);
Route::get('/Questionsfeedbacks/{id}',[QuestionsfeedbackController::class,'show']);

//reponsefeedbacks
Route::get('/reponsefeedback', [ReponsefeedbackController::class, 'index']);
Route::get('/reponsefeedback/{id}',[ReponsefeedbackController::class,'show']);

//Questionsfevaluations
Route::get('/Questionsevaluation/listes',[QuestionsEvaluationController::class,'index']);
Route::get('/Questionsevaluation/{id}',[QuestionsEvaluationController::class,'show']);

//reponseevaluations
Route::get('/reponseevaluation', [ReponsesEvaluationController::class, 'index']);
Route::get('/reponseevaluation/{id}',[ReponsesEvaluationController::class,'show']);



Route::middleware(['auth', 'role:admin'])->group(function () {
    //Newsletter
    Route::get('/newsletters',[NewsletterController::class,'index'])->middleware('auth:api');
    Route::delete('/newsletter/{id}/soft-delete', [NewsletterController::class, 'softDelete'])->middleware('auth:api');
    //ADmin_Authentifie
    Route::get('/user_admin',[Authcontroller::class,'user'])->middleware('auth:api');
    //Contacte
    Route::get('/contacte/{id}',[ContacteController::class,'show'])->middleware('auth:api');
    Route::get('/contactes',[ContacteController::class,'index'])->middleware('auth:api');
    Route::delete('/contacte/{id}/soft-delete', [ContacteController::class, 'softDelete'])->middleware('auth:api');
    //Participant
    Route::post('/participant/create',[Authcontroller::class,'create'])->middleware('auth:api');
    Route::post('/participant/update/{id}',[Authcontroller::class,'update'])->middleware('auth:api');
    Route::get('/participants',[Authcontroller::class,'index'])->middleware('auth:api'); 
    Route::get('/participants/bloquer',[Authcontroller::class,'indexs'])->middleware('auth:api'); 
    Route::get('/participants/{id}',[Authcontroller::class,'show'])->middleware('auth:api');
    Route::post('/participant/{id}/bloquer',[Authcontroller::class,'bloquer'])->middleware('auth:api');
    Route::post('/participant/{id}/debloquer',[Authcontroller::class,'debloquer'])->middleware('auth:api');
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

    //Feddback
    Route::post('/fedddback/create',[FeddbackController::class,'create'])->middleware('auth:api');
    Route::post('/fedddback/update/{id}', [FeddbackController::class, 'update'])->middleware('auth:api');
    Route::delete('/fedddbacks/{id}/soft-delete', [FeddbackController::class, 'softDelete'])->middleware('auth:api');

    //QuestionFeddback
    Route::post('/questionsfeedback/create',[QuestionsfeedbackController::class,'create'])->middleware('auth:api');
    Route::post('/questionsfeedback/update/{id}', [QuestionsfeedbackController::class, 'update'])->middleware('auth:api');
    Route::delete('/questionsfeedbacks/{id}/soft-delete', [QuestionsfeedbackController::class, 'softDelete'])->middleware('auth:api');

    //QuestionEvaluation
    Route::post('/Questionsevaluation/create',[QuestionsEvaluationController::class,'create'])->middleware('auth:api');
    Route::post('/Questionsevaluation/update/{id}', [QuestionsEvaluationController::class, 'update'])->middleware('auth:api');
    Route::delete('/Questionsevaluations/{id}/soft-delete', [QuestionsEvaluationController::class, 'softDelete'])->middleware('auth:api');


    //Evaluations
    Route::post('/evaluation/create',[EvaluationController::class,'create'])->middleware('auth:api');
    Route::post('/evaluation/update/{id}', [EvaluationController::class, 'update'])->middleware('auth:api');
    Route::delete('/evaluations/{id}/soft-delete', [EvenementController::class, 'softDelete'])->middleware('auth:api');
 

  
});

Route::middleware(['auth', 'role:participant'])->group(function () {
    Route::get('/user_participant',[Authcontroller::class,'user'])->middleware('auth:api');


    //ReponseFeddback
    Route::post('/reponsefeedback/create',[ReponsefeedbackController::class,'create'])->middleware('auth:api');
    Route::post('/reponsefeedback/update/{id}', [reponsefeedbackController::class, 'update'])->middleware('auth:api');
    Route::delete('/reponsefeedback/{id}/soft-delete', [reponsefeedbackController::class, 'softDelete'])->middleware('auth:api');


     
    //reponseevaluations
    Route::post('/reponseevaluation/create',[ReponsesEvaluationController::class,'create'])->middleware('auth:api');
    Route::post('/reponseevaluation/update/{id}', [ReponsesEvaluationController::class, 'update'])->middleware('auth:api');
    Route::delete('/reponseevaluation/{id}/soft-delete', [ReponsesEvaluationController::class, 'softDelete'])->middleware('auth:api');








});
