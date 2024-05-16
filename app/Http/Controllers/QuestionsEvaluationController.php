<?php

namespace App\Http\Controllers;

use App\Models\QuestionsEvaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ReponsesEvaluation;
use Illuminate\Support\Facades\Auth;

use App\Models\Evaluation;

class QuestionsEvaluationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return response()->json([
                'questionsEvaluation' => QuestionsEvaluation::with('evaluation')->get(),
                'status' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la récupération des questions d\'évaluation',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Récupérer l'ID de l'utilisateur connecté
        $userId = Auth::id();   
    
        // Validation des données pour l'évaluation
        $validatedData = $request->validate([
            'titre' => 'required|string|max:255',
            'questions' => 'required|array',
            'questions.*.nom' => 'required|string|max:255',
            'questions.*.categorie_id' => 'required|integer|exists:categories,id',
            'questions.*.reponses' => 'array',
        ]);
    
        // Créer une nouvelle évaluation avec l'ID de l'utilisateur connecté
        $evaluation = Evaluation::create([
            'titre' => $validatedData['titre'],
            'usercreate'=> $userId,
        ]);
    
        // Récupérer l'ID de l'évaluation créée
        $evaluationId = $evaluation->id;
    
        // Créer les questions associées à l'évaluation et les réponses
        foreach ($validatedData['questions'] as $questionData) {
            // Créer la question
            $question = QuestionsEvaluation::create([
                'nom' => $questionData['nom'],
                'evaluation_id' => $evaluationId,
                'categorie_id' => $questionData['categorie_id'],
            ]);
    
            // Vérifier s'il y a des réponses pour cette question
            if (is_array($questionData['reponses']) && count($questionData['reponses']) > 0) {
                // Créer chaque réponse et les associer à la question
                foreach ($questionData['reponses'] as $reponse) {
                    ReponsesEvaluation::create([
                        'reponse' => $reponse,
                        'questions_evaluations_id' => $question->id,
                    ]);
                }
            }
        }
    
        // Retourner une réponse indiquant que l'évaluation a été créée avec succès et l'ID de l'évaluation créée
        return response()->json([
            'message' => 'Evaluation créée avec succès',
            'evaluation_id' => $evaluationId
        ], 201);
    }
    /**
     * Display the specified resource.
     */
    public function show($id){
        return response()->json([
            'questionsEvaluation' => QuestionsEvaluation::find($id),
            'message' => 'questionsEvaluation recuperer',
            'status' => 200
        ]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'nom' => ['required', 'string', 'max:255'],
            'evaluation_id' => ['required', 'numeric'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'status' => 400
            ], 400);
        }
        $validatedData = $validator->validated();
        $questionsEvaluation = new QuestionsEvaluation();
        $questionsEvaluation->nom = $validatedData['nom'];
        $questionsEvaluation->evaluation_id = $validatedData['evaluation_id'];
        $questionsEvaluation->save();
        return response()->json([
            'message' => 'questionsEvaluation mis à jour avec succès',
            'questionsEvaluation' => $questionsEvaluation,
        ], 200);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function softDelete($id)
    {
        $questionsEvaluation = QuestionsEvaluation::find($id);
        if ($questionsEvaluation) {
            $questionsEvaluation->delete(); // Utilise la suppression douce
            return response()->json([
                'message' => 'questionsEvaluation soft deleted successfully',
                'status' => 200
            ]);
        } else {
            return response()->json([
                'message' => 'questionsEvaluation not found',
                'status' => 404
            ], 404);
        }
    }
}