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
        $evaluations = Evaluation::all();
        return response()->json([
            'evaluations' => $evaluations,
            'status' => 200
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request){

        $validator = Validator::make($request->all(), [
            'titre' => ['required', 'string', 'max:55'],
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'status' => 400
            ], 400);
        }
    
        $validatedData = $validator->validated();
    
        $Evaluation = new Evaluation();
        $Evaluation->titre = $validatedData['titre'];

        $Evaluation->save();
        return response()->json([
            'message' => 'Evaluation créé avec succès',
            'Evaluation' => $Evaluation,
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
            'Evaluation' => Evaluation::find($id),
            'message' => 'Evaluation recuperer',
            'status' => 200
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Evaluation $evaluation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'titre' => ['required', 'string', 'max:455'],
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'status' => 400
            ], 400);
        }
    
        $validatedData = $validator->validated();
    
        $evaluation = Evaluation::find($id);
        $evaluation->titre = $validatedData['titre'];
        $evaluation->save();
    
        return response()->json([
            'message' => 'Evaluation mise à jour avec succès',
            'Evaluation' => $evaluation,
        ], 200);
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
