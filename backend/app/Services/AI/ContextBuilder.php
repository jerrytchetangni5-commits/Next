<?php

namespace App\Services\AI;

use App\Models\Scholarship;
use App\Models\User;

class ContextBuilder
{
    public function build(array $payload): array
    {
        $context ='';
        $scholarshipId = $payload['scholarship_id'] ?? null;
        $filters = $payload['filters'] ?? null;
        $history = $payload['conversation_history'] ?? null;
        $userId = $payload['user_id'] ?? null;

        if($userId) {
            $user = User::find($userId);
            if ($user) {
                $context .= "**Profile de l'utilisateur:**\n";
                $context .= "-Pays :" . ($user->country ?? 'Non renseigné') . "\n"; 
                $context .= "-Niveau d'étude :" . ($user->study_level ?? 'Non renseigné') . "\n";
                $context .= "-Domaine d'étude :" . ($user->study_domain ?? 'Non renseigné') . "\n";
                $context .= "-Moyenne :" . ($user->average ?? 'Non renseigné') . "\n\n";                
            } 
        }

        if ($scholarshipId) {
            $scholarship = Scholarship::find($scholarshipId);
            if ($scholarship) {
                $context .= "**Bourse consultée actuellement :**\n";
                $context .= json_encode($this->scholarshipContext($scholarship), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                $context .= "\n\n";
                //JSON_PRETTY_PRINT rend le JSON lisible pour l'IA pour une meilleur compréhension
                //JSON_UNESCAPED_UNICODE garde les accents pour ne pas les transformés en \u00e9
            }
        }

        if($filters && is_array($filters)){
            $context .= "**Recherche demandé par l'utilisateur :**\n";
            $context .= json_encode($filters, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";
            $results = this->searchScholarships($filters);

            if (!empty($results)){
                $context .= "**Bourses trouvées dans next :**\n";
                $context .= json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";
            }
        }

        if ($history && is_array($history)){
            $limitedHistory = array_slice($history, -config('gemini.max_history_length', 10));
            $context .= "**Historique de conversation :**\n";
            foreach($limitedHistory as $turn) {
                $role = $turn['role'] ?? 'unknown';
                $content = $turn['content'] ?? '';
                $context .= "-{$role} : {$content}\n"; 
            }
            $context .= "\n";
        }

        return [
            'context' => $context,
            'has_content' => !empty($context)
        ];
    }

    private function scholarshipContext(Scholarship $scholarship): array
    {
        return [
            'id' => $scholarship->id,
            'title' => $scholarship->title,
            'country' => $scholarship->country,
            'university' => $scholarship->university,
            'domain' => $scholarship->domain,
            'level' => $scholarship->level,
            'deadline' => $scholarship->deadline,
            'funding_type' => $scholarship->funding_type,
            'amount' => $scholarship->amount,
            'currency' => $scholarship->currency,
            'benefits' => $scholarship->benefits,
            'requirements' => $scholarship->requirements
        ];
    }

    private function searchScholarships(array $filters): array
    {
        $query = Scholarship::query();
        if (!empty($filters['country'])){
            $query->where('country', 'LIKE', '%' . $filters['country'] . '%');
        }

        if (!empty($filters['domain'])){
            $query->where('domain', 'LIKE', '%' . $filters['domain'] . '%');
        }

        if (!empty($filters['level'])){
            $query->where('level', 'LIKE', '%' . $filters['level'] . '%');
        }

        return $query->limit(5)->get()->map(function ($s){
            return $this->scholarshipContext($s);
        })->toArray();
    }
}