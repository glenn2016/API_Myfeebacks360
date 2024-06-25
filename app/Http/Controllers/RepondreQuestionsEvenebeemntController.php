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
            'reponsefeedback_ids' => 'required|array|min:1',  // 'reponsefeedback_ids' doit être un tableau non vide
            'reponsefeedback_ids.*' => 'required|exists:reponsefeedbacks,id',  // Chaque élément du tableau doit exister dans la table 'reponsefeedbacks'
        ]);
    
        try {
            $selections = [];
    
            foreach ($validatedData['reponsefeedback_ids'] as $reponsefeedback_id) {
                // Récupérer la réponse feedback
                $reponseFeedback = Reponsefeedback::find($reponsefeedback_id);
    
                // Récupérer la question associée
                $questionFeedback = $reponseFeedback->questionsfeedback;
    
                // Récupérer l'événement associé
                $evenement = $questionFeedback->evenement;
    
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
    
                // Vérifier si l'entrée existe déjà pour éviter les doublons
                $selection = RepondreQuestionsEvenebeemnt::firstOrCreate([
                    'reponsefeedback_id' => $reponsefeedback_id,
                ]);
    
                // Ajouter la sélection au tableau des sélections
                $selections[] = $selection;
            }
    
            // Retourner une réponse JSON avec les détails des sélections créées
            return response()->json([
                'message' => 'Réponses sélectionnées avec succès!',
                'selections' => $selections,
            ], 200);
    
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
