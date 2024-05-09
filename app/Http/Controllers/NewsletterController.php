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
            $id = $request->input('id');
            $uniqueRule = $id ? 'unique:users,email,'.$id : 'unique:users,email';
    
            $validatedData = $request->validate([
                'email' => ['required', 'string', 'email', 'max:255', $uniqueRule],
            ]);
    
            $existingNewsletter = Newsletter::where('email', $validatedData['email'])->first();
            if ($existingNewsletter) {
                return response()->json([
                    'message' => 'L\'adresse e-mail existe déjà dans la newsletter',
                    'newsletter' => $existingNewsletter,
                ], 409);
            }
            return response()->json([
                'newsletter' => Newsletter::create($validatedData),
                'message' => 'Newsletter créée avec succès',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la création de la newsletter',
                'error' => $e->getMessage(),
            ], 500);
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