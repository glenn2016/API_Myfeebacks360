<?php

namespace App\Http\Controllers;

use App\Models\Abonnement;
use Illuminate\Http\Request;

class AbonnementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        try {            
            return response()->json([
                'Abonnements' => Abonnement::all(),
                'status' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la récupération des Abonnement',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
        try {
            $validatedData = $request->validate([
                'formule' => ['required', 'string', 'max:255'],
                'temps' => ['required', 'string', 'max:255'],
                'prix' => ['required', 'string', 'max:255'],
            ]);
            return response()->json([
                'message' => 'Abonnement créé avec succès',
                'Abonnemente' => Abonnement::create($validatedData),
                'status'=>200
            ], );
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la création du Abonnement',
                'error' => $e->getMessage(),
                'status'=>500
            ], );
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
                'Abonnements' => Abonnement::find($id),
                'message' => 'Abonnements recuperer',
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
     * Show the form for editing the specified resource.
     */
    public function edit(Abonnement $abonnement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        try {
            $validatedData = $request->validate([
                'formule' => ['required', 'string', 'max:255'],
                'temps' => ['required', 'string', 'max:255'],
                'prix' => ['required', 'string', 'max:255'],
            ]);
            $Abonnement = Abonnement::findOrFail($id);
            $Abonnement->update($validatedData);
    
            return response()->json([
                'message' => 'Catégorie mise à jour avec succès',
                'Abonnement' => $Abonnement,
                'status' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la mise à jour de la catégorie',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function softDelete($id)
    {
        try {
            $Abonnement = Abonnement::findOrFail($id);
            $Abonnement->delete();
            return response()->json([
                'message' => 'Abonnement supprimé avec succès',
                'status' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la suppression du Abonnement',
                'error' => $e->getMessage(),
                'status' => 500
            ], );
        }
    }
}
