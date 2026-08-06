<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Scholarship;

class ScholarshipController extends Controller
{
    //Landing page (la liste des bourses)
    public function index()
    {
        $scholarships = Scholarship::where('deadline', '>=', now())->orderBy('deadline')->get();
        return response()->json([
            'success' => true,
            'data' => $scholarships
        ]);
    }

    //Detail d'une bourse
    public function show($id)
    {
        $scholarship = Scholarship::find($id);
        if(!$scholarship){
            return response()->json([
                'success' => false,
                'message' => 'Scholarship not found'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $scholarship
        ]);
    }

    // Recherche
    public function search(Request $request)
    {
        $query = Scholarship::query();
        if($request->filled('country')){
            $query->where('country', 'LIKE', '%' . $request->country . '%');
            $query->where('deadline', '>=', now());
        }
        if($request->filled('domain')){
            $query->where('domain', 'LIKE', '%' . $request->domain . '%');
            $query->where('deadline', '>=', now());
        }
        if($request->filled('level')){
            $query->where('level', 'LIKE', '%' . $request->level . '%');
            $query->where('deadline', '>=', now());
        }

        $query->where('deadline', '>=', now());
        $results  = $query->orderBy('deadline', 'asc')->get();
        return response()->json([
            'success' => true,
            'data' => $results
        ]);
    }

    public function countries()
    {
        $counts = Scholarship::where('deadline', '>=', now())
            ->select('country', \DB::raw('count(*) as total'))
            ->groupBy('country')
            ->orderBy('total', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $counts
        ]);
    }

    public function byCountry($country)
    {
        $scholarships = Scholarship::where('country', $country)
            ->where('deadline', '>=', now())
            ->orderBy('deadline', 'asc')
            ->get();

        if (!$scholarships->isEmpty()){
            return response()->json([
                'success' => true,
                'data' => $scholarships
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Aucune bourse trouvée pour ce pays'
        ], 404);
    }

    public function statistics(){
        $today = now();
        $totalScholarships = Scholarship::where('deadline', '>=', $today)->count();
        $totalCountries = Scholarship::where('deadline', '>=', $today)->distinct('country')->count('country');
        $totalUniversities = Scholarship::where('deadline', '>=', $today)->whereNotNull('university')->distinct('university')->count('university');

        return response()->json([
            'success' =>true,
            'data' => [
                'scholarships' => $totalScholarships,
                'countries' => $totalCountries,
                'universities' => $totalUniversities
            ]
        ]);
    }
}
