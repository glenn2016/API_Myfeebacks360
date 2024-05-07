<?php

namespace App\Http\Controllers;

use App\Models\QuestionsEvaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ReponsesEvaluation;
use App\Models\Evaluation;

class QuestionsEvaluationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $questionsEvaluation = QuestionsEvaluation::with('evaluation')->get();

        return response()->json([
            'questionsEvaluation' => $questionsEvaluation,
            'status' => 200
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Validation des données pour les questions et les catégories
        $validatedData = $request->validate([
            'nom.*' => 'required|string|max:255',
            'titre' => 'required|string|max:255',
            'categories.*' => 'required|numeric', // Assurez-vous que chaque catégorie est un ID numérique
        ]);
    
        // Créer une nouvelle évaluation
        $evaluation = Evaluation::create([
            'titre' => $validatedData['titre'],
        ]);
    
        $evaluationId = $evaluation->id;
    
        // Initialiser un tableau pour stocker les questions et les réponses avec les catégories
        $questionsWithReponsesAndCategories = [];
    
        // Initialiser un compteur pour l'index des questions
        $questionIndex = 0;
    
        // Créer les questions associées à l'évaluation et les réponses avec les catégories
        foreach ($validatedData['nom'] as $nom) {
            $question = QuestionsEvaluation::create([
                'nom' => $nom,
                'evaluation_id' => $evaluationId,
                'categorie_id' => $validatedData['categories'][$questionIndex], // Associer la catégorie à la question
            ]);
    
            $questionData = [
                'question' => $question,
                'reponses' => [],
                'categorie_id' => $validatedData['categories'][$questionIndex], // Ajouter l'ID de la catégorie
            ];
    
            // Vérifier si des réponses ont été fournies pour cette question
            if ($request->has("reponse.$questionIndex")) {
                // Récupérer les réponses associées à cette question
                $reponses = $request->input("reponse.$questionIndex");
    
                // Créer chaque réponse et les associer à la question
                foreach ($reponses as $reponse) {
                    $reponseEvaluation = ReponsesEvaluation::create([
                        'reponse' => $reponse,
                        'questions_evaluations_id' => $question->id,
                    ]);
    
                    $questionData['reponses'][] = $reponseEvaluation;
                }
            }
    
            // Ajouter les données de la question, des réponses et de la catégorie au tableau
            $questionsWithReponsesAndCategories[] = $questionData;
    
            // Incrémenter le compteur de l'index des questions
            $questionIndex++;
        }
    
        // Retourner les données des questions, des réponses et des catégories créées
        return response()->json([
            'message' => 'Évaluation créée avec succès',
            'evaluation' => $evaluation,
            'questions_with_reponses_and_categories' => $questionsWithReponsesAndCategories,
        ], 201);
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
    public function show($id){
        return response()->json([
            'questionsEvaluation' => QuestionsEvaluation::find($id),
            'message' => 'questionsEvaluation recuperer',
            'status' => 200
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(QuestionsEvaluation $questionsEvaluation)
    {
        //
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