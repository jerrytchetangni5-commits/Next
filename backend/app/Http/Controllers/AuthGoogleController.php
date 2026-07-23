<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthGoogleController extends Controller
{
    public function googleLogin(Request $request)
    {
        $request->validate([
            'id_token' => 'required|string',
        ]);

        //Socialite va contacter l'API Google pour vérifier que le token est valide et récupérer les informations de l'utilisateur (email, nom, etc)
        try{
            $googleUser = Socialite::driver('google')->userFromToken($request->id_token);
        } catch (\Exception $e) {

        //si le token est invalide ou expiré Google refuse la connexion
        //on renvoie une erreur 401
            return response()->json([
                'success' => false,
                'message' => 'Token Google invalide ou expiré.',
            ], 401);
        }

        //google renvoie tjrs l'email, mais on verifie par sécurité
        if (!$googleUser->getEmail()){
            return response()->json([
                'success' => false,
                'message' => 'Aucun email fourni par Google'
            ], 400); //400pour une requete mal formulé
        }

        //on cherche un utisateur existant
        $user = User::where('email', $googleUser->getEmail())->first();

        if (!$user) {
            $user = User::create([
                'first_name' => $googleUser->user['given_name'] ?? 'Google', 
                'last_name' => $googleUser->user['family_name'] ?? 'User',
                'email' => $googleUser->getEmail(),
                'password' => Hash::make(Str::random(24)),
                'country' => null,
                'role' => 'student',
                'google_id' => $googleUser->getId(),
                'email_verified_at' => now(),
            ]);
        } else {
            //si l'utisateur existe déjà, on met à jour son goo
            if (!$user->google_id) {
                $user->update(['google_id' => $googleUser->getId()]);
            }
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Connexion réussie avec Google',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'country' => $user->country,
                    'role' => $user->role,
                ],
                'token' => $token
            ]
        ]);
    }
}
