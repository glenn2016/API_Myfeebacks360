<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;



class AuthController extends Controller
{
    //

        /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = Auth::user();

        if ($user->etat === 0) {
            return response()->json([
                'error' => 'Votre compte est bloqué',
                'status'=>402
            ]);
        }
        $user = User::find(Auth::user()->id);
        $user_roles = $user->roles()->pluck('nom');
        return response()->json([
            'success' => true,
            'token' => $token,  
            'status' => 200,
            'roles' => $user_roles,
        ]);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }
    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }
     /*
     * Creation Admin 
     * 
     */
    public function createAdmin(Request $request){
        
        $userId = Auth::id(); 
        $validations = Validator::make($request->all(), [
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => 'required|string|min:8',
        ]);
        if ($validations->fails()) {
            $errors = $validations->errors();
            return response()->json([
                'errors' => $errors,
                'status' => 420
            ]);
        }
        if ($validations->passes()) {
            $user = User::create([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'email' => $request->email,
                'entreprise_abonements_id' => $request->entreprise_abonements_id,
                'password' => Hash::make($request->password),
                'usercreate'=> $userId
            ]
        );
            // Attache le rôle à l'utilisateur
            $user->roles()->attach(2);
            // Crée un jeton d'authentification pour l'utilisateur
            $token = $user->createToken('auth_token')->accessToken;
            return response()->json([
                'token' => $token,      
                'type' => 'Bearer'
            ]);
        }
    }
    /*
     * Creation Participant 
     * 
     */
    public function create(Request $request){
        $userId = Auth::id(); 
        $validations = Validator::make($request->all(), [
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => 'required|string|min:8',
        ]);
        if ($validations->fails()) {
            $errors = $validations->errors();
            return response()->json([
                'errors' => $errors,
                'status' => 420
            ]);
        }
        if ($validations->passes()) {
            $user = User::create([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'email' => $request->email,
                'categorie_id' => $request->categorie_id,
                'entreprise_id' => $request->entreprise_id,
                'password' => Hash::make($request->password),
                'usercreate'=> $userId
            ]);
            // Attache le rôle à l'utilisateur
            $user->roles()->attach(3);
            // Crée un jeton d'authentification pour l'utilisateur
            $token = $user->createToken('auth_token')->accessToken;
            return response()->json([
                'token' => $token,      
                'type' => 'Bearer'
            ]);
        }
    }
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
    /*
     * Liste Admin 
     * 
     */
    public function indexAdmin()
    {
        $users = User::whereHas('roles', function ($query) {
            $query->where('nom', 'Admin');
        })
        ->where('etat', 1) // Ajoutez cette condition pour filtrer les utilisateurs bloqués
        ->with('entrepriseAbonement','roles')
        ->get();    
        return response()->json([
            'Admins' => $users,   
            'status' => 200
        ]);
    }
    /*
     * Liste Participants 
     * 
     */
    public function index()
    {
        // Récupérez l'ID de l'utilisateur authentifié
        $userId = Auth::id();
        $users = User::whereHas('roles', function ($query) {
            $query->where('nom', 'Participant');
        })
        ->where('etat', 1) // Ajoutez cette condition pour filtrer les utilisateurs bloqués
        ->with('categorie', 'entreprise', 'roles')
        ->where('usercreate', $userId)
        ->get();    
        return response()->json([
            'participants' => $users,   
            'status' => 200
        ]);
    }
    /*
     * Liste Admin bloquer
     * 
     */
    public function indexAdminBloquer()
    {
        $users = User::whereHas('roles', function ($query) {
            $query->where('nom', 'Admin');
        })
        ->where('etat', 0) // Modifiez cette condition pour filtrer les utilisateurs avec un état égal à 1
        ->with('entrepriseAbonement','roles')
        ->get();
        return response()->json([
            'Admin' => $users,
            'status' => 200
        ]);
    }
    /*
     * Liste Participants bloquer
     * 
     */
    public function indexParticipantsBolquer()
    {
        $userId = Auth::id();
        $users = User::whereHas('roles', function ($query) {
            $query->where('nom', 'Participant');
        })
        ->where('etat', 0) // Modifiez cette condition pour filtrer les utilisateurs avec un état égal à 1
        ->with('categorie', 'entreprise', 'roles')
        ->where('usercreate', $userId)
        ->get();
        return response()->json([
            'participants' => $users,
            'status' => 200
        ]);
    }
    /*
     * Modification d'un utilisateur
     * 
     */
    public function update(Request $request, $id)
    {
        // Validation des données de la requête
        $validator = Validator::make($request->all(), [
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$id],
            'categorie_id' => ['nullable', 'exists:categories,id'],
            'entreprise_id' => ['nullable', 'exists:entreprises,id'],
        ]);
        // Vérifie si la validation a échoué
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'status' => 400
            ], 400);
        }
        // Récupère l'utilisateur à mettre à jour
        $user = User::findOrFail($id);
        // Mise à jour des attributs de l'utilisateur
        $user->nom = $request->nom;
        $user->prenom = $request->prenom;
        $user->email = $request->email;
        // Mise à jour de la catégorie et de l'entreprise si les IDs sont fournis dans la requête
        if ($request->filled('categorie_id')) {
            $user->categorie_id = $request->categorie_id;
        }
        if ($request->filled('entreprise_id')) {
            $user->entreprise_id = $request->entreprise_id;
        }
        // Enregistre les modifications
        $user->save();
        // Retourne la réponse JSONcavec l'utilisateur mis à jour
        return response()->json([
            'message' => 'Utilisateur mis à jour avec succès',
            'user' => $user,
            'status' => 200
        ], 200);
    }
    /*
     * Details d'un utilisateur
     * 
     * 
     */
    public function show($id)
    {
        $participant = User::with('categorie', 'entreprise', 'roles')->find($id);
        if (!$participant) {
            return response()->json([
                'message' => 'Participant non trouvé',
                'status' => 404
            ], 404);
        }
        return response()->json([
            'participant' => $participant,
            'message' => 'Contact récupéré',
            'status' => 200
        ]);
    }
    /*
     * Bloquer un utilisateur
     * 
     */
    public function bloquer($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }
        $user->etat = 0;
        $user->save();
        return response()->json(
            ['message' => 'Utilisateur bloqué avec succès','User'=>$user], 200);
    }
    /*
    *
     * Debloquer un utilisateur
     * 
     * 
     * 
     */
    public function debloquer($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }
        $user->etat = 1;
        $user->save();
        return response()->json(['message' => 'Utilisateur débloqué avec succès','User'=>$user], 200);
    }

    /*
     * Password forgot 
     * 
     * 
     */
    
}