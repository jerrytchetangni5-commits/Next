<?php

namespace App\Http\Controllers;

use App\Models\Scholarship;
use App\Services\NextScoreService;

class UserRecommendationController extends Controller
{
    protected NextScoreService $scoreService;

    public function __construct(NextScoreService $scoreService)
    {
        $this->scoreService = $scoreService;
    }

    public function index()
    {
        $user = auth()->user();

        // On récupère uniquement les bourses encore disponibles
        $scholarships = Scholarship::query()
            ->where(function ($query) {
                $query->whereNull('deadline')
                    ->orWhereDate('deadline', '>=', now());
            })
            ->whereNotIn('id', function ($query) use ($user) {
                $query->select('scholarship_id')
                    ->from('favorites')
                    ->where('user_id', $user->id);
            })
            ->get();

        // Calcul du score pour chaque bourse
        $recommendations = $scholarships
            ->map(function ($scholarship) use ($user) {

                $score = $this->scoreService->calculateScore($user, $scholarship);

                return [
                    'id' => $scholarship->id,
                    'title' => $scholarship->title,
                    'country' => $scholarship->country,
                    'university' => $scholarship->university,
                    'domain' => $scholarship->domain,
                    'level' => $scholarship->level,
                    'funding_type' => $scholarship->funding_type,
                    'deadline' => $scholarship->deadline,
                    'image' => $scholarship->image,
                    'compatibility_score' => $score,
                ];
            })

            // On ne garde que les bourses ayant un minimum de pertinence
            ->filter(fn ($item) => $item['compatibility_score'] > 0)

            // Tri décroissant
            ->sortByDesc('compatibility_score')

            // Top 10
            ->take(10)

            ->values();

        if ($recommendations->isEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'Aucune recommandation trouvée. Complétez davantage votre profil.',
                'count' => 0,
                'data' => [],
            ]);
        }

        return response()->json([
            'success' => true,
            'count' => $recommendations->count(),
            'data' => $recommendations,
        ]);
    }
}