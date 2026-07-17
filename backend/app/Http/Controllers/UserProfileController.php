<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return response()->json([
            'success' => true,
            'message' => 'Profile affiché',
            'data' => $user,
            'completion_percentage' => $this->calculateProfileCompletion($user)
        ]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'first_name' => 'string|max:100',
            'last_name' => 'string|max:100',
            'email' => 'email|unique:users,email,' .$user->id,
            'country' => 'string|max:100',        
        ]);

        $user->update($request->only(['first_name', 'last_name', 'email', 'country']));
        $user->refresh();  

        return response()->json([
            'success' => true,
            'message' => 'Profil mis à jour',
            'data' => $user
        ]);
    }

    public function updateRecommendation(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'nationality' => 'nullable|string|max:100',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|string|in:Homme,Femme',
            'study_level' => 'nullable|string',
            'study_domain' => 'nullable|string',
            'average' => 'nullable|numeric|min:0',
            'languages' => 'nullable|array',
            'skills' => 'nullable|array'        
        ]);

        $user->update($validated);
        $user->refresh(); //Garantit qu'on a les données les plus récentes après la mise à jour

        return response()->json([
            'success' => true,
            'message' => 'Profil mis à jour',
            'data' => $user,
            'completion_percentage' => $this->calculateProfileCompletion($user)
        ]);
    }

    private function calculateProfileCompletion($user):int
    {
        $fields = [
            'nationality',
            'birth_date',
            'gender',
            'study_level',
            'study_domain',
            'average',
            'languages',
            'skills'
        ];

        $filled = 0;
        foreach($fields as $field){
            $value = $user->$field;
            if(!empty($value)){
                if(is_array($value)){
                    if(count($value) > 0){
                        $filled++;
                    }
                }
                elseif(!is_array($value)){
                    $filled++;
                }
            }
        }
        return(int) round(($filled / count($fields)) * 100);
    }
}
