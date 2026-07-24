<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

class SyncScholarships extends Command
{

    protected $signature = 'scholarships:sync';
    protected $description = 'Scrape, enrich, import et nettoie les bourses (automatisation complète)';

    public function handle()
    {
        $this->info('Synchronisation complète des bourses');
        $start = now();

        $scraperPath = base_path('../scraper');
        $jsonPath = $scraperPath . '/storage/scholarships.json';

        // 1. Scraper les cartes
        $this->info('Étape 1/4 : Scraping des cartes...');
        $result = Process::path($scraperPath)->run('npm run links');

        if (!$result->successful()) {
            $this->error('Scraping échoué : ' . $result->errorOutput());
            return 1;
        }
        $this->line($result->output());

        // 2. Ajout les données
        $this->info('Étape 2/4 : Ajout...');
        $result = Process::path($scraperPath)->run('npm run start');

        if (!$result->successful()) {
            $this->error('Enrichissement échoué : ' . $result->errorOutput());
            return 1;
        }
        $this->line($result->output());

        // 3. Importer les bourses (commande Artisan)
        $this->info('Étape 3/4 : Import dans la base...');
        $exitCode = $this->call('scholarships:import');

        if ($exitCode !== 0) {
            $this->error('Import échoué.');
            return 1;
        }

        // 4. Supprimer les bourses expirées (via le service)
        $this->info('Étape 4/4 : Nettoyage des bourses expirées...');
        $deleted = app(\App\Services\ScholarshipImporter::class)->removeExpiredScholarships();

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

    private function getImportStats(): array
    {
        // Récupère les stats depuis le cache ou retourne des valeurs par défaut
        return [
            'total' => 0,
            'created' => 0,
            'updated' => 0,
            'expired' => 0,
            'errors' => 0,
        ];
    }

}
