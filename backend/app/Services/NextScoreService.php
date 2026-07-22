<?php

namespace App\Services;

use App\Models\User;
use App\Models\Scholarship;
use Carbon\Carbon;

class NextScoreService
{
    public function calculateScore(User $user, Scholarship $scholarship): int
    {
        $score = 0;

        $score += $this->domainScore($user, $scholarship);

        $score += $this->countryScore($user, $scholarship);

        $score += $this->deadlineScore($scholarship);

        return min(100, $score);
    }

    private function domainScore(User $user, Scholarship $scholarship): int
    {
        if (!$user->study_domain || !$scholarship->domain) {
            return 0;
        }

        $userDomain = strtolower(trim($user->study_domain));
        $scholarshipDomain = strtolower(trim($scholarship->domain));

        if ($userDomain === $scholarshipDomain) {
            return 60;
        }

        if (
            str_contains($scholarshipDomain, $userDomain) ||
            str_contains($userDomain, $scholarshipDomain)
        ) {
            return 30;
        }

        return 0;
    }

    private function countryScore(User $user, Scholarship $scholarship): int
    {
        if (
            empty($user->destination_countries) ||
            empty($scholarship->country)
        ) {
            return 0;
        }

        $countries = array_map(
            fn ($country) => strtolower(trim($country)),
            $user->destination_countries
        );

        if (in_array(strtolower($scholarship->country), $countries)) {
            return 30;
        }

        return 0;
    }

    private function deadlineScore(Scholarship $scholarship): int
    {
        if (!$scholarship->deadline) {
            return 0;
        }

        $days = Carbon::today()->diffInDays($scholarship->deadline, false);

        if ($days < 0) {
            return 0;
        }

        if ($days <= 30) {
            return 10;
        }

        if ($days <= 90) {
            return 5;
        }

        return 2;
    }
}