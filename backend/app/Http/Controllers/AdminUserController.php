<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserController extends Controller
{
    //Liste des users
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'country' => 'nullable|string|max:100',
            'role' => 'required|in:admin,student'
        ]);

        $user = User::create([
            'first_name' => $request->first_name,          
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'country' => $request->country,
            'role' => $request->role
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Utilisateur enregistrer avec succès',
            'data' => $user
        ], 201);
    }
    
    public function show($id)
    {
        $user = User::find($id);
        if(!$user){
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur introuvable'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $user
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if(!$user){
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur introuvable'
            ], 404);
        }

        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users,email,' .$user->id,
            'country' => 'nullable|string|max:15',
            'role' => 'required|in:admin,student'        
        ]);

        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'country' => $request->country,
            'role' => $request->role
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Utilisateur modifier',
            'data' => $user
        ], 200);
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if(!$user){
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur introuvable'
            ], 404);
        }

        //empecher un admin de delete son compte
        if (auth()->id() == $user->id){
            return response()->json([
                'success' => true,
                'message' => 'Vous ne pouvez pas supprimer votre compte'
            ], 403);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Utilisateur supprimé avec succès'
        ], 200);
    }
}
