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
         $evaluationReponses = EvaluationQuestionReponseEvaluation::where('evaluer_id', $id)->get();
     
         // Créer un tableau pour stocker les évaluations groupées par "evaluation_id"
         $groupedEvaluations = [];
     
         // Parcourir toutes les évaluations de l'utilisateur
         foreach ($evaluationReponses as $evaluationReponse) {
             // Récupérer l'utilisateur associé à l'évaluateur
             $user = User::find($evaluationReponse->evaluatuer_id);
     
             // Récupérer l'évaluation associée à la réponse
             $evaluation = Evaluation::find($evaluationReponse->reponse->questionsEvaluation->evaluation_id);
     
             // Vérifier si l'évaluateur ID existe déjà dans le tableau groupé
             if (!isset($groupedEvaluations[$user->id][$evaluation->id])) {
                 // Si ce n'est pas le cas, ajouter une nouvelle entrée au tableau groupé
                 $groupedEvaluations[$user->id][$evaluation->id] = [
                     'user' => $user,
                     'evaluation' => $evaluation,
                     'commentaire' => $evaluationReponse->commentaire,
                     'niveau' => $evaluationReponse->niveau,
                     'questions_reponses' => []
                 ];
             }
     
             // Récupérer la question associée à la réponse
             $question = QuestionsEvaluation::find($evaluationReponse->reponse->questions_evaluations_id);
     
             // Ajouter la question et la réponse à la liste des questions et réponses pour cet utilisateur et cette évaluation
             $groupedEvaluations[$user->id][$evaluation->id]['questions_reponses'][] = [
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
    public function getEvaluatorsList()
    {
        // Obtenir l'ID de l'utilisateur connecté
        $currentUserId = Auth::id();
        // Récupérer les IDs des utilisateurs évalués par l'utilisateur connecté
        $evaluatedUserIDs = EvaluationQuestionReponseEvaluation::select('evaluatuer_id')
                            ->where('evaluer_id', $currentUserId)
                            ->distinct()
                            ->get()
                            ->pluck('evaluatuer_id');

        // Récupérer les utilisateurs évalués
        $evaluatedUsers = User::whereIn('id', $evaluatedUserIDs)->get();

        // Retourner les utilisateurs évalués au format JSON
        return response()->json([
            'evaluatedUsers' => $evaluatedUsers,
            'status' => 200
        ]);
    }

    /**
     * Display the specified resource.
     */

    public function getEvaluerrsList()
    {
        // Obtenir l'ID de l'utilisateur connecté
        $currentUserId = Auth::id();

        // Récupérer les IDs des utilisateurs évalués par l'utilisateur connecté
        $evaluatedUserIDs = EvaluationQuestionReponseEvaluation::select('evaluer_id')
                            ->where('evaluatuer_id', $currentUserId)
                            ->distinct()
                            ->get()
                            ->pluck('evaluer_id');

        // Récupérer les utilisateurs évalués
        $evaluatedUsers = User::whereIn('id', $evaluatedUserIDs)->get();

        // Retourner les utilisateurs évalués au format JSON
        return response()->json([
            'evaluatedUsers' => $evaluatedUsers,
            'status' => 200
        ]);
    }



    public function getEvaluatorsParticipants($userId)
    {
        // Récupérer les IDs des utilisateurs évalués par l'utilisateur spécifié
        $evaluatedUserIDs = EvaluationQuestionReponseEvaluation::select('evaluatuer_id')
                            ->where('evaluer_id', $userId)
                            ->distinct()
                            ->get()
                            ->pluck('evaluatuer_id');
        
        // Récupérer les utilisateurs évalués
        $evaluatedUsers = User::whereIn('id', $evaluatedUserIDs)->get();
        
        $result = [];
        
        foreach ($evaluatedUsers as $user) {
            // Récupérer les évaluations de l'utilisateur évalué
            $evaluations = EvaluationQuestionReponseEvaluation::where('evaluatuer_id', $user->id)
                                ->with('reponse.questionsEvaluation.evaluation')
                                ->get();
            
            $evaluationsGrouped = [];
            
            foreach ($evaluations as $evaluationResponse) {
                $evaluation = $evaluationResponse->reponse->questionsEvaluation->evaluation;
                $evaluationId = $evaluation->id;
                
                // Initialiser l'entrée pour cette évaluation s'il n'existe pas encore
                if (!isset($evaluationsGrouped[$evaluationId])) {
                    $evaluationsGrouped[$evaluationId] = [
                        'evaluation' => $evaluation,
                        'commentaire' => $evaluationResponse->commentaire,
                        'niveau' => $evaluationResponse->niveau,
                        'questions_reponses' => []
                    ];
                }
                
                // Ajouter la réponse à la liste des questions et réponses
                $evaluationsGrouped[$evaluationId]['questions_reponses'][] = [
                    'reponse' => $evaluationResponse->reponse
                ];
            }
            
            $result[] = [
                'user' => $user,
                'evaluations' => array_values($evaluationsGrouped)
            ];
        }
        
        // Retourner les utilisateurs évalués et leurs évaluations au format JSON
        return response()->json([
            'evaluatedUsers' => $result,
            'status' => 200
        ]);
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
