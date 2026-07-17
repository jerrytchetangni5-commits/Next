<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    //Infos du profil
    public function profile(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'success' => true,
            'data' => $user
        ], 200);
    }

    //modifier info
    public function  updateProfile(Request $request)
    {
        //récupérer user connecté
        $user = $request->user();

        //valider les données reçu
        $user->validate([
            'first_name' => 'required|string|max:80',
            'last_name' => 'required|string|max:80',
            'email' => 'required|email|unique:users,email,' .$user->id,
            'country' => 'nullable|string'
        ]);

        //mettre à jour informations
        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'country' => $request->country
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Profil mis à jour',
            'data' => $user
        ], 200);
    }
}
