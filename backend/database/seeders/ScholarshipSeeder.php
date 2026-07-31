<?php

namespace Database\Seeders;

use App\Models\Scholarship;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ScholarshipSeeder extends Seeder
{
    /**
     * Stocke les dates invalides rencontrées pour éviter de spammer la console.
     */
    private array $invalidDates = [];

    public function run(): void
    {
        // 1. Trouver le fichier JSON (chemin configurable via .env)
        $path = env('SCHOLARSHIPS_JSON_PATH') ?: base_path('scraper/storage/scholarships.json');

        if (!File::exists($path)) {
            $this->command->error("Fichier introuvable : {$path}");
            return;
        }

        $this->command->info("Lecture du fichier JSON...");
        $json = File::get($path);
        $scholarships = json_decode($json, true);

        // 2. Vérification stricte de la validité du JSON
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->command->error('JSON invalide : ' . json_last_error_msg());
            return;
        }

        if (empty($scholarships)) {
            $this->command->warn('Le fichier est vide.');
            return;
        }

        $now = now();

        // 3. Nettoyage et formatage des données
        $data = collect($scholarships)
            ->filter(fn ($item) => !empty($item['link'])) // On ignore les entrées sans lien
            ->map(function ($item) use ($now) {
                return [
                    'title'              => $item['title'] ?? null,
                    'country'            => $item['country'] ?? null,
                    'university'         => $item['university'] ?? null,
                    'domain'             => $item['domain'] ?? null,
                    'level'              => $item['level'] ?? null,
                    'deadline'           => $this->nettoyerLaDate($item['deadline'] ?? null),
                    'description'        => $item['description'] ?? null,
                    'details'            => $item['details'] ?? null,
                    'funding_type'       => $item['funding_type'] ?? null,
                    'benefits'           => $item['benefits'] ?? null,
                    'requirements'       => $item['requirements'] ?? null,
                    'required_documents' => $item['required_documents'] ?? null,
                    'image'              => $item['image'] ?? null,
                    'apply_link'         => $item['apply_link'] ?? null,
                    'official_website'   => $item['official_website'] ?? null,
                    'link'               => $item['link'],
                    'source'             => $item['source'] ?? 'ScholyHub',
                    'created_at'         => $now,
                    'updated_at'         => $now,
                ];
            })
            ->values()
            ->toArray();

        $this->command->info("Sauvegarde de " . count($data) . " bourses...");

        // 4. Insertion par lots (Chunking) + Upsert
        foreach (array_chunk($data, 100) as $chunk) {
            Scholarship::upsert(
                $chunk,
                ['link'], // Clé unique
                [
                    'title', 'country', 'university', 'domain', 'level',
                    'deadline', 'description', 'details', 'funding_type',
                    'benefits', 'requirements', 'required_documents',
                    'image', 'apply_link', 'official_website', 'source', 'updated_at'
                ]
            );
        }

        // 5. Rapport final sur les dates ignorées
        if (!empty($this->invalidDates)) {
            $this->command->warn('Dates ignorées : ' . count($this->invalidDates));
        }

        $this->command->info(" " . count($data) . " bourses importées avec succès.");
    }

    /**
     * Nettoie et formate la date.
     * Retourne une date au format Y-m-d ou null.
     */
    private function nettoyerLaDate(?string $date): ?string
    {
        if (!$date) {
            return null;
        }

        $date = trim($date);

        // Ignorer les valeurs non-dates courantes
        if (preg_match('/^(rolling|open|ongoing|tbd|varies|not specified)$/i', $date)) {
            return null;
        }

        try {
            return Carbon::parse($date)->format('Y-m-d');
        } catch (\Throwable $e) {
            // On stocke la date invalide pour le rapport final au lieu de spammer
            $this->invalidDates[$date] = true;
            return null;
        }
    }
}