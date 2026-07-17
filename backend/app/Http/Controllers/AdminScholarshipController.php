<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Scholarship;
use Carbon\Carbon;

class AdminScholarshipController extends Controller
{
    // récuperer toutes les bourses
    public function index()
    {
        $scholarships = Scholarship::orderBy('id', 'desc')->get();
        return response()->json([
            'success' => true,
            'data' => $scholarships
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'university' => 'required|string|max:50',
            'domain' => 'required|string|max:50',
            'deadline' => 'required|date',
            'description' => 'required|string',
            'funding_type' => 'required|in:full,partial,unfunded',
            'amount' => 'nullable|string|max:20',
            'benefits' => 'nullable|string',
            'requirement' => 'nullable|string',
            'image' => 'nullable|url',
            'link' => 'required|url',
            'source' => 'nullable|string|max:100'
        ]);
        $days_remaining = Carbon::now()->diffInDays($request->deadline, false);

        //créer la bourse
        $scholarship = Scholarship::create([
            'title' => $request->title,
            'country' => $request->country,
            'university' => $request->university,
            'domain' => $request->domain,
            'deadline' => $request->deadline,
            'description' => $request->description,
            'funding_type' => $request->funding_type,
            'amount' => $request->amount,
            'benefits' => $request->beneficts,
            'days_remaining' => $days_remaining,
            'requirement' => $request->requirement,
            'image' => $request->image,
            'link' => $request->link,
            'source' => $request->source
        ]);

        //retourner succès
        return response()->json([
            'success' => true,
            'message' => 'Bourse ajouté avec succès',
            'data' => $scholarship
        ], 201);
    }

    public function show($id)
    {
        $scholarship = Scholarship::find($id);
        if(!$scholarship){
            return response()->json([
                'success' => true,
                'message' => 'Bourse introuvable'
            ]);
        }
        return response()->json([
            'success' => true,
            'date' => $scholarship
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $scholarship = Scholarship::find($id);

        if(!$scholarship){
            return response()->json([
                'success' => false,
                'message' => 'Bourse introuvable'
            ], 404);
        }

        $request->validate([
            'title' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'university' => 'required|string|max:50',
            'domain' => 'required|string|max:50',
            'deadline' => 'required|date',
            'description' => 'required|string',
            'funding_type' => 'required|in:full,partial,unfunded',
            'amount' => 'nullable|string|max:20',
            'benefits' => 'nullable|string',
            'requirement' => 'nullable|string',
            'image' => 'nullable|url',
            'link' => 'required|url',
            'source' => 'nullable|string|max:100'
        ]);
        $days_remaining = Carbon::now()->diffInDays($request->deadline, false);

        // Mis à jour des informations de la bourse
        $scholarship->update([
            'title' => $request->title,
            'country' => $request->country,
            'region' => $request->region,
            'university' => $request->university,
            'domain' => $request->domain,
            'deadline' => $request->deadline,
            'description' => $request->description,
            'funding_type' => $request->funding_type,
            'amount' => $request->amount,
            'benefits' => $request->beneficts,
            'days_remaining' => $days_remaining,
            'requirement' => $request->requirement,
            'image' => $request->image,
            'link' => $request->link,
            'source' => $request->source
        ]);

        //retourner succès
        return response()->json([
            'success' => true,
            'message' => 'Bourse mis à jour',
            'data' => $scholarship
        ], 200);
    }

    public function destroy($id)
    {
        $scholarship = Scholarship::find($id);
        if(!$scholarship){
            return response()->json([
                'success' => false,
                'message' => 'Bourse introuvable'
            ], 404);
        }

        $scholarship->delete();
        return response()->json([
            'success' => true,
            'message' => 'Bourse supprimé avec succès'
        ], 200);
    }
}
