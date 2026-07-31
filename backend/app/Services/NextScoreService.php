<?php

namespace App\Services;

use App\Models\User;
use App\Models\Scholarship;

class NextScoreService
{
    private const WEIGHTS = [ //WEIGHTS définit l'importance relative de chaque critère
        'domain' => 50,
        'level' => 25,
        'country' => 25,
    ];

    public function calculateScore(User $user, Scholarship $scholarship): int
    {
        $score = 0;

        $score += $this->scoreDomain($user, $scholarship);
        $score += $this->scoreLevel($user, $scholarship);
        $score += $this->scoreCountry($user, $scholarship);

        //$this->score... appel la méthode qui calcule ce score et l'ajoute au score totale

        return min(100, (int) round($score));

        //round arrondit à l'entier le plus proche puis int force la conversion en entier
    }

    public function getScoreDetails(User $user, Scholarship $scholarship): array
    {
        return [
            'total_score' => $this->calculateScore($user, $scholarship),
            'details' => [
                'domain' => $this->scoreDomain($user, $scholarship),
                'level' => $this->scoreLevel($user, $scholarship),
                'country' => $this->scoreCountry($user, $scholarship),
            ]
        ];

        //retourne le score total et les détails à chaque niveau sous un format tableau
    }

    private function scoreDomain(User $user, Scholarship $scholarship): int
    {
        if (!$user->study_domain || !$scholarship->domain) {
            return 0;
        }

        $userDomain = strtolower(trim($user->study_domain));
        $scholarshipDomain = strtolower(trim($scholarship->domain));

        if ($userDomain === $scholarshipDomain) {
            return self::WEIGHTS['domain'];
        }

        if (
            str_contains($userDomain, $scholarshipDomain) ||
            str_contains($scholarshipDomain, $userDomain)
        ) {
            return (int) (self::WEIGHTS['domain'] / 2);
        }

        //str_contains:fonction php vérifie si une chaine contient une sous chaine

        return 0;
    }

    private function scoreLevel(User $user, Scholarship $scholarship): int
    {
        if (!$user->study_level || !$scholarship->level) {
            return 0;
        }

        $userLevel = strtolower(trim($user->study_level));
        $scholarshipLevel = strtolower(trim($scholarship->level));

        if ($userLevel === $scholarshipLevel) {
            return self::WEIGHTS['level'];
        }

        if (
            str_contains($scholarshipLevel, $userLevel) ||
            str_contains($userLevel, $scholarshipLevel)
        ) {
            return (int) (self::WEIGHTS['level'] / 2);
        }

        //str_contains:fonction php vérifie si une chaine contient une sous chaine

        return 0;
    }

    private function scoreCountry(User $user, Scholarship $scholarship): int
    {
        if (
            (!$user->destination_countries || !$scholarship->country) ||
            (!$user->destination_countries && !$scholarship->country)
        ) {
            return 0;
        }

        $countries = is_array($user->destination_countries)
            ? $user->destination_countries
            : json_decode($user->destination_countries, true);

        if (!$countries) {
            return 0;
        }

        //array_map('strtolower') retourne tout en minuscule pour la comparaison

        $countries = array_map(fn($country) => strtolower(trim($country)), $countries);

        $scholarshipCountry = strtolower(trim($scholarship->country));

        if (in_array($scholarshipCountry, $countries)) {
            return self::WEIGHTS['country'];
        }

        return 0;
    }
}