<?php

namespace App\Http\Controllers;

use App\Models\Reponsefeedback;
use App\Models\Evenement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Questionsfeedback;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class ReponsefeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return response()->json([
                'reponsefeedback' => Reponsefeedback::with(['questionsfeedback.feedback', 'users'])->get(),
                'status' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la récupération des réponses de feedback',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function evenementquestionreponse($evenement_id)
    {
        try {
            // Récupérer toutes les questions liées à l'événement spécifié avec les réponses associées et les utilisateurs correspondants
            return response()->json([
                'questions' => Questionsfeedback::where('evenement_id', $evenement_id)
                ->with(['reponsefeedbacks.user'])
                ->get(),
                'status' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la récupération des questions et réponses liées à l\'événement',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return response()->json([
            'reponsefeedback' =>  Reponsefeedback::with(['questionsfeedback.feedback','users'])->find($id),
            'status' => 200
        ]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id){     
        $validator = Validator::make($request->all(), [
            'questionsfeedbacks_id' => ['required', 'numeric'], // Assurez-vous que evenement_id est numérique
            'nom' => ['required', 'string', 'max:255'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'status' => 400
            ], 400);
        }
        $user = Auth::user(); // Utilisez la méthode statique auth() de la classe Auth pour récupérer l'utilisateur authentifié
        $validatedData = $validator->validated();
        $validatedData = $validator->validated();
        $reponsefeedback = new Reponsefeedback();
        $reponsefeedback->nom = $validatedData['nom'];
        $reponsefeedback->user_id= $user->id; // Assurez-vous d'accéder à l'attribut id de l'utilisateur
        $reponsefeedback->questionsfeedbacks_id = $validatedData['questionsfeedbacks_id'];
        $reponsefeedback->save();
        return response()->json([
            'message' => 'reponsefeedback mis à jour avec succès',
            'reponsefeedback' => $reponsefeedback,
        ], 200);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function softDelete($id)
    {
        $reponsefeedback = Reponsefeedback::find($id);
        if ($reponsefeedback) {
            $reponsefeedback->delete(); // Utilise la suppression douce
            return response()->json([
                'message' => 'reponsefeedback soft deleted successfully',
                'status' => 200
            ]);
        } else {
            return response()->json([
                'message' => 'reponsefeedback not found',
                'status' => 404
            ], 404);
        }
    }
    
    public function submitResponses(Request $request, $token)
    {
        $evenement = Evenement::where('token', $token)->first();

        if (!$evenement) {
            return response()->json(['message' => 'Événement non trouvé'], 404);
        }
        

        // Vérifier si l'état de l'événement est différent de 1
        if ($evenement->etat != 1) {
            return response()->json(['message' => 'Soumission de réponses non autorisée pour cet événement.'], 403);
        }

        // Récupérer la date de fin de l'événement
        $dateFinEvenement = new \DateTime($evenement->date_fin);
        $dateCourante = new \DateTime();

        // Vérifier si la date de fin de l'événement est passée depuis plus de 4 jours
        $interval = $dateCourante->diff($dateFinEvenement);
        if ($interval->invert == 1 && $interval->days > 4) {
            return response()->json(['message' => 'Le délai pour soumettre des réponses est expiré.'], 403);
        }

        // Validation des réponses
        $validator = Validator::make($request->all(), [
            'reponses.*.questionsfeedbacks_id' => 'required|numeric|exists:questionsfeedbacks,id',
            'reponses.*.nom' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 400);
        }

        // Créer les réponses de feedback
        $validatedData = $validator->validated();
        $reponses = [];

        foreach ($validatedData['reponses'] as $reponseData) {
            $reponsefeedback = new Reponsefeedback();
            $reponsefeedback->nom = $reponseData['nom'];
            $reponsefeedback->questionsfeedbacks_id = $reponseData['questionsfeedbacks_id'];
            $reponsefeedback->save();

            $reponses[] = $reponsefeedback;
        }

        return response()->json([
            'message' => 'Réponses créées avec succès',
            'reponses' => $reponses,
            'status' => 200
        ]);
    }


    public function showQuestions($token)
    {
        // Trouver l'événement en utilisant le token
        $evenement = Evenement::where('token', $token)->first();
    
        // Vérifier si l'événement existe
        if (!$evenement) {
            return response()->json(['message' => 'Événement non trouvé'], 404);
        }
    
        // Récupérer les questions associées à l'événement avec leurs réponses
        $questions = QuestionsFeedback::with('reponsefeedbacks')
            ->where('evenement_id', $evenement->id)
            ->get();
    
        // Formater la réponse JSON
        return response()->json([
            'evenement' => $evenement,
            'questions' => $questions->map(function ($question) {
                return [
                    'id' => $question->id,
                    'nom' => $question->nom,
                    'reponses' => $question->reponsefeedbacks->map(function ($reponse) {
                        return [
                            'id' => $reponse->id,
                            'nom' => $reponse->nom
                        ];
                    })
                ];
            })
        ]);
    }
    
    
   

}