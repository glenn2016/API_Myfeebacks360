<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\FeddbackController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\EntrepriseController;
use App\Http\Controllers\EvenementController;
use App\Http\Controllers\ContacteController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\QuestionsfeedbackController;
use App\Http\Controllers\ReponsefeedbackController;
use App\Http\Controllers\QuestionsEvaluationController;
use App\Http\Controllers\ReponsesEvaluationController;
use App\Http\Controllers\EvaluationQuestionReponseEvaluationController;
use App\Http\Controllers\ContactAbonementController;
use App\Http\Controllers\EntrepriseAbonementController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ImportUserController;
use App\Http\Controllers\AbonnementController;
use App\Http\Controllers\AbonnementUtlisateursController;



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
    'middleware' => 'a  pi',
    'prefix' => 'auth'

], function ($router) {
});
Route::post('login', [AuthController::class ,'login']);
Route::post('logout', [AuthController::class ,'logout']);
Route::post('refresh', [AuthController::class ,'refresh']);
Route::post('me', [AuthController::class ,'me']);
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::post('/password/reset', [ForgotPasswordController::class, 'resetPassword']);


//Abonement
Route::get('/listes/abonements',[AbonnementController::class,'index']);
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
Route::get('/evaluations',[EvaluationQuestionReponseEvaluationController::class,'indexs']);
//Entreprise
Route::get('/entreprises',[EntrepriseController::class,'index']);
Route::get('/entreprise/{id}',[EntrepriseController::class,'show']);
//Contacte
Route::post('/contacte/create',[ContacteController::class,'create']);
//ContactAbonementC
Route::post('/ContactAbonementC/create/',[ContactAbonementController::class,'create']);
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
Route::get('/questions-feedbacks/{evenement_id}',[QuestionsfeedbackController::class,'evenementquestion']);
//evaluation
//Route::get('/categories/questions-and-reponses/{CategorieId}', [ReponsesEvaluationController::class, 'questionsAndReponsesByCategory']);

Route::get('/categories/questions-and-reponses/{CategorieId}/{evaluationId}', [ReponsesEvaluationController::class, 'questionsAndReponsesByCategoryAndEvaluation']);

Route::middleware(['auth', 'role:admin'])->group(function () {
    //ADmin_Authentifie
    Route::get('/user_admin',[Authcontroller::class,'user'])->middleware('auth:api');
    //Participant
    Route::post('/participant/create',[Authcontroller::class,'create'])->middleware('auth:api');
    Route::post('/participant/update/{id}',[Authcontroller::class,'update'])->middleware('auth:api');
    Route::get('/participants',[Authcontroller::class,'index'])->middleware('auth:api'); 
    Route::get('/liste/participants/bloquer',[Authcontroller::class,'indexParticipantsBolquer'])->middleware('auth:api'); 
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
    Route::get('/evenement/questions-reponses/{evenement_id}', [ReponsefeedbackController::class, 'evenementquestionreponse']);
    Route::post('/archiver/evenement/{id}',[EvenementController::class,'archiver'])->middleware('auth:api');
    Route::get('/listes/evenements/archives',[EvenementController::class,'indexarchiver'])->middleware('auth:api');
    //Feddback
    Route::post('/fedddback/create',[FeddbackController::class,'create'])->middleware('auth:api');
    Route::post('/fedddback/update/{id}', [FeddbackController::class, 'update'])->middleware('auth:api');
    Route::delete('/fedddbacks/{id}/soft-delete', [FeddbackController::class, 'softDelete'])->middleware('auth:api');
    //QuestionFeddback
    Route::post('/questionsfeedback/create',[QuestionsfeedbackController::class,'create'])->middleware('auth:api');
    //
    Route::post('/questionsfeedback/update/{id}', [QuestionsfeedbackController::class, 'update'])->middleware('auth:api');
    Route::delete('/questionsfeedbacks/{id}/soft-delete', [QuestionsfeedbackController::class, 'softDelete'])->middleware('auth:api');
    //QuestionEvaluation
    //Creation d'evaluation des question et des reponses
    Route::post('/Questionsevaluation/create',[QuestionsEvaluationController::class,'create'])->middleware('auth:api');
    Route::post('/Questions/reponse/evaluation/update/{id}', [QuestionsEvaluationController::class, 'update'])->middleware('auth:api');
    Route::delete('/Questionsevaluations/{id}/soft-delete', [QuestionsEvaluationController::class, 'softDelete'])->middleware('auth:api');
    //Evaluations
    Route::post('/evaluation/create',[EvaluationController::class,'create'])->middleware('auth:api');
    Route::post('/evaluation/update/{id}', [EvaluationController::class, 'update'])->middleware('auth:api');
    Route::delete('/evaluations/{id}/soft-delete', [EvaluationController::class, 'softDelete'])->middleware('auth:api');
    Route::put('/archiver/evaluation/{id}',[EvaluationController::class,'archiver'])->middleware('auth:api');
    Route::get('/listes/evaluation/archives',[EvaluationController::class,'indexarchiver'])->middleware('auth:api');
    Route::get('/users/evaluations/{id}', [EvaluationQuestionReponseEvaluationController::class, 'getUserEvaluations'])->middleware('auth:api');
    //importation des participsants
    Route::post('/import/participants', [ImportUserController::class, 'import'])->middleware('auth:api');
    //listes totala des utlisateurs ayan passer une evaluation
    Route::get('/listes/total/utlisateur/evaluer', [EvaluationQuestionReponseEvaluationController::class, 'getAllEvaluators'])->middleware('auth');
});
Route::middleware(['auth', 'role:participant'])->group(function () {
    Route::get('/participant/entreprse/{categoryId}',[EvaluationQuestionReponseEvaluationController::class,'showUsersWithSimilarEntrepriseAndCategory'])->middleware('auth:api');
    //ReponseFeddback
    Route::post('/reponsefeedback/create',[ReponsefeedbackController::class,'create'])->middleware('auth:api');
    Route::post('/reponsefeedback/update/{id}', [reponsefeedbackController::class, 'update'])->middleware('auth:api');
    Route::delete('/reponsefeedback/{id}/soft-delete', [reponsefeedbackController::class, 'softDelete'])->middleware('auth:api');
    //reponseevaluationsd
    Route::post('/reponseevaluation/create',[ReponsesEvaluationController::class,'create'])->middleware('auth:api');
    Route::post('/reponseevaluation/update/{id}', [ReponsesEvaluationController::class, 'update'])->middleware('auth:api');
    Route::delete('/reponseevaluation/{id}/soft-delete', [ReponsesEvaluationController::class, 'softDelete'])->middleware('auth:api');
    //evaluation
    Route::post('/evaluation/create',[EvaluationQuestionReponseEvaluationController::class,'create'])->middleware('auth:api');
    Route::get('/evenements/admin',[EvenementController::class,'indexevenement'])->middleware('auth:api');
    Route::get('evaluations/admin',[EvaluationController::class,'indexevaluation'])->middleware('auth:api');
    Route::get('/categories/admin',[EvaluationController::class,'indexcategorie'])->middleware('auth:api');
    //Listes evaluation recu
    Route::get('/liste/evaluation/recu', [EvaluationQuestionReponseEvaluationController::class, 'getEvaluatorsList'])->middleware('auth');
    //Listes  participants evaluer
    Route::get('/liste/user/evaluer', [EvaluationQuestionReponseEvaluationController::class, 'getEvaluerrsList'])->middleware('auth');
    Route::get('/listes/particpants/evaluateur/{userId}', [EvaluationQuestionReponseEvaluationController::class, 'getEvaluatorsParticipants'])->middleware('auth');
    //Evenement ou vous avez donner aavis
    Route::get('/listes/evenements/evaluer', [EvenementController::class, 'getEvenementsForCurrentUser'])->middleware('auth');
    Route::get('/evenement/feedback/{id}', [EvenementController::class, 'getFeedbackForEvent'])->middleware('auth');
    Route::get('/evaluated-users', [EvaluationQuestionReponseEvaluationController::class, 'getEvaluators']);


});

Route::middleware(['auth', 'role:SuperAdmin'])->group(function () {
    //Newsletter
    Route::get('/newsletters',[NewsletterController::class,'index'])->middleware('auth:api');
    Route::delete('/newsletter/{id}/soft-delete', [NewsletterController::class, 'softDelete'])->middleware('auth:api');
    //ContactAbonement
    Route::get('/ContactAbonement/{id}',[ContactAbonementController::class,'show'])->middleware('auth:api');
    Route::get('/ContactAbonementCs',[ContactAbonementController::class,'index'])->middleware('auth:api');
    Route::delete('/ContactAbonements/{id}/soft-delete', [ContactAbonementController::class, 'softDelete'])->middleware('auth:api');

    Route::get('/contact-abonements/{id}', [ContactAbonementController::class, 'getAbonnementByContactAbonnementId'])->middleware('auth:api');

    //Contacte
    Route::get('/contacte/{id}',[ContacteController::class,'show'])->middleware('auth:api');
    Route::get('/contactes',[ContacteController::class,'index'])->middleware('auth:api');
    Route::delete('/contacte/{id}/soft-delete', [ContacteController::class, 'softDelete'])->middleware('auth:api');
    //Admin
    Route::post('/admin/create',[Authcontroller::class,'createAdmin'])->middleware('auth:api');
    Route::post('/admin/update/{id}',[Authcontroller::class,'update'])->middleware('auth:api');
    Route::get('/listes/admins',[Authcontroller::class,'indexAdmin'])->middleware('auth:api'); 
    Route::get('/listes/admin/bloquer',[Authcontroller::class,'indexAdminBloquer'])->middleware('auth:api'); 
    Route::get('/admins/{id}',[Authcontroller::class,'show'])->middleware('auth:api');
    Route::post('/admin/{id}/bloquer',[Authcontroller::class,'bloquer'])->middleware('auth:api');
    Route::post('/admin/{id}/debloquer',[Authcontroller::class,'debloquer'])->middleware('auth:api');
    //EntrepriseAbanement
    Route::post('/entrepriseAbonement/create',[EntrepriseAbonementController::class,'create'])->middleware('auth:api');
    Route::post('/entrepriseAbonement/update/{id}', [EntrepriseAbonementController::class, 'update'])->middleware('auth:api');
    Route::delete('/entrepriseAbonements/{id}/soft-delete', [EntrepriseAbonementController::class, 'softDelete'])->middleware('auth:api');
    Route::get('/listes/entrepriseAbonement',[EntrepriseAbonementController::class,'index'])->middleware('auth:api');
    Route::get('/entrepriseAbonement/{id}',[EntrepriseAbonementController::class,'show'])->middleware('auth:api');
    //Abonement
    Route::get('/abonment/{id}',[AbonnementController::class,'show'])->middleware('auth:api');
    Route::delete('/abonements/{id}/soft-delete', [AbonnementController::class, 'softDelete'])->middleware('auth:api');
    Route::post('/abonment/update/{id}',[AbonnementController::class,'update'])->middleware('auth:api');
    Route::post('/abonment/create',[AbonnementController::class,'create'])->middleware('auth:api');
    //AbonementUtilisateurs
    Route::get('/abonnement-utilisateurs', [AbonnementUtlisateursController::class, 'index']);
    Route::put('/abonnement-utilisateurs/update/{id}', [AbonnementUtlisateursController::class, 'update']);
    Route::put('/abonnement-utilisateurs/create', [AbonnementUtlisateursController::class, 'create']); 
});