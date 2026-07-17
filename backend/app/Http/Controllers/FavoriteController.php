<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use App\Models\Scholarship;

class FavoriteController extends Controller
{
    public function index()
    {
        $favorites = Favorite::with('scholarship')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();
        return response()->json([
            'success' => true,
            'data' => $favorites
        ]);
    }

    public function toggle($scholarshipId)
    {
        $user = auth()->user();
        $scholarship = Scholarship::find($scholarshipId);
        if(!$scholarship){
            return response()->json([
                'success' => true,
                'message' => 'Bourse introuvable',
            ], 404);
        }
        $favorite = Favorite::where('user_id', $user->id)
            ->where('scholarship_id', $scholarshipId)
            ->first();
        if($favorite){
            $favorite->delete();
            return response()->json([
                'success' => true,
                'favorite' => false,
                'message' => 'Bourse retiré des favoris' 
            ]);
        }
        Favorite::create([
            'user_id' => $user->id,
            'scholarship_id' => $scholarshipId
        ]);
        return response()->json([
            'success' => true,
            'favorite' => true,
            'message' => 'Bourse ajoutée aux favoris'
        ]);
    }
}
