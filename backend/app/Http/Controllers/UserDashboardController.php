<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use App\Models\Scholarship;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $newScholarships = Scholarship::where('created_at', '>=', now()->subDays(7))->count();   //Retourne toute les nouvelles bourses créer il t a moins de 7 jours

        $completion = $this->calculateProfileCompletion($user);

        $favoriteCount = Favorite::where('user_id', $user->id)->count();

        $recommendation = 0;

        return response()->json([
            'success' => true,
            'data' => [
                'newScholarships' => $newScholarships,
                'profile_completion' => $completion,
                'favorite' => $favoriteCount,
                'recommendation' => $recommendation
            ]
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
