<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use Illuminate\Http\Request;

class EvenementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    function index()
    {
        $evenements = Evenement::all();
        return response()->json([
            'evenements' => $evenements,
            'status' => 200
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request){
        $validatedData = $request->validate([
            'titre' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:355'],
            'date_debut' => ['required', 'date'],
            'date_fin' => ['required', 'date'],
        ]);
    
        $Evenement = new Evenement();
        $Evenement->titre = $validatedData['titre'];
        $Evenement->description = $validatedData['description'];
        $Evenement->date_debut = $validatedData['date_debut'];
        $Evenement->date_fin = $validatedData['date_fin'];
        $Evenement->save();
    
        return response()->json([
            'message' => 'Evenement créé avec succès',
            'Evenement' => $Evenement,
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
    public function show($id){
        return response()->json([
            'Evenement' => Evenement::find($id),
            'message' => 'Evenement recuperer',
            'status' => 200
        ]);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Evenement $evenement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'titre' => ['requ   ired', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:355'],
            'date_debut' => ['required', 'date'],
            'date_fin' => ['required', 'date'],
        ]);
        $evenement = Evenement::findOrFail($id);

        $evenement->titre = $validatedData['titre'];
        $evenement->description = $validatedData['description'];
        $evenement->date_debut = $validatedData['date_debut'];
        $evenement->date_fin = $validatedData['date_fin'];

        $evenement->save();

        return response()->json([
            'message' => 'Evenement mis à jour avec succès',
            'Evenement' => $evenement,
        ]);
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
