<?php

namespace App\Http\Controllers;

use App\Models\Entreprise;
use Illuminate\Http\Request;

class EntrepriseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    function index()
    {
        $entreprises = Entreprise::all();
        return response()->json([
            'entreprises' => $entreprises,
            'status' => 200
        ]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request){
        $validatedData = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
        ]);
        $Entreprise = new Entreprise();
        $Entreprise->nom = $validatedData['nom'];
        $Entreprise->save();
        return response()->json([
            'message' => 'Entreprise créé avec succès',
            'Entreprise' => $Entreprise,
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
            'Entreprise' => Entreprise::find($id),
            'message' => 'Entreprise recuperer',
            'status' => 200
        ]);
    } 

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Entreprise $entreprise)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
        ]);

        $entreprise = Entreprise::findOrFail($id);

        $entreprise->nom = $validatedData['nom'];
    
        $entreprise->save();

        return response()->json([
            'message' => 'Entreprise mise à jour avec succès',
            'entreprise' => $entreprise,
            'status'=>200
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Entreprise $entreprise)
    {
        //
    }
}
