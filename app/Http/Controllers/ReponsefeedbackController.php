<?php

namespace App\Http\Controllers;

use App\Models\Reponsefeedback;
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
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        try {
            // Debugging: Journaliser les données de la requête
            Log::info('Données de la requête:', $request->all());
            // Validation des données de la requête
            $validator = Validator::make($request->all(), [
                'reponses.*.questionsfeedbacks_id' => 'required|numeric',
                'reponses.*.nom' => 'required|string|max:255',
            ]);
            // Si la validation échoue, retourner les erreurs de validation
            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Erreur de validation',
                    'errors' => $validator->errors(),
                    'status' => 400
                ], 400);
            }
            // Récupérer l'utilisateur authentifié
            $user = Auth::user();
            // Récupérer les données validées
            $validatedData = $validator->validated();
            // Journaliser les données validées
            Log::info('Données validées:', $validatedData);
            // Vérifier si le tableau 'reponses' existe et n'est pas vide
            if (!isset($validatedData['reponses']) || empty($validatedData['reponses'])) {
                return response()->json([
                    'message' => 'Aucune réponse fournie',
                    'status' => 400
                ], 400);
            }
            // Créer chaque réponse
            $reponses = [];
            foreach ($validatedData['reponses'] as $reponseData) {
                // Journaliser les données de la réponse actuelle
                Log::info('Données de la réponse actuelle:', $reponseData);
                // Créer la réponse de feedback
                $reponsefeedback = new Reponsefeedback();
                $reponsefeedback->nom = $reponseData['nom'];
                $reponsefeedback->user_id = $user->id;
                $reponsefeedback->questionsfeedbacks_id = $reponseData['questionsfeedbacks_id'];
                $reponsefeedback->save();
                $reponses[] = $reponsefeedback;
            }
            // Retourner une réponse indiquant que les réponses ont été créées avec succès
            return response()->json([
                'message' => 'Réponses créées avec succès',
                'reponses' => $reponses,
            ], 200);
        } catch (\Exception $e) {
            // En cas d'erreur, retourner une réponse avec un message d'erreur
            return response()->json([
                'message' => 'Une erreur est survenue lors de la création des réponses de feedback',
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

    
}