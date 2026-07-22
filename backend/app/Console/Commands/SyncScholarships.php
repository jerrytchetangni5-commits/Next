<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;
use App\Services\ScholarshipImporter;
use App\Models\Scholarship;

class SyncScholarships extends Command
{

    protected $signature = 'scholarships:sync';
    protected $description = 'Scrape, ajout, import et nettoie les bourses (automatisation complète)';

    public function handle()
    {
        $this->info('Synchronisation complète des bourses en cours......');
        $start = now();

        $scraperPath = base_path('../scraper');

        // 1. Scraper les cartes
        $this->info('Scraping des cartes...');
        $result = Process::path($scraperPath)->run('npm run links');

        if (!$result->successful()) {
            $this->error('Scraping échoué : ' . $result->errorOutput());
            return 1;
        }
        $this->line($result->output());

        // 2. Ajout les données
        $this->info('Ajout des détails en cours......');
        $result = Process::path($scraperPath)->run('npm start');

        if (!$result->successful()) {
            $this->error('Enrichissement échoué : ' . $result->errorOutput());
            return 1;
        }
        $this->line($result->output());

        // 3. Importer les bourses (commande Artisan)
        $this->info('Import des bourses vers next...');
        $jsonPath = $scraperPath . '/storage/scholarships.json';
        if(!file_exists($jsonPath)){
            $this->error("Fichier scholarships.json introuvable : {$jsonPath}");
            return 1;
        }
        $importer = app(ScholarshipImporter::class);
        $stats = $importer->import($jsonPath);

        // Supprimer les bourses expirées (via le service)
        $this->info('Nettoyage des bourses expirées...');
        $deleted = $importer->removeExpiredScholarships();
        $duration = now()->diffInSeconds($start);

        $this->newLine();
        $this->info('Synchronisation terminée en ' . $duration . ' secondes.');
        $this->info('RÉSULTATS');
        $this->line(" Bourses lues       : {$stats['total']}");
        $this->line(" Nouvelles           : {$stats['created']}");
        $this->line(" Mises à jour       : {$stats['updated']}");
        $this->line(" Expirées ignorées  : {$stats['expired']}");
        $this->line(" Supprimées        : {$deleted}");
        $this->line(" Erreurs            : {$stats['errors']}");
        $this->newLine();

        if (config('locales.translate_enabled', false)){
            $this->info('Les traductions ont été mises en file automatiquement pendant l\'import.');
        }

        if ($stats['expired'] > 0 || $deleted > 0) {
            $this->warn('Les bourses expirées ont été automatiquement nettoyées.');
        }

        return 0;

        //Traduction en français par défaut
        $this->info('Traduction des données en cours');
        $translatedCount = 0;
        if (config('locales.translate_enabled', false)){
            $toTranslate = Scholarship::whereNull('title_fr')->get();
            foreach ($toTranslate as $scholarship){
                \App\Jobs\TranslateScholarshipJob::dispatch($scholarship);
                $translatedCount++;
            }
            $this->info(" {$translatedCount} bourse(s) mises en file d'attente pour la traduction");
        } else {
            $this->info('APP_TRANSLATE=false');
        }
    }
}
