<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Scholarship;
use App\Services\NextScoreService;
class UserRecommendationController extends Controller
{
    protected $scoreService; //propriété pour protéger le service

    public function __construct(NextScoreService $scoreService) //injection de NextScoreService  via le constructeur
    {
        $this->scoreService = $scoreService;
    }

    public function index()
    {
        dd('JE PASSE ICI');
        $user = auth()->user();

        //verifie quels critères du profil sont renseignés
        $hasDomain = !empty($user->study_domain);
        $hasLevel = !empty($user->study_level);
        $hasCountries = !empty($user->destination_countries);

        //verifie si le profile est suffissament complet
        if (!$hasDomain && !$hasLevel && !$hasCountries) {
            return response()->json([
                'success' => true,
                'message' => 'Veillez compléter votre profil pour obtenir des recommendations.',
                'data' => [],
                'count' => 0
            ]);
        }

        $query = Scholarship::query(); //Permet de construire la requete progressivement avec des conditions dynamique

        // if ($hasDomain) {
        //     $query->where('domain', 'LIKE', '%' . $user->study_domain . '%');
        // }

        // if ($hasLevel) {
        //     $query->where('level', 'LIKE', '%' . $user->study_level . '%');
        // }

        // if ($hasCountries) { //evite une erreur si le champ est vide ou mal formé
        //     $query->where('country', 'LIKE', '%' . $user->destination_countries . '%');
        // } //whereIn filtre les bourses dont le pays est dans la liste(J'ai modifier ici but i don't want to remove this comment)

        $query->where('deadline', '>=', now()); //bourse nn expiré

        $query->whereNotIn('id', function ($j) use ($user) {
            $j->select('scholarship_id')
                ->from('favorites')
                ->where('user_id', $user->id);   //exclure les bourses favorites de l'utilisateur
        });

        // SCORE DE COMPATIBILITE

        $recommendations = $query->get() //get() exécute la requete et retourne une collection de bourses
            ->map(function ($scholarship) use ($user) { // map() transforme chaque bourse en tableau avec les données formatées

                $score = $this->scoreService->calculateScore($user, $scholarship); //On fait un appel au servise pour calculer le score

                return [
                    'id' => $scholarship->id,
                    'title' => $scholarship->title,
                    'domain' => $scholarship->domain,
                    'level' => $scholarship->level,
                    'country' => $scholarship->country,
                    'university' => $scholarship->university,
                    'deadline' => $scholarship->deadline,
                    'image' => $scholarship->image,
                    'compatibility_score' => $score
                ];
            })

            ->sortByDesc('compatibility_score') // trie par order décroissant
            ->take(20) //limite à 20 results
            ->values(); //réindex le tableau

        if ($recommendations->isEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'Aucune recommendation ne correspond à votre profil pour le moment.',
                'data' => [],
                'count' => 0
            ]);
        }

        return response()->json([
            'success' => true,
            'count' => $recommendations->count(),
            'data' => $recommendations
        ]);
    }
}
