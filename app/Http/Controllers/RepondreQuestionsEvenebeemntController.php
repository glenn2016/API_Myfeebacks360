<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reponsefeedback;
use App\Models\questionsfeedback;
use Illuminate\Support\Facades\Validator;
use App\Models\RepondreQuestionsEvenebeemnt;

class RepondreQuestionsEvenebeemntController extends Controller
{
    //

    public function selectReponses(Request $request)
{
    // Validation des données d'entrée
    $validatedData = $request->validate([
        'reponsefeedbacks' => 'required|array|min:1', // 'reponsefeedbacks' doit être un tableau non vide
        'reponsefeedbacks.*.reponsefeedback_id' => 'nullable|exists:reponsefeedbacks,id', // Chaque élément du tableau doit exister dans la table 'reponsefeedbacks' ou être null
        'reponsefeedbacks.*.questionsfeedbacks_id' => 'nullable|exists:questionsfeedbacks,id', // Chaque élément doit exister dans la table 'questionsfeedbacks' ou être null
        'reponsefeedbacks.*.repondre' => 'nullable|string|max:255', // Ajouter la validation pour 'repondre'
        'email' => 'required|email' // Ajouter la validation de l'email
    ]);

    try {
        $email = $validatedData['email'];
        $selections = [];
        $evenementChecked = []; // Liste pour garder une trace des événements déjà vérifiés pour cet email

        foreach ($validatedData['reponsefeedbacks'] as $reponseData) {
            // Récupérer la réponse feedback si elle est spécifiée
            $reponseFeedback = null;
            if (isset($reponseData['reponsefeedback_id'])) {
                $reponseFeedback = Reponsefeedback::find($reponseData['reponsefeedback_id']);
                if (!$reponseFeedback) {
                    return response()->json(['message' => 'Réponse feedback non trouvée pour id: ' . $reponseData['reponsefeedback_id']], 404);
                }
            }

            // Récupérer la question associée si elle est spécifiée
            $questionFeedback = null;
            if (isset($reponseData['questionsfeedbacks_id'])) {
                $questionFeedback = Questionsfeedback::find($reponseData['questionsfeedbacks_id']);
                if (!$questionFeedback) {
                    return response()->json(['message' => 'Question feedback non trouvée pour id: ' . $reponseData['questionsfeedbacks_id']], 404);
                }
            }

            // Vérifier si l'événement associé existe et est actif
            $evenement = null;
            if ($questionFeedback) {
                $evenement = $questionFeedback->evenement;
                if (!$evenement) {
                    return response()->json(['message' => 'Événement non trouvé pour la question feedback'], 404);
                }
            }

            // Vérifier si l'état de l'événement est différent de 1
            if ($evenement && $evenement->etat != 1) {
                return response()->json(['message' => 'Soumission de réponses non autorisée pour cet événement.'], 403);
            }

            // Vérifier la validité de la date de fin de l'événement si applicable
            if ($evenement) {
                $dateFinEvenement = new \DateTime($evenement->date_fin);
                $dateCourante = new \DateTime();

                // Vérifier si la date de fin de l'événement est passée depuis plus de 4 jours
                $interval = $dateCourante->diff($dateFinEvenement);
                if ($interval->invert == 1 && $interval->days > 4) {
                    return response()->json([
                        'message' => 'Le délai pour soumettre des réponses est expiré.',
                        'status' => 432
                    ], 403);
                }
            }

            // Vérifier si l'email a déjà soumis des réponses pour cet événement spécifique
            if ($evenement && !in_array($evenement->id, $evenementChecked)) {
                $hasAlreadyResponded = RepondreQuestionsEvenebeemnt::where('email', $email)
                    ->whereHas('reponsefeedback', function ($query) use ($evenement) {
                        $query->whereHas('questionsfeedback', function ($query) use ($evenement) {
                            $query->where('evenement_id', $evenement->id);
                        });
                    })
                    ->exists();

                $evenementChecked[] = $evenement->id;

                if ($hasAlreadyResponded) {
                    return response()->json([
                        'message' => 'Vous avez déjà soumis des réponses pour cet événement.',
                        'status' => 431
                    ], 403);
                }
            }

            // Enregistrer la sélection de réponse avec l'email, questionsfeedbacks_id, et repondre
            $selection = RepondreQuestionsEvenebeemnt::create([
                'reponsefeedback_id' => $reponseData['reponsefeedback_id'] ?? null,
                'questionsfeedbacks_id' => $reponseData['questionsfeedbacks_id'] ?? null,
                'repondre' => $reponseData['repondre'] ?? null,
                'email' => $email
            ]);

            // Ajouter la sélection au tableau des sélections
            $selections[] = $selection;
        }

        // Retourner une réponse JSON avec les détails des sélections créées
        return response()->json([
            'message' => 'Réponses sélectionnées avec succès!',
            'selections' => $selections,
            'status' => 200
        ]);

    } catch (\Exception $e) {

        // Retourner une réponse JSON en cas d'erreur
        return response()->json([
            'message' => 'Erreur lors de la sélection des réponses',
            'error' => $e->getMessage()
        ], 500);
    }
}

    
    
    
    
    
    
    
    
    
    
    


}
