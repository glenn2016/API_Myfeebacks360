<?php

namespace App\Http\Controllers;

use App\Models\Newsletter;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return response()->json([
                'newsletter' => Newsletter::all(),
                'status' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la récupération des newsletters',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        try {
            // Validation des données d'entrée avec l'email en minuscules
            $validatedData = $request->validate([
                'email' => ['required', 'string', 'email', 'max:255', 'unique:newsletters,email'],
            ]);
    
            // Transformation de l'email en minuscules pour éviter les conflits de casse
            $validatedData['email'] = strtolower($validatedData['email']);
    
            // Vérifier si l'email existe déjà dans la table 'newsletters'
            $existingNewsletter = Newsletter::where('email', $validatedData['email'])->first();
            if ($existingNewsletter) {
                return response()->json([
                    'message' => 'L\'adresse e-mail existe déjà dans la newsletter',
                    'newsletter' => $existingNewsletter,
                ], 409); // 409 Conflict
            }
    
            // Créer une nouvelle entrée dans la table 'newsletters'
            $newNewsletter = Newsletter::create($validatedData);
    
            // Retourner une réponse JSON avec la nouvelle entrée créée
            return response()->json([
                'newsletter' => $newNewsletter,
                'message' => 'Newsletter créée avec succès',
            ], 201); // 201 Created
        } catch (\Exception $e) {
            // Retourner une réponse JSON en cas d'erreur
            return response()->json([
                'message' => 'Une erreur est survenue lors de la création de la newsletter',
                'error' => $e->getMessage(),
            ], 500); // 500 Internal Server Error
        }
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function softDelete($id)
    {
        //
        $newsletter = Newsletter::find($id);
        if ($newsletter) {
            $newsletter->delete(); // Utilise la suppression douce
            return response()->json([
                'message' => 'newsletter soft deleted successfully',
                'status' => 200
            ]);
        } else {
            return response()->json([
                'message' => 'newsletter not found',
                'status' => 404
            ], 404);
        }
    }
}