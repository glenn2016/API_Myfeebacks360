<?php

namespace App\Http\Controllers;

use App\Models\Entreprise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EntrepriseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    function index()
    {
        try {
            $user = Auth::user();
            return response()->json([
                'entreprises' => Entreprise::where('usercreate', $user->id)->get(),
                'status' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la récupération des entreprises',
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
        try {
            $userId = Auth::id(); // Récupérer l'ID de l'utilisateur authentifié
            $validatedData = $request->validate([
                'nom' => ['required', 'string', 'max:255'],
            ]);
    
            // Créez une nouvelle instance de Entreprise et attribuez l'ID de l'utilisateur à la propriété usercreate
            $entreprise = new Entreprise();
            $entreprise->nom = $validatedData['nom'];
            $entreprise->usercreate = $userId;
    
            // Enregistrez l'entreprise
            $entreprise->save();
    
            return response()->json([
                'entreprise' => $entreprise,
                'message' => 'Entreprise créée avec succès',
                'status' => 200
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la création de l\'entreprise',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }
    
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            return response()->json([
                'entreprise' =>  Entreprise::findOrFail($id),
                'message' => 'Entreprise récupérée avec succès',
                'status' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'L\'entreprise demandée n\'a pas été trouvée',
                'error' => $e->getMessage(),
                'status' => 404
            ], 404);
        }
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'nom' => ['required', 'string', 'max:255'],
            ]);
            $entreprise = Entreprise::findOrFail($id);
            $entreprise->update($validatedData);
            return response()->json([
                'message' => 'Entreprise mise à jour avec succès',
                'entreprise' => $entreprise,
                'status' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la mise à jour de l\'entreprise',
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
        $entreprise = Entreprise::find($id);
        if ($entreprise) {
            $entreprise->delete(); // Utilise la suppression douce
            return response()->json([
                'message' => 'entreprise soft deleted successfully',
                'status' => 200
            ]);
        } else {
            return response()->json([
                'message' => 'entreprise not found',
                'status' => 404
            ], 404);
        }
    }
}