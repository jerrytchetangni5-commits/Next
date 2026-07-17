<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    //Inscription
    public function register(Request $request)
    {
        //validation
        $request->validate([
            'first_name' => 'required|string|max:225',
            'last_name' => 'required|string|max:225',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'country' => 'nullable|string|max:255'
        ]);

        //créer user
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'country' => $request->country,
            'role' => 'student',
            'password' => Hash::make($request->password),
        ]);

        //Token
        $token = $user->createToken('auth_token')->plainTextToken;

        //Response
        return response()->json([
            'success' => true,
            'message' => 'Inscription réussie',
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
        ], 201);
    }

    //LOGIN
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        //Find user
        $user = User::where('email', $request->email)->first(); //first() retourne le premier resultat ou null si aucun trouvé

        // Check password
        if(!$user || !Hash::check($request->password, $user->password)){
            return response()->json([
                'success' => false,
                'message' => 'Email ou mot de passe incorrecte'
            ], 401);
        }

        //TOKEN
        $token = $user->createToken('auth_token')->plainTextToken;

        //RESPONSE
        return response()->json([
            'success' => true,
            'message' => 'Connexion réussie',
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

    public function me(Request $request)
    {
        $user = $request->user();
        if(!$user){
            return response()->json([
                'success' => false,
                'message' => 'Non authentifié'
            ], 401);
        }
        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }
    //LOGOUT
    public function logout(Request $request)
    {
        $user = $request->user();
        if($user){
            $user->tokens()->delete();
        }
        return response()->json([
            'success' => true,
            'message' => 'Déconnexion réussie'
        ]);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if($status === Password::RESET_LINK_SENT){
            return response()->json([
                'success' => true,
                'message' => 'Un lien de réinitialisation a été envoyé à votre adresse email.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => __($status),
        ], 400);
        
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);
         $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),

            function($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
         );

         if ($status === Password::PASSWORD_RESET){
            return response()->json([
                'success' => true,
                'message' => 'Mot de passe réinitialisé avec succès.'
            ]);
         }

         return response()->json([
            'success' => false,
            'message' => 'Token invalide ou expiré.'
         ]);
    }
}
