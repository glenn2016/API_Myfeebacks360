<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use App\Models\User;

class RefreshTokenController extends Controller
{
    //

     public function refresh(Request $request)
    {
        // Extraire le token expiré du corps de la requête
        $expiredToken = $request->input('token');

        try {
            // Décoder le token expiré pour obtenir les informations de l'utilisateur
            $decodedToken = JWT::decode($expiredToken, 'votre_secret', ['HS256']);
            
            // Récupérer l'utilisateur à partir des informations du token
            $user = User::find($decodedToken->user_id);
            
            // Générer un nouveau token avec une nouvelle durée de validité
            $newToken = JWT::encode([
                'user_id' => $user->id,
                'exp' => time() + 3600, // Expire dans 1 heure
            ], 'votre_secret');

            // Retourner le nouveau token
            return response()->json(['token' => $newToken]);
        } catch (\Exception $e) {
            // En cas d'erreur lors du décodage du token ou de la recherche de l'utilisateur,
            // retourner une réponse d'erreur appropriée
            return response()->json(['error' => 'Token invalide'], 401);
        }
    }
}