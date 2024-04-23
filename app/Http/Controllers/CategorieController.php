<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Categorie::all();
    
        return response()->json([
            'categories' => $categories,
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
        $Categorie = new Categorie();
        $Categorie->nom = $validatedData['nom'];
        $Categorie->save();
        return response()->json([
            'message' => 'Categorie créé avec succès',
            'Categorie' => $Categorie,
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
    public function show($id)
    {
        //
        return response()->json([
            'Categorie' => Categorie::find($id),
            'message' => 'Categorie recuperer',
            'status' => 200
        ]);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Categorie $categorie)
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

        $Categorie = Categorie::findOrFail($id);

        $Categorie->nom = $validatedData['nom'];
    
        $Categorie->save();

        return response()->json([
            'message' => 'Categorie mise à jour avec succès',
            'Categorie' => $Categorie,
            'status'=>200
        ]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function softDelete($id)
    {
        $categorie = Categorie::find($id);
    
        if ($categorie) {
            $categorie->delete(); // Utilise la suppression douce
            return response()->json([
                'message' => 'categorie soft deleted successfully',
                'status' => 200
            ]);
        } else {
            return response()->json([
                'message' => 'categorie not found',
                'status' => 404
            ], 404);
        }
    }

    
}
