<?php

namespace App\Http\Controllers;

use App\Models\Contacte;
use Illuminate\Http\Request;

class ContacteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $evenements = Contacte::all();
        return response()->json([
            'Contactes' => $evenements,
            'status' => 200
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request){
        $validatedData = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255'],
            'telephone' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:255'],
        ]);
    
        $contacte = new Contacte();
        $contacte->nom = $validatedData['nom'];
        $contacte->prenom = $validatedData['prenom'];
        $contacte->email = $validatedData['email'];
        $contacte->telephone = $validatedData['telephone'];
        $contacte->message = $validatedData['message'];
        $contacte->save();
    
        return response()->json([
            'message' => 'contacte créé avec succès',
            'contacte' => $contacte,
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
            'contacte' => Contacte::find($id),
            'message' => 'contacte recuperer',
            'status' => 200
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contacte $contacte)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contacte $contacte)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contacte $contacte)
    {
        //
    }
}
