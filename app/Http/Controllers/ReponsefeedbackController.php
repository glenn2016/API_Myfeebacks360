<?php

namespace App\Http\Controllers;

use App\Models\Reponsefeedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReponsefeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $reponsefeedback = Reponsefeedback::all();
        return response()->json([
            'feedbacks' => $reponsefeedback,
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
    
        $feedback = new Reponsefeedback();
        $feedback->titre = $validatedData['titre'];
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
    public function show(Reponsefeedback $reponsefeedback)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reponsefeedback $reponsefeedback)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reponsefeedback $reponsefeedback)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reponsefeedback $reponsefeedback)
    {
        //
    }
}
