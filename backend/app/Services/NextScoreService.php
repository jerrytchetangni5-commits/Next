<?php
namespace App\Services;
use App\Models\User;
use App\Models\Scholarship;

class NextScoreService
{
    private const WEIGHTS = [ //WEIGHTS définit l'importance relative de chaque critère
        'domain' => 45,
        'level' => 15,
        'languages' => 20,
        'average' => 10,
        'english' => 10,
    ];

    public function calculateScore(User $user, Scholarship $scholarship): int
    {
        $score = 0;
        $score += $this->scoreDomain($user, $scholarship);
        $score += $this->scoreLevel($user, $scholarship);
        $score += $this->scoreLanguages($user, $scholarship);
        $score += $this->scoreAverage($user, $scholarship);
        $score += $this->scoreEnglish($user, $scholarship);  
        //$this->score... appel la méthode qui calcule ce score et l'ajoute au score totale
        return min(100, (int) round($score)); 
        //round arrondit à l'entier le plus proche puis int force la conversion en entier
    }

    public function getScoreDetails(User $user, Scholarship $scholarship): array
    {
        return[
            'total_score' => $this->calculateScore($user, $scholarship),
            'details' => [
                'domain' => $this->scoreDomain($user, $scholarship),
                'level' => $this->scoreLevel($user, $scholarship),
                'languages' => $this->scoreLanguages($user, $scholarship),
                'average' => $this->scoreAverage($user, $scholarship),
                'english' => $this->scoreEnglish($user, $scholarship),            
            ]
        ];
        //retourne le score total et les détails à chaque niveau sous un format tableau
    }

    private function scoreDomain(User $user, Scholarship $scholarship): int
    {
        if(!$user->study_domain || !$scholarship->domain){
            return 0;
        }

        $userDomain = strtolower(trim($user->study_domain));
        $scholarshipDomain = strtolower(trim($scholarship->domain));

        if($userDomain === $scholarshipDomain){
            return self::WEIGHTS['domain'];
        }

        if(str_contains($userDomain, $scholarshipDomain) || str_contains($scholarshipDomain, $userDomain)){
            return (int) (self::WEIGHTS['domain'] / 2);
        }
        //str_contains:fonction php vérifie si une chaine contient une sous chaine

        return 0;
    }

    private function scoreLevel(User $user, Scholarship $scholarship): int
    {
        if(!$user->study_level || !$scholarship->requirements){
            return 0;
        }

        $userLevel = strtolower($user->study_level);
        $requirements = strtolower($scholarship->requirements);

        if(str_contains($requirements, $userLevel)){
            return self::WEIGHTS['level'];
        }
        //on vérifie si le niveau de l'utilisateur est mentionné dans le texte(requirements) pas vraiment reco mmandé 
        return 0;
    }

    private function scoreLanguages(User $user, Scholarship $scholarship): int
    {
        if ((!$user->languages || !$scholarship->languages) || (!$user->languages && !$scholarship->languages)){
            return 0;
        }

        $userLanguages = is_array($user->languages)
            ? $user->languages
            : json_decode($user->languages, true);

        $requiredLanguages = is_array($scholarship->languages)
            ? $scholarship->languages
            : json_decode($scholarship->languages, true) ?? [];

        if(!$userLanguages || !$requiredLanguages){
            return 0;
        }

        $userLanguages = array_map('strtolower', $userLanguages);
        $requiredLanguages = array_map('strtolower', $requiredLanguages);

        //array_map('strtolower) retourne tt en miniscule pour la comparaison

        $matches = count(array_intersect($userLanguages, $requiredLanguages));
        //array_intersect retourne l'intersetion des valeurs communes aux deux tableaux

        if($matches === 0){
            return 0;
        }

        return (int) round(($matches / count($requiredLanguages)) * self::WEIGHTS['languages']);
    }

    private function scoreAverage(User $user, Scholarship $scholarship): int
    {
        if(!$user->average){
            return 0;
        }
        
        if(!$scholarship->min_average){
            return self::WEIGHTS['average'];
        }

        if($user->average >= $scholarship->min_average){
            return self::WEIGHTS['average'];
        }

        if($user->average >= ($scholarship->min_average - 1)){
            return (int) (self::WEIGHTS['average'] / 2);
        }
        
        return 0;
    }

    private function scoreEnglish(User $user, Scholarship $scholarship): int
    {
        if(!$user->english_level || !$scholarship->required_english_level){
            return 0;
        }

        $levels = [
            'A1' => 1,
            'A2' => 2,
            'B1' => 3,
            'B2' => 4,
            'C1' => 5,
            'C2' => 6,
        ];

        $userLevel = strtoupper($user->english_level);
        $requiredLevel = strtoupper($scholarship->required_english_level);

        if(!isset($levels[$userLevel]) || !isset($levels[$requiredLevel])){
            return 0;
        }

        // Full score
        if($levels[$userLevel] >= $levels[$requiredLevel]) {
            return self::WEIGHTS['english'];
        }

        //Partial
        if($levels[$userLevel] === $levels[$requiredLevel] - 1){
            return (int) (self::WEIGHTS['english'] / 2);
        }

        return 0;
    }
}