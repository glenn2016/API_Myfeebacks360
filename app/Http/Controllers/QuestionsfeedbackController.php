<?php

namespace App\Http\Controllers;

use App\Models\Questionsfeedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class QuestionsfeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return response()->json([
                'Questionsfeedback' => Questionsfeedback::with('evenement')->get(),
                'status' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la récupération des questions de feedback',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function evenementquestion($evenement_id)
    {
        try {
            // Récupérer toutes les questions associées à l'événement spécifié
            return response()->json([
                'questions' => Questionsfeedback::where('evenement_id', $evenement_id)->get(),
                'status' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la récupération des questions associées à l\'événement',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $userId = Auth::id();
        try {
            $validatedData = $request->validate([
                'nom' => ['required', 'string', 'max:255'],
                'evenement_id' => ['required', 'numeric'],
            ]);
            $questionsFeedback = Questionsfeedback::create([
                'nom' => $validatedData['nom'],
                'evenement_id' => $validatedData['evenement_id'],
                'usercreate'=> $userId
            ]);
            return response()->json([
                'message' => 'Question de feedback créée avec succès',
                'questionsFeedback' => $questionsFeedback,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la création de la question de feedback',
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
                'Questionsfeedback' => Questionsfeedback::findOrFail($id),
                'message' => 'Question de feedback récupérée avec succès',
                'status' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'La question de feedback demandée n\'a pas été trouvée',
                'error' => $e->getMessage(),
                'status' => 404
            ], 404);
        }
    }
    /**
     * Update the specified resource in storage.
     */
    /*
    public function update(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'nom' => ['required', 'string', 'max:255'],
            'evenement_id' => ['required', 'numeric'],
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'status' => 400
            ], 400);
        }
        $validatedData = $validator->validated();
        $questionsfeedback = new Questionsfeedback();
        $questionsfeedback->nom = $validatedData['nom'];
        $questionsfeedback->evenement_id = $validatedData['evenement_id'];
        $questionsfeedback->save();
        return response()->json([
            'message' => 'questionsfeedback mis à jour avec succès',
            'questionsfeedback' => $questionsfeedback,
        ], 200);
    }
    /**
     * Remove the specified resource from storage.
     */
     public function softDelete($id)
     {
         $questionsfeedback = Questionsfeedback::find($id);
         if ($questionsfeedback) {
             $questionsfeedback->delete(); // Utilise la suppression douce
             return response()->json([
                 'message' => 'questionsfeedback soft deleted successfully',
                 'status' => 200
             ]);
         } else {
             return response()->json([
                 'message' => 'questionsfeedback not found',
                 'status' => 404
             ], 404);
         }
     }
}