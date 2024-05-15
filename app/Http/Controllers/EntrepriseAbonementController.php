<?php

namespace App\Http\Controllers;

use App\Models\EntrepriseAbonement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EntrepriseAbonementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        try {
            $user = Auth::user();
            return response()->json([
                'EntrepriseAbonement' => EntrepriseAbonement::where('usercreate', $user->id)->get(),
                'status' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la récupération des EntrepriseAbonement',
                'error' => $e->getMessage(),-
                'status' => 500
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
        try {
            $validatedData = $request->validate([
                'nom' => 'required|string',
                'email' => 'required|email|unique:entreprise_abonements',
                'numeroTelUn' => 'required|string|unique:entreprise_abonements',
                'numeroTelDeux' => 'nullable|string|unique:entreprise_abonements',
                'pays' => 'required|string',
                'ville' => 'required|string',
                'adresse' => 'required|string',
            ]);
            $user = Auth::user();

            $entrepriseAbonement = new EntrepriseAbonement();
            $entrepriseAbonement->usercreate = $user->id;

            return response()->json([
                'message' => 'EntrepriseAbonement créé avec succès',
                'EntrepriseAbonemente' => EntrepriseAbonement::create($validatedData),
                'status'=>200
            ], );
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la création d\'une EntrepriseAbonement',
                'error' => $e->getMessage(),
                'status'=>500
            ], );
        }
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
        try {
            return response()->json([
                'contacte' => EntrepriseAbonement::find($id),
                'message' => 'contacte recuperer',
                'status' => 200
            ]);
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Le EntrepriseAbonement demandé n\'a pas été trouvé',
                    'error' => $e->getMessage(),
                    'status' => 404
                ], 404);
            }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EntrepriseAbonement $entrepriseAbonement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        try {
            $validatedData = $request->validate([
                'nom' => 'required|string',
                'email' => 'required|email|unique:entreprise_abonements',
                'numeroTelUn' => 'required|string|unique:entreprise_abonements',
                'numeroTelDeux' => 'nullable|string|unique:entreprise_abonements',
                'pays' => 'required|string',
                'ville' => 'required|string',
                'adresse' => 'required|string',
            ]);
    
            $entrepriseAbonement = EntrepriseAbonement::findOrFail($id);
            $entrepriseAbonement->update($validatedData);
    
            return response()->json([
                'message' => 'entrepriseAbonement mis à jour avec succès',
                'entrepriseAbonement' => $entrepriseAbonement,
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
        $entrepriseAbonement = EntrepriseAbonement::find($id);
        if ($entrepriseAbonement) {
            $entrepriseAbonement->delete(); // Utilise la suppression douce
            return response()->json([
                'message' => 'entrepriseAbonement soft deleted successfully',
                'status' => 200
            ]);
        } else {
            return response()->json([
                'message' => 'entrepriseAbonement not found',
                'status' => 404
            ], 404);
        }
    }
}
