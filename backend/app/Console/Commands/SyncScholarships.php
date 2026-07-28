<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;
use App\Services\ScholarshipImporter;

class SyncScholarships extends Command
{

    protected $signature = 'scholarships:sync';
    protected $description = 'Scrape, enrich, import et nettoie les bourses (automatisation complète) au niveau des deux sites';

    public function handle()
    {
        $this->info('Synchronisation complète des bourses');
        $start = now();

        $scraperPath = base_path('../scraper');
        $importer = app(ScholarshipImporter::class);

        $globalStats = [
            'total' => 0,
            'created' => 0,
            'updated' => 0,
            'expired' => 0,
            'duplicates' => 0,
            'errors' => 0
        ];

        // 1. Scraper les sites
        $this->info('ScholyHub');
        $result1 = Process::path($scraperPath)->run('npm run scrape:hub');

        if ($result1->successful()) {
            $this->info('Import Scholyhub en cours...');
            $stats1 = $importer->import($scraperPath . '/storage/scholarships.json');
            $this->accumulateStats($globalStats, $stats1);
        } else {
            $this->error('Scraping Scholyhub échoué : ' . $result1->errorOutput());
        }

        $this->info('ScholarshipsAds');
        $result2 = Process::path($scraperPath)->run('npm run scrape:ads');

        if ($result2->successful()) {
            $this->info('Import ScholarshipsAds en cours...');
            $stats2 = $importer->import($scraperPath . '/data/scholarshipsads-final.json');
            $this->accumulateStats($globalStats, $stats2);
        } else {
            $this->error('Scraping ScholarshipsAds échoué : ' . $result2->errorOutput());
        }
            
        

        // 4. Supprimer les bourses expirées (via le service)
        $this->info('Nettoyage de la base');
        $deleted = $importer->removeExptredScholarships();
        $duration = now()->diffInSeconds($start);

        $this->newLine();
        $this->info("Synchronisation terminée en {$duration} secondes.");
        $this->info('RÉSULTATS GLOBAUX');
        $this->line("Bourses lues: {$globalStats['total']}");
        $this->line("Nouvelles: {$globalStats['created']}");
        $this->line("Mises à jour: {$globalStats['updated']}");
        $this->line("Expirées ignoré: {$globalStats['expired']}");
        $this->line("Doublons évités: {$globalStats['duplicates']}");
        $this->line("Supprimées (DB): {$deleted}");
        $this->line("Erreurs: {$globalStats['errors']}");
        $this->newLine();

        if ($globalStats['expired'] > 0 || $deleted > 0 || $globalStats['duplicates'] > 0) {
            $this->warn('Les bourses expirées et les doublons ont été automatiquement filtrés.');
        }

        return 0;


        // 5. Récupérer les stats de l'import
        $stats = $this->getImportStats();

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

        if ($stats['expired'] > 0 || $deleted > 0) {
            $this->warn('Les bourses expirées ont été automatiquement nettoyées.');
        }

        return 0;
    }

    private function accumulateStats(array &$global, array $source): void
    {
        $global['total'] += $source['total'] ?? 0;
        $global['created'] += $source['created'] ?? 0;
        $global['updated'] += $source['updated'] ?? 0;
        $global['expired'] += $source['expired'] ?? 0;
        $global['duplicates'] += $source['duplicates'] ?? 0;
        $global['errors'] += $source['errors'] ?? 0;
    }

}
