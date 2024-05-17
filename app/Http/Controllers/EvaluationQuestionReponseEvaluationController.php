<?php

namespace App\Http\Controllers;

use App\Models\User;
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
            $entrepriseDePersonneConnectee = Auth::user()->entreprise;
    
            // Vérifier si l'utilisateur connecté a une entreprise
            if (!$entrepriseDePersonneConnectee) {
                return response()->json([
                    'message' => 'L\'utilisateur connecté n\'a pas d\'entreprise associée.',
                    'status' => '400'
                ], 400);
            }
    
            // Récupérer les utilisateurs avec les mêmes conditions d'entreprise et de catégorie
            $participants = User::whereHas('entreprise', function ($query) use ($entrepriseDePersonneConnectee) {
                    $query->where('nom', '=', $entrepriseDePersonneConnectee->nom);
                })
                ->where('id', '!=', Auth::id())
                ->where('categorie_id', $categoryId) // Assurez-vous que 'categorie_id' est la colonne qui référence la catégorie dans la table users
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
    public function store(Request $request)
    {
        //
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
