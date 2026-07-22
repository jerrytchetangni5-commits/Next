<?php

namespace App\Jobs;

use App\Models\Scholarship;
use App\Services\LibreTranslateService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TranslateScholarshipJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Scholarship $scholarship;

    public function __construct(Scholarship $scholarship)
    {
        $this->scholarship = $scholarship;
    }

    public function handle(LibreTranslateService $translator): void
    {
        //Déjà traduite ? On quitte
        if ($this->scholarship->is_translated) {
            return;
        }

        //Récupérer les champs à traduire (ceux qui sont encore en anglais)
        $fields = [
            'title' => $this->scholarship->title,
            'description' => $this->scholarship->description,
            'details' => $this->scholarship->details,
            'benefits' => $this->scholarship->benefits,
            'requirements' => $this->scholarship->requirements,
            'required_documents' => $this->scholarship->required_documents,
        ];

        //Traduire tout en une seule requête
        $translated = $translator->translateFields($fields);

        $hasTranslation = false;
        $updateData = [];

        foreach ($fields as $key => $originalValue) {
            if (!empty($originalValue)) {
                $translatedValue = $translated[$key] ?? null;
                // Si la traduction est différente et non vide, on l'utilise
                if ($translatedValue && $translatedValue !== $originalValue) {
                    $updateData[$key] = $translatedValue;
                    $hasTranslation = true;
                } else {
                    $updateData[$key] = $originalValue; // On garde l'original
                }
            }
        }

        //Mettre à jour la bourse avec les traductions
        $this->scholarship->update($updateData);

        if ($hasTranslation) {
            $this->scholarship->update(['is_translated' => true]);
            Log::info('Bourse traduite avec succès', ['id' => $this->scholarship->id]);
        } else {
            Log::warning('Aucune traduction effectuée pour la bourse', ['id' => $this->scholarship->id]);
        }

        Log::info('Bourse traduite avec succès', ['id' => $this->scholarship->id]);
    }
}