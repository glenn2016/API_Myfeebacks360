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
        //
        $newsletter = Newsletter::all();

        return response()->json([
            'newsletter' => $newsletter,
            'status' => 200
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */

    public function create(Request $request){
        // Assurez-vous que l'ID est disponible dans la requête ou d'autres parties du code
        $id = $request->input('id');
    
        // Valider l'adresse e-mail uniquement si l'ID est fourni
        $uniqueRule = $id ? 'unique:users,email,'.$id : 'unique:users,email';
    
        $validatedData = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', $uniqueRule],
        ]);
    
        // Vérifier si l'e-mail existe déjà dans la base de données
        $existingNewsletter = Newsletter::where('email', $validatedData['email'])->first();
    
        if ($existingNewsletter) {
            // L'e-mail existe déjà, vous pouvez choisir de renvoyer un message d'erreur ou mettre à jour l'entrée existante
            return response()->json([
                'message' => 'L\'adresse e-mail existe déjà dans la newsletter',
                'newsletter' => $existingNewsletter,
            ], 409); // Code de réponse 409 pour conflit
        }
    
        // L'e-mail n'existe pas encore, vous pouvez créer une nouvelle entrée
        $newsletter = new Newsletter();
        $newsletter->email = $validatedData['email'];
        $newsletter->save();
    
        return response()->json([
            'message' => 'Newsletter créé avec succès',
            'newsletter' => $newsletter,
        ], 200);
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
    public function show(Newsletter $newsletter)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Newsletter $newsletter)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Newsletter $newsletter)
    {
        //
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
