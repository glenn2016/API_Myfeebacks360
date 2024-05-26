<?php

namespace App\Http\Controllers;

use App\Models\AbonnementUtlisateurs;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class AbonnementUtlisateursController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        try {
            // Récupérer tous les abonnements des utilisateurs avec les informations des utilisateurs et des abonnements
            $abonnementUtilisateurs = AbonnementUtlisateurs::with(['user', 'abonnement'])->get();
    
            // Retourner les données en format JSON
            return response()->json([
                'status' => 200,
                'data' => $abonnementUtilisateurs
            ]);
        } catch (\Exception $e) {
            // Gérer les exceptions et retourner une réponse JSON d'erreur
            return response()->json([
                'status' => 500,
                'message' => 'Une erreur est survenue lors de la récupération des abonnements des utilisateurs',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */

    public function create(Request $request)
    {
        try {
            // Validation des données de la requête
            $validator = Validator::make($request->all(), [
                'abonnement_id' => ['required', 'exists:abonnements,id'],
                'utlisateur_id' => ['required', 'exists:users,id'],
                'date_debut_abonement' => ['required', 'date'],
                'date_fin_abonement' => ['required', 'date', 'after_or_equal:date_debut_abonnement'],
            ]);
            // Vérification de la validation
            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 400);
            }
            // Création d'un nouvel enregistrement AbonnementUtlisateurs
            $abonnementUtlisateurs = AbonnementUtlisateurs::create([
                'abonnement_id' => $request->abonnement_id,
                'utlisateur_id' => $request->utlisateur_id,
                'date_debut_abonement' => $request->date_debut_abonement,
                'date_fin_abonement' => $request->date_fin_abonement,
            ]);
            // Retourner une réponse JSON avec succès
            return response()->json([
                'status' => 201,
                'message' => 'Abonnement utilisateur créé avec succès',
                'data' => $abonnementUtlisateurs
            ], 201);

        } catch (\Exception $e) {
            // Gérer les exceptions et retourner une réponse JSON d'erreur
            return response()->json([
                'status' => 500,
                'message' => 'Une erreur est survenue lors de la création de l\'abonnement utilisateur',
                'error' => $e->getMessage()
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
    public function show($id)
    {
        //
        try {
            return response()->json([
                'AbonnementUtlisateurs' => AbonnementUtlisateurs::find($id),
                'message' => 'AbonnementUtlisateurs recuperer',
                'status' => 200
            ]);
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Le AbonnementUtlisateurs demandé n\'a pas été trouvé',
                    'error' => $e->getMessage(),
                    'status' => 404
                ], 404);
            }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AbonnementUtlisateurs $abonnementUtlisateurs)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            // Valider les données de la requête
            $validatedData = $request->validate([
                'abonnement_id' => ['required', 'exists:abonnements,id'],
                'utlisateur_id' => ['required', 'exists:users,id'],
                'date_debut_abonnement' => ['required', 'date'],
                'date_fin_abonnement' => ['required', 'date', 'after_or_equal:date_debut_abonnement'],
            ]);
    
            // Trouver l'abonnement utilisateur par son ID
            $abonnementUtilisateur = AbonnementUtlisateurs::findOrFail($id);
    
            // Mettre à jour l'abonnement utilisateur avec les données validées
            $abonnementUtilisateur->update($validatedData);
    
            // Retourner une réponse JSON avec succès
            return response()->json([
                'status' => 200,
                'message' => 'Abonnement utilisateur mis à jour avec succès',
                'data' => $abonnementUtilisateur
            ]);
        } catch (\Exception $e) {
            // Gérer les exceptions et retourner une réponse JSON d'erreur
            return response()->json([
                'status' => 500,
                'message' => 'Une erreur est survenue lors de la mise à jour de l\'abonnement utilisateur',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function softDelete($id)
    {
        try {
            $AbonnementUtlisateurs = AbonnementUtlisateurs::findOrFail($id);
            $AbonnementUtlisateurs->delete();
            return response()->json([
                'message' => 'AbonnementUtlisateurs supprimé avec succès',
                'status' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la suppression du AbonnementUtlisateurs',
                'error' => $e->getMessage(),
                'status' => 500
            ], );
        }
    }
}
