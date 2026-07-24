<?php

namespace App\Services;

use App\Models\Scholarship;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

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
            throw new \Exception("Fichier introuvable: $path");
        }

        $json = File::get($path);
        $data = json_decode($json,true);

        if (json_last_error()!== JSON_ERROR_NONE){
            throw new \Exception('JSON invalide: ' . json_last_error_msg());
        }

        if(empty($data)){
            return $stats;
        }

        $stats['total'] = count($data);

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

                $model = Scholarship::updateOrCreate(
                    ['link' => $prepared['link']],
                    $prepared
                );

                if($model->wasRecentlyCreated){
                    $stats['created']++;
                } else {
                    $stats['updated']++;
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

        if (preg_match('/^([A-Za-z]+)\s+(\d{1,2}),?\s+(\d{4})$/', $date, $m)){
            return Carbon::parse($m[3] . '-' . $m[1] . '-' . str_pad($m[2], 2, '0', STR_PAD_LEFT));
        }

        if (preg_match('/^(\d{1,2})\s+([A-Za-z]+)\s+(\d{4})$/', $date, $m)) {
            return Carbon::parse($m[3] . '-' . $m[2] . '-' . str_pad($m[1], 2, '0', STR_PAD_LEFT));
        }

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
        return [
            'title' => $data['title'] ?? null,
            'country' => $data['country'] ?? null,
            'university' => $data['university'] ?? null,
            'domain' => $data['domain'] ?? null,
            'level' => $data['level'] ?? null,
            'deadline' => $this->parseDate($data['deadline'] ?? '')?->toDateString(),
            'description' => $data['description'] ?? null,
            'funding_type' => $this->normalizeFundingType($data['funding_type'] ?? null),
            'benefits' => $data['benefits'] ?? null,
            'requirements' => $data['requirements'] ?? null,
            'required_documents' => $data['required_documents'] ?? null,
            'image' => $data['image'] ?? null,
            'link' => $data['link'] ?? null,
            'source' => $data['source'] ?? 'ScholyHub',
        ];
    }

    private function normalizeFundingType(?string $type): ?string
    {
        if (!$type) {
            return null;
        }

        $type = strtolower(trim($type));

        if(str_contains($type, 'fully funded') || str_contains($type, 'full')){
            return 'full';
        }

        if(str_contains($type, 'partially funded') || str_contains($type, 'partial') || str_contains($type, 'tuition')){
            return 'partial';
        }

        if(str_contains($type, 'unfunded')){
            return 'unfunded';
        }

        return null;
    }
}