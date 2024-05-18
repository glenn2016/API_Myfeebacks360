<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Categorie;
use App\Models\Evaluation;
use App\Models\QuestionsEvaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\EvaluationQuestionReponseEvaluation;
use Illuminate\Support\Facades\Validator;

class EvaluationQuestionReponseEvaluationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function showUsersWithSimilarEntrepriseAndCategory($categoryId)
    {
        try {
            // Récupérer l'entreprise de la personne connectée
            $user = Auth::user();
            $entrepriseDePersonneConnectee = $user->entreprise;
    
            // Vérifier si l'utilisateur connecté a une entreprise
            if (!$entrepriseDePersonneConnectee) {
                return response()->json([
                    'message' => 'L\'utilisateur connecté n\'a pas d\'entreprise associée.',
                    'status' => '400'
                ], 400);
            }
    
            // Récupérer la catégorie
            $categorie = Categorie::find($categoryId);
    
            // Vérifier si la catégorie existe
            if (!$categorie) {
                return response()->json([
                    'message' => 'La catégorie spécifiée est introuvable.',
                    'status' => '404'
                ], 404);
            }
    
            // Récupérer les utilisateurs avec les mêmes conditions d'entreprise, de catégorie et de usercreate
            $participants = User::whereHas('entreprise', function ($query) use ($entrepriseDePersonneConnectee) {
                    $query->where('nom', '=', $entrepriseDePersonneConnectee->nom);
                })
                ->where('id', '!=', $user->id)
                ->where('categorie_id', $categoryId) // Assurez-vous que 'categorie_id' est la colonne qui référence la catégorie dans la table users
                ->where('usercreate', $user->usercreate)
                ->get();
    
            return response()->json([
                'participants' => $participants,
                'status' => '200'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la récupération des utilisateurs',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    public function index()
    {
        //
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'evaluation.*.reponse_id' => 'required|numeric',
                'evaluer_id' => 'required|numeric|max:255',
                'niveau' => 'required|string|max:255',
                'commentaire' => 'required|string|max:255',
            ]);

            $user = Auth::user();
            $validatedData = $validator->validated();
            $evaluations = [];

            foreach ($validatedData['evaluation'] as $evaluationData) {
                $evaluation = new EvaluationQuestionReponseEvaluation();
                $evaluation->reponse_id = $evaluationData['reponse_id'];
                $evaluation->evaluatuer_id = $user->id;
                $evaluation->evaluer_id = $validatedData['evaluer_id'];
                $evaluation->niveau = $validatedData['niveau'];
                $evaluation->commentaire = $validatedData['commentaire'];
                $evaluation->save();
                $evaluations[] = $evaluation;
            }

            return response()->json([
                'message' => 'Évaluations créées avec succès',
                'evaluations' => $evaluations,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la création d\'une évaluation ',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    /**
     * Store a newly created resource in storage.
     */


     public function getUserEvaluations($id)
     {
         // Récupérer toutes les évaluations de l'utilisateur
         $evaluationReponses = EvaluationQuestionReponseEvaluation::where('evaluatuer_id', $id)->get();
     
         // Créer un tableau pour stocker les évaluations groupées par "evaluation_id"
         $groupedEvaluations = [];
     
         // Parcourir toutes les évaluations de l'utilisateur
         foreach ($evaluationReponses as $evaluationReponse) {
             // Récupérer l'évaluation associée à la réponse
             $evaluation = Evaluation::find($evaluationReponse->reponse->questionsEvaluation->evaluation_id);
     
             // Vérifier si l'évaluateur ID existe déjà dans le tableau groupé
             if (!isset($groupedEvaluations[$evaluationReponse->evaluer_id][$evaluation->id])) {
                 // Si ce n'est pas le cas, ajouter une nouvelle entrée au tableau groupé
                 $groupedEvaluations[$evaluationReponse->evaluer_id][$evaluation->id] = [
                     'evaluer_id' => $evaluationReponse->evaluer_id,
                     'evaluation' => $evaluation,
                     'commentaire' => $evaluationReponse->commentaire,
                     'niveau' => $evaluationReponse->niveau,
                     'questions_reponses' => []
                 ];
             }
     
             // Récupérer la question associée à la réponse
             $question = QuestionsEvaluation::find($evaluationReponse->reponse->questions_evaluations_id);
     
             // Ajouter la question et la réponse à la liste des questions et réponses pour cet évaluateur ID
             $groupedEvaluations[$evaluationReponse->evaluer_id][$evaluation->id]['questions_reponses'][] = [
                 'reponse' => $evaluationReponse->reponse
             ];
         }
     
         // Retourner les évaluations groupées au format JSON
         return response()->json([
             'user' => array_values($groupedEvaluations),
             'status' => 200
         ]);
     }
     
    /**
     * Display the specified resource.
     */
    public function show(EvaluationQuestionReponseEvaluation $evaluationQuestionReponseEvaluation)
    {
        //
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EvaluationQuestionReponseEvaluation $evaluationQuestionReponseEvaluation)
    {
        //
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EvaluationQuestionReponseEvaluation $evaluationQuestionReponseEvaluation)
    {
        //
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EvaluationQuestionReponseEvaluation $evaluationQuestionReponseEvaluation)
    {
        //
    }
}
