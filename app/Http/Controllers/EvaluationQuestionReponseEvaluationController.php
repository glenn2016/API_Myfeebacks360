<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Categorie;
use App\Models\Evaluation;
use App\Models\QuestionsEvaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ReponsesEvaluation;
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
    /*
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
    */
    /**
     * Store a newly created resource in storage.
     */

     /*
    public function create(Request $request)
    {
        try {
            // Validation des données d'entrée
            $validator = Validator::make($request->all(), [
                'evaluation.*.reponse_id' => 'required|numeric',
                'evaluer_id' => 'required|numeric|max:255',
                'commentaire' => 'required|string|max:255',
            ]);
    
            // Vérification des erreurs de validation
            if ($validator->fails()) {
                return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
            }
    
            $user = Auth::user();  // Récupération de l'utilisateur authentifié
            $validatedData = $validator->validated();
            $evaluations = [];
            $totalNiveau = 0;
            $countNiveau = 0;
    
            foreach ($validatedData['evaluation'] as $evaluationData) {
                // Récupérer la réponse associée
                $reponse = ReponsesEvaluation::find($evaluationData['reponse_id']);
                if ($reponse) {
                    // Ajouter le niveau de cette réponse au total
                    $totalNiveau += $reponse->niveau;
                    $countNiveau++;
                }
            }
    
            // Calculer la moyenne des niveaux
            $moyenneNiveau = $countNiveau > 0 ? ($totalNiveau / $countNiveau) : 0;
    
            foreach ($validatedData['evaluation'] as $evaluationData) {
                $evaluation = new EvaluationQuestionReponseEvaluation();
                $evaluation->reponse_id = $evaluationData['reponse_id'];
                $evaluation->evaluatuer_id = $user->id;
                $evaluation->evaluer_id = $validatedData['evaluer_id'];
                $evaluation->niveau = $moyenneNiveau.' %'; // Appliquer la moyenne calculée
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
                'message' => 'Une erreur est survenue lors de la création d\'une évaluation',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    */

    public function create(Request $request)
    {
        try {
            // Validation des données d'entrée
            $validator = Validator::make($request->all(), [
                'evaluation.*.reponse_id' => 'required|numeric',
                'evaluer_id' => 'required|numeric|max:255',
                'commentaire' => 'required|string|max:255',
            ]);

            // Vérification des erreurs de validation
            if ($validator->fails()) {
                return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
            }

            $user = Auth::user();  // Récupération de l'utilisateur authentifié
            $validatedData = $validator->validated();
            $evaluations = [];
            $totalNiveau = 0;
            $countNiveau = 0;

            foreach ($validatedData['evaluation'] as $evaluationData) {
                // Vérifier si l'évaluation existe déjà
                $existingEvaluation = EvaluationQuestionReponseEvaluation::where('reponse_id', $evaluationData['reponse_id'])
                    ->where('evaluatuer_id', $user->id)
                    ->where('evaluer_id', $validatedData['evaluer_id'])
                    ->first();  

                if ($existingEvaluation) {
                    return response()->json([
                        'message' => 'Évaluation déjà existante pour cet utilisateur et cette réponse',
                        'status' =>409
                    ], 409);
                }

                // Récupérer la réponse associée
                $reponse = ReponsesEvaluation::find($evaluationData['reponse_id']);
                if ($reponse) {
                    // Ajouter le niveau de cette réponse au total
                    $totalNiveau += $reponse->niveau;
                    $countNiveau++;
                }
            }

            // Calculer la moyenne des niveaux
            $moyenneNiveau = $countNiveau > 0 ? ($totalNiveau / $countNiveau) : 0;

            foreach ($validatedData['evaluation'] as $evaluationData) {
                $evaluation = new EvaluationQuestionReponseEvaluation();
                $evaluation->reponse_id = $evaluationData['reponse_id'];
                $evaluation->evaluatuer_id = $user->id;
                $evaluation->evaluer_id = $validatedData['evaluer_id'];
                $evaluation->niveau = $moyenneNiveau . ' %'; // Appliquer la moyenne calculée
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
                'message' => 'Une erreur est survenue lors de la création d\'une évaluation',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    
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
    // Get the ID of the authenticated user (evaluated user)
    $userId = auth()->id();

    // Fetch all evaluation responses where the authenticated user is the evaluated user
    $evaluationReponses = EvaluationQuestionReponseEvaluation::where('evaluer_id', $userId)->get();

    // Initialize an array to store grouped evaluations
    $groupedEvaluations = [];

    // Iterate through the fetched evaluation responses
    foreach ($evaluationReponses as $evaluationReponse) {
        // Fetch the related evaluation through the response's question's evaluation
        $evaluation = Evaluation::find($evaluationReponse->reponse->questionsEvaluation->evaluation_id);

        // Check if the evaluation ID is already in the grouped evaluations array
        if (!isset($groupedEvaluations[$evaluation->id])) {
            // If not, initialize a new entry for this evaluation
            $groupedEvaluations[$evaluation->id] = [
                'evaluation' => $evaluation,
                'commentaire' => $evaluationReponse->commentaire,
                'niveau' => $evaluationReponse->niveau,
                'questions_reponses' => []
            ];
        }

        // Add the response to the questions_reponses array for this evaluation
        $groupedEvaluations[$evaluation->id]['questions_reponses'][] = [
            'reponse' => $evaluationReponse->reponse
        ];
    }

    // Return the grouped evaluations as a JSON response
    return response()->json([
        'evaluations' => array_values($groupedEvaluations),
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


    public function getEvaluatorsParticipants($evaluatedUserId)
    {
        // Get the ID of the authenticated user (evaluator)
        $evaluatorId = auth()->id();
    
        // Fetch all evaluation responses where the authenticated user is the evaluator and the specified user is the evaluated user
        $evaluationReponses = EvaluationQuestionReponseEvaluation::where('evaluatuer_id', $evaluatorId)
            ->where('evaluer_id', $evaluatedUserId)
            ->get();
    
        // Initialize an array to store grouped evaluations
        $groupedEvaluations = [];
    
        // Iterate through the fetched evaluation responses
        foreach ($evaluationReponses as $evaluationReponse) {
            // Fetch the related evaluation through the response's question's evaluation
            $evaluation = Evaluation::find($evaluationReponse->reponse->questionsEvaluation->evaluation_id);
    
            // Check if the evaluation ID is already in the grouped evaluations array
            if (!isset($groupedEvaluations[$evaluation->id])) {
                // If not, initialize a new entry for this evaluation
                $groupedEvaluations[$evaluation->id] = [
                    'evaluation' => $evaluation,
                    'commentaire' => $evaluationReponse->commentaire,
                    'niveau' => $evaluationReponse->niveau,
                    'questions_reponses' => []
                ];
            }
    
            // Add the response to the questions_reponses array for this evaluation
            $groupedEvaluations[$evaluation->id]['questions_reponses'][] = [
                'reponse' => $evaluationReponse->reponse
            ];
        }
    
        // Return the grouped evaluations as a JSON response
        return response()->json([
            'evaluations' => array_values($groupedEvaluations),
            'status' => 200
        ]);
    }

    public function getEvaluators()
    {
        // Get the ID of the authenticated user (evaluated user)
        $userId = auth()->id();

        // Fetch all evaluation responses where the authenticated user is the evaluated user
        $evaluationReponses = EvaluationQuestionReponseEvaluation::where('evaluer_id', $userId)->get();

        // Initialize an array to store evaluators
        $evaluators = [];

        // Iterate through the fetched evaluation responses
        foreach ($evaluationReponses as $evaluationReponse) {
            // Fetch the evaluator user ID
            $evaluatorId = $evaluationReponse->evaluatuer_id;

            // Avoid duplicates
            if (!in_array($evaluatorId, $evaluators)) {
                $evaluators[] = $evaluatorId;
            }
        }

        // Fetch user details for each evaluator ID
        $users = User::whereIn('id', $evaluators)->get();

        // Return the evaluators as a JSON response
        return response()->json([
            'evaluators' => $users,
            'status' => 200
        ]);
    }
    
    public function getAllEvaluators()
    {
        // Get the ID of the authenticated user
        $userId = auth()->id();
    
        // Fetch all evaluation responses
        $evaluationReponses = EvaluationQuestionReponseEvaluation::all();
    
        // Initialize an array to store evaluators
        $evaluators = [];
    
        // Iterate through the fetched evaluation responses
        foreach ($evaluationReponses as $evaluationReponse) {
            // Fetch the evaluator user ID
            $evaluatorId = $evaluationReponse->evaluatuer_id;
    
            // Fetch the user create ID for the evaluated user
            $evaluatedUserId = $evaluationReponse->evaluer->usercreate;
    
            // Check if the user create ID matches the authenticated user ID
            if ($evaluatedUserId == $userId) {
                // Add the evaluator to the list
                if (!in_array($evaluatorId, $evaluators)) {
                    $evaluators[] = $evaluatorId;
                }
            }
        }
    
        // Fetch user details for each evaluator ID
        $users = User::whereIn('id', $evaluators)->get();
    
        // Return the evaluators as a JSON response
        return response()->json([
            'evaluators' => $users,
            'status' => 200
        ]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EvaluationQuestionReponseEvaluation $evaluationQuestionReponseEvaluation)
    {
        //
    }

    public function indexs()
    {
        try {
            $user = Auth::user();
            // Récupérer toutes les évaluations
            $evaluations = Evaluation::where('etat', 1)
            ->where('usercreate', $user->id)
            ->get();

            // Initialiser un tableau pour stocker les données de toutes les évaluations avec leurs questions et réponses
            $evaluationsData = [];

            // Pour chaque évaluation, récupérer ses questions et réponses associées
            foreach ($evaluations as $evaluation) {
                // Récupérer les questions associées à cette évaluation
                $questions = QuestionsEvaluation::where('evaluation_id', $evaluation->id)->get();

                // Pour chaque question, récupérer les réponses associées
                foreach ($questions as $question) {
                    $question->reponses = ReponsesEvaluation::where('questions_evaluations_id', $question->id)->get();
                }

                // Stocker les données de l'évaluation avec ses questions et réponses associées dans le tableau
                $evaluationsData[] = [
                    'evaluation' => $evaluation,
                    'questions' => $questions,
                ];
            }

            // Retourner les données de toutes les évaluations avec leurs questions et réponses associées
            return response()->json([
                'evaluations' => $evaluationsData,
            ], 200);
        } catch (\Exception $e) {
            // En cas d'erreur, retourner un message d'erreur
            return response()->json([
                'message' => 'Une erreur est survenue lors de la récupération des évaluations',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function getEvaluerrsListAdmin()
    {
        try {
            // Obtenir l'ID de l'utilisateur connecté
            $currentUserId = Auth::id();

            // Vérifier si l'utilisateur est authentifié
            if (!$currentUserId) {
                return response()->json([
                    'message' => 'Utilisateur non authentifié',
                    'status' => 401
                ], 401);
            }

            // Récupérer les enregistrements des utilisateurs évalués
            $evaluations = EvaluationQuestionReponseEvaluation::select('evaluer_id', 'niveau')
                ->distinct()
                ->get();

            // Filtrer les utilisateurs évalués dont le usercreate est égal à l'ID de l'utilisateur connecté
            $evaluatedUsers = $evaluations->filter(function($evaluation) use ($currentUserId) {
                $user = User::find($evaluation->evaluer_id);
                return $user && $user->usercreate == $currentUserId;
            });

            // Initialiser les catégories
            $classification = [
                'insuffisant' => 0,
                'moyen' => 0,
                'bien' => 0,
                'excellent' => 0
            ];

            // Récupérer les utilisateurs évalués et leur niveau
            $usersWithLevels = $evaluatedUsers->map(function($evaluation) {
                $user = User::find($evaluation->evaluer_id);
                if ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'niveau' => $evaluation->niveau
                    ];
                }
                return null;
            })->filter();

            // Classifier les utilisateurs selon les niveaux
            foreach ($usersWithLevels as $user) {
                $niveau = intval(str_replace('%', '', $user['niveau']));
                if ($niveau < 50) {
                    $classification['insuffisant']++;
                } elseif ($niveau < 60) {
                    $classification['moyen']++;
                } elseif ($niveau < 80) {
                    $classification['bien']++;
                } else {
                    $classification['excellent']++;
                }
            }

            // Retourner les données classifiées en JSON
            return response()->json([
                'classification' => $classification,
                'evaluatedUsers' => $usersWithLevels,
                'status' => 200
            ]);

        } catch (\Exception $e) {
            // Gérer les exceptions
            return response()->json([
                'message' => 'Une erreur est survenue lors de la récupération des utilisateurs évalués.',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }

}