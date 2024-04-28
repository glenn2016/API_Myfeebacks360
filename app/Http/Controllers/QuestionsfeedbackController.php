<?php

namespace App\Http\Controllers;

use App\Models\Questionsfeedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class QuestionsfeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $totalQeddbacks = Questionsfeedback::with('feddback')->get();

        return response()->json([
            'Questionsfeedback' => $totalQeddbacks,
            'status' => 200
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */

    public function create(Request $request){
        $validatedData = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'feddback_id' => ['required', 'numeric'],
        ]);

        $questionsfeedback = new Questionsfeedback();
        $questionsfeedback->nom = $validatedData['nom'];

        $questionsfeedback->feddback_id = $validatedData['feddback_id'];

        $questionsfeedback->save();

        return response()->json([
            'message' => 'questionsfeedback créé avec succès',
            'questionsfeedback' => $questionsfeedback,
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
            'Questionsfeedback' => Questionsfeedback::find($id),
            'message' => 'Questionsfeedback recuperer',
            'status' => 200
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Questionsfeedback $questionsfeedback)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id){
        
        $validator = Validator::make($request->all(), [
            'nom' => ['required', 'string', 'max:255'],
            'feddback_id' => ['required', 'numeric'],
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

        $questionsfeedback->feddback_id = $validatedData['feddback_id'];

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
