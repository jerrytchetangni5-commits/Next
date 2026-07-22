<?php

namespace App\Services;

use App\Models\Scholarship;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class ScholarshipImporter
{
    public function import (string $path): array
    {
        $stats = [
            'total' => 0,
            'created' => 0,
            'updated' => 0,
            'expired' => 0,
            'errors' => 0
        ];

        if (!File::exists($path)) {
            throw new \Exception("Fichier introuvable: $path"); // si le fichier n'existe pas on lance une exception
        }

        $json = File::get($path);
        $data = json_decode($json,true);

        //vérification de la validité du json
        if (json_last_error() !== JSON_ERROR_NONE){
            throw new \Exception('JSON invalide: ' . json_last_error_msg());
        }

        if(empty($data)){
            return $stats;
        }

        $stats['total'] = count($data);

        //parcourt chaque bourse
        foreach ($data as $item){
            try{
                if (empty($item['link'])){
                    $stats['errors']++;
                    continue;
                }

                if($this->isExpired($item)){
                    $stats['expired']++;
                    continue;
                }

                $prepared = $this->prepareData($item);

                if(empty($prepared['title'])){
                    $stats['errors']++;
                    continue;
                }

                $model = Scholarship::updateOrCreate(
                    ['link' => $prepared['link']],
                    $prepared
                );

                if($model->wasRecentlyCreated){
                    $stats['created']++;
                } else {
                    $stats['updated']++;
                }

                if (config('locales.translate_enabled', false)){
                    \App\Jobs\TranslateScholarshipJob::dispatch($model);
                }

            } catch(\Exception $e) {
                $stats['errors']++;
                \Log::error('Erreur import bourse', [
                    'title' => $item['title'] ?? 'Inconnu',
                    'link' => $item['link'] ?? 'Inconnu',
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }

        Log::channel('import')->info('Import terminé', $stats);
        return $stats;
    }

    private function isExpired(array $item): bool
    {
        $deadline = $item['deadline'] ?? null;

        if(empty($deadline)){
            return false;
        }

        $special = ['rolling', 'open', 'ongoing', 'all year', 'varies', 'not specified'];
        if(in_array(strtolower(trim($deadline)), $special)){
            return false;
        }

        $parsed = $this->parseDate($deadline);

        if (!$parsed){
            return false;
        }

        return $parsed->isPast();
    }

    private function parseDate(?string $date): ?Carbon
    {
        if (empty($date)){
            return null;
        }

        $date = trim($date);

        //December 1, 2026
        if (preg_match('/^([A-Za-z]+)\s+(\d{1,2}),?\s+(\d{4})$/', $date, $m)){
            return Carbon::parse($m[3] . '-' . $m[1] . '-' . str_pad($m[2], 2, '0', STR_PAD_LEFT));
        }

        //1 December 2026
        if (preg_match('/^(\d{1,2})\s+([A-Za-z]+)\s+(\d{4})$/', $date, $m)) {
            return Carbon::parse($m[3] . '-' . $m[2] . '-' . str_pad($m[1], 2, '0', STR_PAD_LEFT));
        }

        // December 2026
        if (preg_match('/^([A-Za-z]+)\s+(\d{4})$/', $date, $m)) {
            return Carbon::parse($m[2] . '-' . $m[1] . '-01')->endOfMonth();
        }

        try{
            return Carbon::parse($date);
        } catch (\Exception $e){
            return null;
        }
    }

    private function prepareData(array $data): array
    {
        //nettoie le texte
        $normalized = $this->normalizeFields($data);

        //normalisations des champs
        $normalized['country'] = $this->normalizeCountry($normalized['country'] ?? null);

        $normalized['funding_type'] = $this->normalizeFundingType($normalized['funding_type'] ?? null);

        $normalized['image'] = $this->validateImage($normalized['image'] ?? null);

        $normalized['deadline'] = $this->parseDate($normalized['deadline'] ?? '')?->toDateString();

        $normalized['apply_link'] = $this->validateUrl($normalized['apply_link'] ?? null);

        $normalized['official_website'] = $this->validateUrl($normalized['official_website'] ?? null);

        return $normalized;
        return['is_translated' => false];
    }

    private function normalizeFields(array $data): array
    {
        $fields = [
            'title',
            'country',
            'university',
            'domain',
            'level',
            'description',
            'details',
            'benefits',
            'requirements',
            'required_documents',
            'funding_type'
        ];

        foreach ($fields as $field) {
            if (!empty($data[$field])) {
                $text = $data[$field];
                // Supprimer les balises HTML
                $text = strip_tags($text);
                // Remplacer les espaces multiples par un seul espace
                $text = preg_replace('/\s+/', ' ', $text);
                // Mettre la première lettre en majuscule (sauf pour les descriptions qui restent en minuscules)
                if (!in_array($field, ['description', 'details', 'benefits', 'requirements', 'required_documents'])) {
                    $text = Str::title($text);
                }
                $data[$field] = trim($text);
            } else {
                $data[$field] = null;
            }
        }

        $urlFields = ['image', 'link', 'apply_link', 'official_website', 'source'];
        foreach ($urlFields as $field) {
            if (isset($data[$field]) && is_string($data[$field])) {
                $data[$field] = trim($data[$field]) ?: null;
            } else {
                $data[$field] = null;
            }
        }

        return $data;
    }

    // Normalise le nom d'un pays à l'aide d'un dictionnaire.
    private function normalizeCountry(?string $text): ?string
    {
        if (empty($text)) {
            return null;
        }

        // Récupère le dictionnaire depuis config/countries.php
        $map = config('countries', []);

        // Recherche exacte (insensible à la casse)
        $key = strtolower(trim($text));
        if (isset($map[$key])) {
            return $map[$key];
        }

        // Si le pays n'est pas dans le dictionnaire, on le normalise simplement
        return Str::title(trim($text));
    }

    //Normalise le type de financement à l'aide d'un dictionnaire.Retourne "full", "partial", "unfunded" ou null.
    private function normalizeFundingType(?string $type): ?string
    {
        if (empty($type)) {
            return null;
        }

        $type = strtolower(trim($type));

        // Récupère les dictionnaires depuis config/funding.php
        $fullKeywords = config('funding.full', []);
        $partialKeywords = config('funding.partial', []);
        $unfundedKeywords = config('funding.unfunded', []);

        foreach ($fullKeywords as $keyword) {
            if (str_contains($type, $keyword)) {
                return 'full';
            }
        }

        foreach ($partialKeywords as $keyword) {
            if (str_contains($type, $keyword)) {
                return 'partial';
            }
        }

        foreach ($unfundedKeywords as $keyword) {
            if (str_contains($type, $keyword)) {
                return 'unfunded';
            }
        }

        return null;
    }

    //Valide l'URL de l'image
    private function validateImage(?string $url): ?string
    {
        return empty($url) ? null : (filter_var($url, FILTER_VALIDATE_URL) ? $url : null);
    }

    private function validateUrl(?string $url): ?string
    {
        return empty($url) ? null : (filter_var($url, FILTER_VALIDATE_URL) ? $url : null);
    }

    //Supprime les bourses expirées de la base
    public function removeExpiredScholarships(): int
    {
        return Scholarship::whereNotNull('deadline')
            ->whereDate('deadline', '<', Carbon::today())
            ->delete();
    }
}