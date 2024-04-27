<?php

namespace App\Http\Controllers;

use App\Models\Feddback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FeddbackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $totalFeddbacks = Feddback::all();
        return response()->json([
            'feedbacks' => $totalFeddbacks,
            'status' => 200
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request){

        $validator = Validator::make($request->all(), [
            'evenement_id' => ['required', 'numeric'], // Assurez-vous que evenement_id est numérique
            'titre' => ['required', 'string', 'max:255'],
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'status' => 400
            ], 400);
        }
    
        $validatedData = $validator->validated();
    
        $feedback = new Feddback();
        $feedback->commentaire = $validatedData['titre'];
        $feedback->date = $validatedData['date'];
        $feedback->evenement_id = $validatedData['evenement_id'];
        $feedback->save();
    
        return response()->json([
            'message' => 'Feedback créé avec succès',
            'feedback' => $feedback,
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
            'Fedddbacks' => Feddback::find($id),
            'message' => 'Fedddbacks recuperer',
            'status' => 200
        ]);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Feddback $feddback)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'evenement_id' => ['required', 'numeric'], // Assurez-vous que evenement_id est numérique
            'titre' => ['required', 'string', 'max:255'],
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'status' => 400
            ], 400);
        }
    
        $validatedData = $validator->validated();

        $feedback = Feddback::find($id);

        $feedback->commentaire = $validatedData['titre'];
        $feedback->date = $validatedData['date'];
        $feedback->evenement_id = $validatedData['evenement_id'];

        $feedback->save();

    
        return response()->json([
            'message' => 'Feedback mis à jour avec succès',
            'feedback' => $feedback,
        ], 200);
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
