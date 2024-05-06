<?php

namespace App\Http\Controllers;

use App\Models\Reponsefeedback;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class ReponsefeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reponsefeedback = Reponsefeedback::with(['questionsfeedback.feedback','users'])->get();
    
        return response()->json([
            'reponsefeedback' => $reponsefeedback,
            'status' => 200
        ]);
    }
    
    /**
     * Show the form for creating a new resource.
     */
    /*
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reponses.*.questionsfeedbacks_id' => 'required|numeric',
            'reponses.*.nom' => 'required|string|max:255',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'status' => 400
            ], 400);
        }
    
        $user = Auth::user(); // Récupérer l'utilisateur authentifié
    
        $validatedData = $validator->validated();
    
        // Créer chaque réponse
        $reponses = [];
        
        foreach ($validatedData['reponses'] as $reponseData) {
            $reponsefeedback = new Reponsefeedback();
            $reponsefeedback->nom = $reponseData['nom'];
            $reponsefeedback->user_id = $user->id;
            $reponsefeedback->questionsfeedbacks_id = $reponseData['questionsfeedbacks_id'];
            $reponsefeedback->save();
    
            $reponses[] = $reponsefeedback;
        }
    
        return response()->json([
            'message' => 'Réponses créées avec succès',
            'reponses' => $reponses,
        ], 200);
    }
    */

    public function create(Request $request)
    {
        // Vérifier si la clé "reponses" est présente dans la requête
        if (!isset($request->reponses)) {
            return response()->json([
                'message' => 'La clé "reponses" est manquante dans la requête',
                'status' => 400
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'reponses.*.questionsfeedbacks_id' => 'required|numeric',
            'reponses.*.nom' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'status' => 400
            ], 400);
        }

        $user = Auth::user(); // Récupérer l'utilisateur authentifié

        $validatedData = $validator->validated();

        // Créer chaque réponse
        $reponses = [];

        foreach ($validatedData['reponses'] as $reponseData) {
            $reponsefeedback = new Reponsefeedback();
            $reponsefeedback->nom = $reponseData['nom'];
            $reponsefeedback->user_id = $user->id;
            $reponsefeedback->questionsfeedbacks_id = $reponseData['questionsfeedbacks_id'];
            $reponsefeedback->save();

            $reponses[] = $reponsefeedback;
        }

        return response()->json([
            'message' => 'Réponses créées avec succès',
            'reponses' => $reponses,
        ],200);
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
        $reponsefeedback = Reponsefeedback::with(['questionsfeedback.feedback','users'])->find($id);
    
        if (!$reponsefeedback) {
            return response()->json([
                'message' => 'Reponsefeedback non trouvé',
                'status' => 404
            ], 404);
        }
    
        return response()->json([
            'reponsefeedback' => $reponsefeedback,
            'status' => 200
        ]);
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
    public function update(Request $request, $id){
        
        $validator = Validator::make($request->all(), [
            'questionsfeedbacks_id' => ['required', 'numeric'], // Assurez-vous que evenement_id est numérique
            'nom' => ['required', 'string', 'max:255'],
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'status' => 400
            ], 400);
        }

        $user = Auth::user(); // Utilisez la méthode statique auth() de la classe Auth pour récupérer l'utilisateur authentifié
    
        $validatedData = $validator->validated();
    
        $validatedData = $validator->validated();
        $reponsefeedback = new Reponsefeedback();
        $reponsefeedback->nom = $validatedData['nom'];
        $reponsefeedback->user_id= $user->id; // Assurez-vous d'accéder à l'attribut id de l'utilisateur
        $reponsefeedback->questionsfeedbacks_id = $validatedData['questionsfeedbacks_id'];
    
        $reponsefeedback->save();

    
        return response()->json([
            'message' => 'reponsefeedback mis à jour avec succès',
            'reponsefeedback' => $reponsefeedback,
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function softDelete($id)
    {
        $reponsefeedback = Reponsefeedback::find($id);

        if ($reponsefeedback) {
            $reponsefeedback->delete(); // Utilise la suppression douce
            return response()->json([
                'message' => 'reponsefeedback soft deleted successfully',
                'status' => 200
            ]);
        } else {
            return response()->json([
                'message' => 'reponsefeedback not found',
                'status' => 404
            ], 404);
        }
    }
}
