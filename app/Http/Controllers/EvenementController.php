<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Evenement;
use App\Models\QuestionsFeedback;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class EvenementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $user = Auth::user();   
            // Utilisez where pour spécifier chaque condition séparément
            $evenements = Evenement::where('etat', 1)
                                    ->where('usercreate', $user->id)
                                    ->get();
            
            return response()->json([
                'evenements' => $evenements,
                'status' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la récupération des événements',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }

    public function indexevenement()
    {
        try {
            $user = Auth::user();   
            
            // Récupérer les événements où le créateur de l'utilisateur est égal au créateur de l'événement
            $evenements = Evenement::where('etat', 1)
                                    ->where('usercreate', $user->usercreate)
                                    ->get();
            
            return response()->json([
                'evenements' => $evenements,
                'status' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la récupération des événements',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }

    public function indexarchiver()
    {
        try {
            return response()->json([
                'evenements' =>Evenement::where('etat', 0)->get(),
                'status' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la récupération des événements',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }
    public function archiver($id)
    {
        try {
            $evenement = Evenement::findOrFail($id);
            $evenement->etat = 0;
            $evenement->save();
            return response()->json([
                'message' => 'L\'événement a été archivé avec succès',
                'status' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de l\'archivage de l\'événement',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }
    /*

    public function create(Request $request)
    {
        try {
            $user = Auth::user();
            $validatedData = $request->validate([
                'titre' => ['required', 'string', 'max:255'],
                'description' => [ 'string', 'max:355'],
                'date_debut' => ['required', 'date'],
                'date_fin' => ['required', 'date'],
            ]);
    
            // Créez une nouvelle instance d'Evenement et attribuez-lui les données validées
            $evenement = new Evenement();
            $evenement->titre = $validatedData['titre'];
            $evenement->description = $validatedData['description'];
            $evenement->date_debut = $validatedData['date_debut'];
            $evenement->date_fin = $validatedData['date_fin'];
            $evenement->usercreate = $user->id;
    
            // Enregistrez l'événement
            $evenement->save();
            
            // Retournez une réponse JSON avec l'événement créé
            return response()->json([
                'Evenement' => $evenement,
                'message' => 'Evenement créé avec succès',
                'status' => 200
            ]);
        } catch (\Exception $e) {
            // En cas d'erreur, renvoyez une réponse JSON avec un message d'erreur
            return response()->json([
                'message' => 'Une erreur est survenue lors de la création de l\'Evenement',
                'error' => $e->getMessage(),
                'status' => 500
            ]);
        }
    }
    */
    /**
     * Show the form for creating a new resource.
     */
    
    /**
     * Update the specified resource in storage.
     */
    public function updateEvenement(Request $request, $id)
    {
        // Log de la requête reçue pour débogage
        Log::info('Requête de mise à jour reçue : ', $request->all());

        // Validation des données d'entrée
        $validatedData = $request->validate([
            'titre' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string|max:255',
            'date_debut' => 'sometimes|required|date',
            'date_fin' => 'sometimes|required|date|after_or_equal:date_debut',
            'questions' => 'sometimes|array',
            'questions.*.id' => 'sometimes|numeric|exists:questionsfeedbacks,id',
            'questions.*.nom' => 'sometimes|required|string|max:255'
        ]);

        try {
            // Récupérer l'ID de l'utilisateur authentifié
            $userId = Auth::id();

            // Vérifiez si l'utilisateur est authentifié
            if (!$userId) {
                return response()->json(['message' => 'Utilisateur non authentifié'], 401);
            }

            // Rechercher l'événement par son ID
            $evenement = Evenement::findOrFail($id);
            /*

            // Vérifier si l'utilisateur a le droit de modifier cet événement
            if ($evenement->usercreate !== $userId) {
                return response()->json(['message' => 'Permission refusée pour modifier cet événement'], 403);
            }*/

            // Mise à jour des informations de l'événement
            $evenement->update($validatedData);

            // Gestion des questions associées (ajout, modification, suppression)
            if (!empty($validatedData['questions'])) {
                foreach ($validatedData['questions'] as $questionData) {
                    if (isset($questionData['id'])) {
                        // Mettre à jour la question existante
                        $question = QuestionsFeedback::find($questionData['id']);
                        if ($question && $question->evenement_id == $evenement->id) {
                            $question->update(['nom' => $questionData['nom']]);
                        }
                    } else {
                        // Ajouter une nouvelle question à l'événement
                        QuestionsFeedback::create([
                            'nom' => $questionData['nom'],
                            'evenement_id' => $evenement->id
                        ]);
                    }
                }
            }

            // Retourner une réponse de succès
            return response()->json([
                'message' => 'Événement mis à jour avec succès!',
                'evenement' => $evenement
            ], 200);
        } catch (\Exception $e) {
            // Log de l'erreur pour débogage
            Log::error('Erreur lors de la mise à jour de l\'événement : ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return response()->json([
                'message' => 'Erreur lors de la mise à jour de l\'événement',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /*
    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'titre' => ['required', 'string', 'max:255'],
                'description' => ['required', 'string', 'max:355'],
                'date_debut' => ['required', 'date'],
                'date_fin' => ['required', 'date'],
            ]);
    
            $evenement = Evenement::findOrFail($id);
            $evenement->update($validatedData);
    
            return response()->json([
                'message' => 'Evenement mis à jour avec succès',
                'Evenement' => $evenement,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la mise à jour de l\'événement',
                'error' => $e->getMessage(),
            ], 500);
        }
    }*/
    /**
     * Remove the specified resource from storage.
     */
    public function softDelete($id)
    {
        $evenement = Evenement::find($id);
        if ($evenement) {
            $evenement->delete(); // Utilise la suppression douce
            return response()->json([
                'message' => 'evenement soft deleted successfully',
                'status' => 200
            ]);
        } else {
            return response()->json([
                'message' => 'evenement not found',
                'status' => 404
            ], 404);
        }
    }

    public function getEvenementsForCurrentUser()
    {
        // Récupérer l'utilisateur connecté
        $user = Auth::user();

        // Vérifier si l'utilisateur est connecté
        if (!$user) {
            return response()->json(['message' => 'Non autorisé'], 401);
        }

        // Récupérer tous les événements où l'ID de l'utilisateur se retrouve dans user_id de reponsefeedbacks
        $evenements = Evenement::whereHas('questionsfeedback.reponsefeedbacks', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        // Retourner les événements en réponse JSON
        return response()->json($evenements);
    }


    public function getFeedbackForEvent($evenementId)
    {
        // Récupérer l'utilisateur connecté
        $user = Auth::user();

        // Vérifier si l'utilisateur est connecté
        if (!$user) {
            return response()->json(['message' => 'Non autorisé'], 401);
        }

        // Récupérer l'événement spécifié
        $evenement = Evenement::find($evenementId);

        // Vérifier si l'événement existe
        if (!$evenement) {
            return response()->json(['message' => 'Événement non trouvé'], 404);
        }

        // Récupérer les questions de feedback et les réponses de l'utilisateur pour cet événement
        $questionsFeedbacks = $evenement->questionsfeedback()
            ->with(['reponsefeedbacks' => function($query) use ($user) {
                $query->where('user_id', $user->id);
            }])
            ->get();

        // Préparer le résultat avec les questions et leurs réponses associées
        $result = [];
        foreach ($questionsFeedbacks as $question) {
            foreach ($question->reponsefeedbacks as $reponse) {
                $result[] = [
                    'id' => $reponse->id,
                    'nom' => $reponse->nom,
                    'questionsfeedbacks_id' => $reponse->questionsfeedbacks_id,
                    'user_id' => $reponse->user_id,
                    'question' => $question->nom,
                    'created_at' => $reponse->created_at,
                    'updated_at' => $reponse->updated_at,
                ];
            }
        }

        // Retourner les réponses de feedback avec les questions associées en réponse JSON
        return response()->json($result);
    }

    public function createEvenementWithQuestions(Request $request)
    {
        // Débogage initial des données de la requête
        Log::info('Requête reçue : ', $request->all());

        // Validation des données d'entrée
        $validatedData = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'questions' => 'required|array',
            'questions.*.nom' => 'required|string|max:255'
        ]);

        try {
            // Récupérer l'ID de l'utilisateur authentifié
            $userId = Auth::id();

            // Vérifiez si l'utilisateur est authentifié
            if (!$userId) {
                return response()->json(['message' => 'Utilisateur non authentifié'], 401);
            }

            // Log des données validées pour débogage
            Log::info('Données validées : ', $validatedData);

            // Génération d'un token unique pour l'événement
            $token = Str::random(32);

            // Création de l'événement avec le token
            $evenement = Evenement::create([
                'titre' => $validatedData['titre'],
                'description' => $validatedData['description'],
                'date_debut' => $validatedData['date_debut'],
                'date_fin' => $validatedData['date_fin'],
                'usercreate' => $userId,  // Enregistrement de l'ID de l'utilisateur
                'token' => $token
            ]);

            // Ajout des questions associées
            foreach ($validatedData['questions'] as $questionData) {
                QuestionsFeedback::create([
                    'nom' => $questionData['nom'],
                    'evenement_id' => $evenement->id
                ]);
            }

            // Générer le lien pour répondre aux questions
            $responseLink = url(config('app.frontend_url') . '/eventform/:token/'.$token);

            return response()->json([
                'message' => 'Événement et questions ajoutés avec succès!',
                'evenement' => $evenement,
                'response_link' => $responseLink
            ], 201);
        } catch (\Exception $e) {
            // Log de l'erreur pour débogage
            Log::error('Erreur lors de la création de l\'événement : ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return response()->json([
                'message' => 'Erreur lors de la création de l\'événement',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /*

    public function showRespondForm($event_id)
    {
        // Récupérer l'événement et ses questions
        $evenement = Evenement::with('questionsfeedback')->findOrFail($event_id);

        // Retourner une vue ou une réponse JSON avec les détails de l'événement et les questions
        return view('respondForm', ['evenement' => $evenement]);
    }

    */
    
}
