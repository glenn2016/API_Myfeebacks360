<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AbonnementUtlisateurs;
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
    /*
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
    }*/

    public function createAdmin(Request $request)
    {
        try {
            $userId = Auth::id();
            
            // Validation des données d'entrée
            $validations = Validator::make($request->all(), [
                'nom' => ['required', 'string', 'max:255'],
                'prenom' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
                'password' => 'required|string|min:8',
                'abonnement_id' => ['required', 'exists:abonnements,id'],
                'entrepriseAbaonement' => ['required', 'string', 'max:255'],
                'date_debut_abonnement' => ['required', 'date'],
                'date_fin_abonnement' => ['required', 'date', 'after_or_equal:date_debut_abonnement'],
            ]);

            // Retourner les erreurs de validation s'il y en a
            if ($validations->fails()) {
                $errors = $validations->errors();
                return response()->json([
                    'errors' => $errors,
                    'status' => 422 // Code d'état HTTP pour erreur de validation
                ]);
            }

            // Si la validation réussit, créer l'utilisateur
            if ($validations->passes()) {
                // Crée l'utilisateur avec les données validées
                $user = User::create([
                    'nom' => $request->nom,
                    'prenom' => $request->prenom,
                    'email' => $request->email,
                    'entrepriseAbaonement' => $request->entrepriseAbaonement,
                    'password' => Hash::make($request->password),
                    'usercreate' => $userId
                ]);

                // Attache le rôle 'Admin' à l'utilisateur
                $user->roles()->attach(2); // 2 étant l'ID du rôle 'Admin'

                // Crée un enregistrement d'abonnement utilisateur
                AbonnementUtlisateurs::create([
                    'abonnement_id' => $request->abonnement_id,
                    'utlisateur_id' => $user->id,
                    'date_debut_abonement' => $request->date_debut_abonnement,
                    'date_fin_abonement' => $request->date_fin_abonnement,
                ]);

                // Crée un jeton d'authentification pour l'utilisateur
                $token = $user->createToken('auth_token')->accessToken;

                // Retourner une réponse JSON avec le token d'authentification
                return response()->json([
                    'token' => $token,
                    'type' => 'Bearer',
                    'message' => 'Admin créé avec succès',
                    'status' => 201
                ], 201); // 201 Created
            }
        } catch (\Exception $e) {
            // En cas d'exception, retourner une réponse JSON avec un message d'erreur
            return response()->json([
                'message' => 'Erreur lors de la création de l\'admin',
                'error' => $e->getMessage(),
            ], 500); // 500 Internal Server Error
        }
    }


    /*

    public function createAdmin(Request $request)
    {
        $userId = Auth::id(); 
        $validations = Validator::make($request->all(), [
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => 'required|string|min:8',
            'abonnement_id' => ['required', 'exists:abonnements,id'],
            'entrepriseAbaonement' => ['required', 'string', 'max:255'],
            'date_debut_abonnement' => ['required', 'date'],
            'date_fin_abonnement' => ['required', 'date', 'after_or_equal:date_debut_abonnement'],
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
                'entrepriseAbaonement'=>$request->entrepriseAbaonement,
                'password' => Hash::make($request->password),
                'usercreate'=> $userId
            ]);

            // Attache le rôle à l'utilisateur
            $user->roles()->attach(2);

            // Crée un enregistrement d'abonnement utilisateur
            AbonnementUtlisateurs::create([
                'abonnement_id' => $request->abonnement_id,
                'utlisateur_id' => $user->id,
                'date_debut_abonement' => $request->date_debut_abonnement,
                'date_fin_abonement' => $request->date_fin_abonnement,
            ]);

            // Crée un jeton d'authentification pour l'utilisateur
            $token = $user->createToken('auth_token')->accessToken;

            return response()->json([
                'token' => $token,      
                'type' => 'Bearer'
            ]);
        }
    }
    */

    /*
     * Creation Participant 
     * 
     */
    /*
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
    */
    public function create(Request $request)
    {
        try {
            $userId = Auth::id(); // Récupérer l'ID de l'utilisateur connecté
            
            // Validation des données d'entrée
            $validations = Validator::make($request->all(), [
                'nom' => ['required', 'string', 'max:255'],
                'prenom' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
                'password' => 'required|string|min:8',
            ]);

            // Si la validation échoue, retourner les erreurs
            if ($validations->fails()) {
                return response()->json([
                    'errors' => $validations->errors(),
                    'status' => 422 // Code d'état HTTP pour erreur de validation
                ]);
            }

            // Si la validation réussit, procéder à la création de l'utilisateur
            if ($validations->passes()) {
                // Créer l'utilisateur avec les données validées
                $user = User::create([
                    'nom' => $request->nom,
                    'prenom' => $request->prenom,
                    'email' => $request->email,
                    'entreprise_id' => $request->entreprise_id, // Assurez-vous que ce champ est présent dans la table 'users'
                    'password' => Hash::make($request->password),
                    'usercreate' => $userId
                ]);

                // Assigner le rôle spécifique à l'utilisateur (ex. ID 3 pour un rôle particulier)
                $user->roles()->attach(3);

                // Créer un jeton d'authentification pour l'utilisateur
                $token = $user->createToken('auth_token')->accessToken;

                // Retourner une réponse JSON avec le token d'authentification
                return response()->json([
                    'token' => $token,
                    'type' => 'Bearer',
                    'message' => 'Utilisateur créé avec succès',
                    'status' => 201
                ], 201); // 201 Created
            }
        } catch (\Exception $e) {
            // En cas d'exception, retourner une réponse JSON avec un message d'erreur
            return response()->json([
                'message' => 'Erreur lors de la création de l\'utilisateur',
                'error' => $e->getMessage(),
            ], 500); // 500 Internal Server Error
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
        try {
            // Récupérer les utilisateurs avec le rôle "Admin" et qui ne sont pas bloqués (etat = 1)
            $users = User::whereHas('roles', function ($query) {
                $query->where('nom', 'Admin');
            })
            ->where('etat', 1) // Filtrer les utilisateurs avec etat = 1 (non bloqués)
            ->with('entrepriseAbonement', 'roles') // Charger les relations 'entrepriseAbonement' et 'roles'
            ->get();
    
            // Retourner la réponse JSON avec les administrateurs et un statut HTTP 200
            return response()->json([
                'Admins' => $users,
                'status' => 200
            ]);
    
        } catch (\Exception $e) {
            // En cas d'exception, retourner une réponse JSON avec un message d'erreur
            return response()->json([
                'message' => 'Erreur lors de la récupération des administrateurs',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500); // 500 Internal Server Error
        }
    }
    
    /*
     * Liste Participants 
     * 
     */
    public function index()
    {
        try {
            // Récupérer l'ID de l'utilisateur authentifié
            $userId = Auth::id();
    
            // Récupérer les utilisateurs ayant le rôle "Participant" et qui ne sont pas bloqués (etat = 1)
            $users = User::whereHas('roles', function ($query) {
                $query->where('nom', 'Participant');
            })
            ->where('etat', 1) // Filtrer les utilisateurs non bloqués
            ->with('categorie', 'entreprise', 'roles') // Charger les relations 'categorie', 'entreprise' et 'roles'
            ->where('usercreate', $userId) // Filtrer les utilisateurs créés par l'utilisateur actuel
            ->get();
    
            // Retourner une réponse JSON avec la liste des participants et un statut HTTP 200
            return response()->json([
                'participants' => $users,
                'status' => 200
            ]);
    
        } catch (\Exception $e) {
            // En cas d'exception, retourner une réponse JSON avec un message d'erreur et un code HTTP 500
            return response()->json([
                'message' => 'Erreur lors de la récupération des participants',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500); // 500 Internal Server Error
        }
    }
        
    /*
     * Liste Admin bloquer
     * 
     */
    public function indexAdminBloquer()
    {
        try {
            // Récupérer les utilisateurs avec le rôle "Admin" et qui sont bloqués (etat = 0)
            $users = User::whereHas('roles', function ($query) {
                $query->where('nom', 'Admin');
            })
            ->where('etat', 0) // Filtrer les utilisateurs avec etat = 0 (bloqués)
            ->with('entrepriseAbonement', 'roles') // Charger les relations 'entrepriseAbonement' et 'roles'
            ->get();
    
            // Retourner la réponse JSON avec les administrateurs bloqués et un statut HTTP 200
            return response()->json([
                'Admin' => $users,
                'status' => 200
            ]);
    
        } catch (\Exception $e) {
            // En cas d'exception, retourner une réponse JSON avec un message d'erreur
            return response()->json([
                'message' => 'Erreur lors de la récupération des administrateurs bloqués',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500); // 500 Internal Server Error
        }
    }
    
    /*
     * Liste Participants bloquer
     * 
     */
    public function indexParticipantsBloquer()
    {
        try {
            // Récupérez l'ID de l'utilisateur authentifié
            $userId = Auth::id();
    
            // Récupérer les participants bloqués associés à l'utilisateur actuel
            $users = User::whereHas('roles', function ($query) {
                $query->where('nom', 'Participant');
            })
            ->where('etat', 0) // Filtrer les utilisateurs avec etat = 0 (bloqués)
            ->with('categorie', 'entreprise', 'roles') // Charger les relations 'categorie', 'entreprise' et 'roles'
            ->where('usercreate', $userId) // Filtrer par l'utilisateur créateur actuel
            ->get();
    
            // Retourner la réponse JSON avec les participants bloqués et un statut HTTP 200
            return response()->json([
                'participants' => $users,
                'status' => 200
            ]);
    
        } catch (\Exception $e) {
            // En cas d'exception, retourner une réponse JSON avec un message d'erreur
            return response()->json([
                'message' => 'Erreur lors de la récupération des participants bloqués',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500); // 500 Internal Server Error
        }
    }
    
    /*
     * Modification d'un utilisateur
     * 
     */    
    public function update(Request $request, $id)
    {
        try {
            // Validation des données de la requête
            $validator = Validator::make($request->all(), [
                'nom' => ['required', 'string', 'max:255'],
                'prenom' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $id],
                'entreprise_id' => ['nullable', 'exists:entreprises,id'],
                'entrepriseAbaonement' => ['nullable', 'string', 'max:255'],
                'password' => 'required|string|min:8',

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
            $user->entrepriseAbaonement = $request->entrepriseAbaonement;
            $user->prenom = $request->prenom;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);

    
            if ($request->filled('entreprise_id')) {
                $user->entreprise_id = $request->entreprise_id;
            }
    
            // Enregistre les modifications
            $user->save();
    
            // Retourne la réponse JSON avec l'utilisateur mis à jour
            return response()->json([
                'message' => 'Utilisateur mis à jour avec succès',
                'user' => $user,
                'status' => 200
            ], 200);
    
        } catch (\Exception $e) {
            // En cas d'exception, retourne une réponse JSON avec un message d'erreur
            return response()->json([
                'message' => 'Une erreur est survenue lors de la mise à jour de l\'utilisateur',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500); // 500 Internal Server Error
        }
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