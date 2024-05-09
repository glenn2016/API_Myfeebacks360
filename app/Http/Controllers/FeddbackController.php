<?php

namespace App\Http\Controllers;

use App\Models\Feddback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FeddbackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return response()->json([
                'feedbacks' => Feddback::with('evenement')->get(),
                'status' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la récupération des feedbacks',
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
            $validatedData = $request->validate([
                'evenement_id' => ['required', 'numeric'],
                'titre' => ['required', 'string', 'max:255'],
            ]);
            return response()->json([
                'message' => 'Feedback créé avec succès',
                'feedback' => Feddback::create($validatedData),
                'status'=>'200'
            ],);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la création du feedback',
                'error' => $e->getMessage(),
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
                'Feedback' => Feddback::findOrFail($id),
                'message' => 'Feedback récupéré avec succès',
                'status' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Le feedback demandé n\'a pas été trouvé',
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
            $validator = Validator::make($request->all(), [
                'evenement_id' => ['required', 'numeric'], // Assurez-vous que evenement_id est numérique
                'titre' => ['required', 'string', 'max:255'],
            ]);
            $validatedData = $validator->validate();
            $feedback = Feddback::findOrFail($id);
            $feedback->update($validatedData);
            return response()->json([
                'message' => 'Feedback mis à jour avec succès',
                'feedback' => $feedback,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la mise à jour du feedback',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function softDelete($id)
    {
        $feddback = Feddback::find($id);
        if ($feddback) {
            $feddback->delete(); // Utilise la suppression douce
            return response()->json([
                'message' => 'Feddback soft deleted successfully',
                'status' => 200
            ]);
        } else {
            return response()->json([
                'message' => 'Feddback not found',
                'status' => 404
            ], 404);
        }
    }
}