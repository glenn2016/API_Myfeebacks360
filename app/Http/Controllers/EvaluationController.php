<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class EvaluationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    function index()
    {
        try {
            $user = Auth::user();

            return response()->json([
                'evaluations' => Evaluation::where('etat', 1)
                                            ->where('usercreate', $user->id)
                                            ->get(),
                'status' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la récupération des évaluations',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }

    public function indexevaluation()
    {
        try {
            $user = Auth::user();   
            
            // Récupérer les événements où le créateur de l'utilisateur est égal au créateur de l'événement
            $valuation = Evaluation::where('etat', 1)
                                    ->where('usercreate', $user->usercreate)
                                    ->get();
            
            return response()->json([
                'valuation' => $valuation,
                'status' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la récupération des evaluation',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }

    function indexarchiver()
    {
        try {
            return response()->json([
                'evaluations' => Evaluation::where('etat', 0)->get(),
                'status' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la récupération des évaluations',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }
    public function archiver($id)
    {
        try {
            $evenement = Evaluation::findOrFail($id);
            $evenement->update(['etat' => 0]);
            
            return response()->json([
                'message' => 'L\'évaluation a été archiver avec succès',
                'status' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la mise à jour de l\'évaluation',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }
    /**
     * Show the form for creating a new resource.
     */
    /*
    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'titre' => ['required', 'string', 'max:55'],
            ]);
            $validator->validate();
            return response()->json([
                'message' => 'Evaluation créée avec succès',
                'evaluation' => Evaluation::create($validator->validated()),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la création de l\'évaluation',
                'error' => $e->getMessage(),
            ], 500);
        }
    }*/
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            return response()->json([
                'evaluation' => Evaluation::findOrFail($id),
                'message' => 'Évaluation récupérée avec succès',
                'status' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'L\'évaluation demandée n\'a pas été trouvée',
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
            $evaluation = Evaluation::findOrFail($id);
            $validator = Validator::make($request->all(), [
                'titre' => ['required', 'string', 'max:455'],
            ]);
            $validator->validate();
            $evaluation->update($validator->validated());
            return response()->json([
                'message' => 'Evaluation mise à jour avec succès',
                'evaluation' => $evaluation,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la mise à jour de l\'évaluation',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function softDelete($id)
    {
        $evaluation = Evaluation::find($id);
        if ($evaluation) {
            $evaluation->delete(); // Utilise la suppression douce
            return response()->json([
                'message' => 'evaluation soft deleted successfully',
                'status' => 200
            ]);
        } else {
            return response()->json([
                'message' => 'evaluation not found',
                'status' => 404
            ], 404);
        }
    }
}
