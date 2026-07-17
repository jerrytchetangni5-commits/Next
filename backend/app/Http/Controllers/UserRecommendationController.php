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
        $user = auth()->user();
        $query = Scholarship::query(); //Permet de construire la requete progressivement avec des conditions dynamique
        if($user->study_domain){
            $query->where('domain', 'LIKE', '%' . $user->study_domain . '%'); 
        }
        if($user->destination_countries && is_array($user->destination_countries)){ //evite une erreur si le champ est vide ou mal formé
            $query->whereIn('country', $user->destination_countries); 
        } //whereIn filtre les bourses dont le pays est dans la liste 

        $query->where('deadline', '>=', now()); //bourse nn expiré

        $query->whereNotIn('id', function($j)use($user){
            $j->select('scholarship_id')
            ->from('favorites')
            ->where('user_id', $user->id);   //exclure les bourses favorites de l'utilisateur
        });

        // SCORE DE COMPATIBILITE

        $recommendations = $query->get() //get() exécute la requete et retourne une collection de bourses
            ->map(function($scholarship)use($user){ // map() transforme chaque bourse en tableau avec les données formatées
                $score = $this->scoreService->calculateScore($user, $scholarship); //On fait un appel au servise pour calculer le score
                return[
                    'id' => $scholarship->id,                    
                    'title' => $scholarship->title,
                    'domain' => $scholarship->domain,
                    'country' => $scholarship->country,
                    'university' => $scholarship->university,
                    'deadline' => $scholarship->deadline,
                    'image' => $scholarship->image,
                    'compatibility_score' => $score            
                ];
            })
            
            ->sortByDesc('compatibility_score') // trie par order décroissant
            ->take(10) //limite à 10 results
            ->values(); //réindex le tableau

        if($recommendations->isEmpty()){
            return response()->json([
                'success' => true,
                'message' => 'Completez votre profil pour obtenir de meilleur recommendation',
                'data' => []
            ]);
        }

        return response()->json([
            'success' => true,
            'count' => $recommendations->count(),
            'data' => $recommendations
        ]);
    }
}
