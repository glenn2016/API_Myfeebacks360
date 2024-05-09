<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use Illuminate\Http\Request;

class EvenementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return response()->json([
                'evenements' => Evenement::all(),
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
    /**
     * Show the form for creating a new resource.
     */
    /**
     * Update the specified resource in storage.
     */
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
    }
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
}