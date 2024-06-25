<?php

namespace App\Http\Controllers;

use App\Models\ContactAbonement;
use Illuminate\Http\Request;

class ContactAbonementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Récupérer tous les contacts abonnements avec les informations de l'abonnement associé
            $contactsAbonnements = ContactAbonement::with('abonnement')->get();

            return response()->json([
                'categories' => $contactsAbonnements,
                'status' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la récupération des catégories',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request){
        try {
            $validatedData = $request->validate([
                'prenom' => ['required', 'string', 'max:255'],
                'nom' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255'],
                'message' => ['nullable', 'string', 'max:255'],
                'entreprise' => ['required', 'string', 'max:255'],
                'numeroTelephone' => ['required', 'string', 'max:255'],
                'poste' => ['required', 'string', 'max:255'],
                'telephoneFixe' => ['nullable', 'string', 'max:255'],
                'adressEntreprise' => ['required', 'string', 'max:255'],
                'ville' => ['required', 'string', 'max:255'],
                'pays' => ['required', 'string', 'max:255'],
                'Abonnement_id' => ['required', 'numeric'],
            ]);
            return response()->json([
                'message' => 'Contact créé avec succès',
                'contacte' => ContactAbonement::create($validatedData),
                'status'=>200
            ], );
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la création du contact',
                'error' => $e->getMessage(),
                'status'=>500
            ], );
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function show($id){
        try {
        return response()->json([
            'contacte' => ContactAbonement::find($id),
            'message' => 'contacte recuperer',
            'status' => 200
        ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Le ContactAbonement demandé n\'a pas été trouvé',
                'error' => $e->getMessage(),
                'status' => 404
            ], 404);
        }
    }
    /**
     * Display the specified resource.
     */

    /**
     * Show the form for editing the specified resource.
     */

    /**
     * Remove the specified resource from storage.
     */
    public function softDelete($id)
    {
        try {
            $contacte = ContactAbonement::findOrFail($id);
            $contacte->delete();
            return response()->json([
                'message' => 'ContactAbonement supprimé avec succès',
                'status' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la suppression du ContactAbonement',
                'error' => $e->getMessage(),
                'status' => 500
            ], );
        }
    }

    public function getAbonnementByContactAbonnementId($id)
    {
        try {
            // Récupérer le contact abonnement avec les détails de l'abonnement associé
            $contactAbonnement = ContactAbonement::with('abonnement')->findOrFail($id);

            return response()->json([
                'status' => 200,
                'message' => 'Détails de l\'abonnement récupérés avec succès',
                'abonnement' => $contactAbonnement->abonnement
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Une erreur est survenue lors de la récupération de l\'abonnement',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 
