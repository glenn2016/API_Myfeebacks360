<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reponsefeedback;
use App\Models\Evenement;
use Illuminate\Support\Facades\Validator;
use App\Models\RepondreQuestionsEvenebeemnt;

class RepondreQuestionsEvenebeemntController extends Controller
{
    //

    public function selectReponses(Request $request)
    {
        // Validation des données d'entrée
        $validatedData = $request->validate([
            'reponsefeedback_ids' => 'required|array|min:1', // 'reponsefeedback_ids' doit être un tableau non vide
            'reponsefeedback_ids.*' => 'required|exists:reponsefeedbacks,id', // Chaque élément du tableau doit exister dans la table 'reponsefeedbacks'
            'email' => 'required|email' // Ajouter la validation de l'email
        ]);
    
        try {
            $email = $validatedData['email'];
            $selections = [];
            $evenementChecked = []; // Liste pour garder une trace des événements déjà vérifiés pour cet email
    
            foreach ($validatedData['reponsefeedback_ids'] as $reponsefeedback_id) {
                // Récupérer la réponse feedback
                $reponseFeedback = Reponsefeedback::find($reponsefeedback_id);
    
                if (!$reponseFeedback) {
                    return response()->json(['message' => 'Réponse feedback non trouvée'], 404);
                }
    
                // Récupérer la question associée
                $questionFeedback = $reponseFeedback->questionsfeedback;
    
                if (!$questionFeedback) {
                    return response()->json(['message' => 'Question feedback non trouvée'], 404);
                }
    
                // Récupérer l'événement associé
                $evenement = $questionFeedback->evenement;
    
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
                    return response()->json([
                        'message' => 'Le délai pour soumettre des réponses est expiré.',
                        'status' => 432
                    ], 403);
                }
    
                // Vérifier si l'email a déjà soumis des réponses pour cet événement
                if (!in_array($evenement->id, $evenementChecked)) {
                    // Vérifier uniquement si nous n'avons pas encore vérifié cet événement pour cet email
                    $hasAlreadyResponded = RepondreQuestionsEvenebeemnt::where('email', $email)
                        ->whereHas('reponsefeedback.questionsfeedback', function ($query) use ($evenement) {
                            $query->where('evenement_id', $evenement->id);
                        })
                        ->exists();
    
                    // Ajouter l'événement à la liste des événements vérifiés
                    $evenementChecked[] = $evenement->id;
    
                    if ($hasAlreadyResponded) {
                        return response()->json([
                            'message' => 'Vous avez déjà soumis des réponses pour cet événement.',
                            'status' => 431
                        ], 403);
                    }
                }
    
                // Enregistrer la sélection de réponse avec l'email
                $selection = RepondreQuestionsEvenebeemnt::create([
                    'reponsefeedback_id' => $reponsefeedback_id,
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
            // Log de l'erreur pour débogage
    
            // Retourner une réponse JSON en cas d'erreur
            return response()->json([
                'message' => 'Erreur lors de la sélection des réponses',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    
    
    
    
    
    


}
