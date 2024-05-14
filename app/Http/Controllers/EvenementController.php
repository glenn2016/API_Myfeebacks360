<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
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
            $user = Auth::user();   
            return response()->json([
                'evenements' =>Evenement::where('etat', 1,'usercreate', $user->id)->get(),
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

    public function create(Request $request)
    {
        try {
            $user = Auth::user();
            $validatedData = $request->validate([
                'titre' => ['required', 'string', 'max:255'],
                'description' => ['required', 'string', 'max:355'],
                'date_debut' => ['required', 'date'],
                'date_fin' => ['required', 'date'],
            ]);

            $Entreprise = new Evenement();
            $Entreprise->usercreate = $user->id;
            
            return response()->json([
                'Evenement' => Evenement::create($validatedData),
                'message' => 'Evenement créée avec succès',
                'stauts'=>200
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la création de l\'Evenement',
                'error' => $e->getMessage(),
                'status'=>500
            ], );
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