<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\Scholarship;
use App\Services\ScholarshipImporter;

class ImportScholarships extends Command
{
    protected $signature = 'scholarships:import {path?}';
    protected $description = 'Importe les bourses depuis le JSON du scraper';

    public function handle(ScholarshipImporter $importer)
    {
        $path = $this->argument('path') ?? base_path('../scraper/storage/scholarships.json');
        $this->info('Import des bourses vers next');

        try{
            $stats = $importer->import($path);

            $this->newLine();
            $this->line('RESULTATS');
            $this->line(" Lues :{$stats['total']}");
            $this->line(" Nouvelles :{$stats['created']}");
            $this->line(" Mise à jours : :{$stats['updated']}");
            $this->line(" Expirées :{$stats['expired']}");
            $this->line(" Erreurs :{$stats['errors']}");
            $this->newLine(); 

            if($stats['expired'] > 0){
                $this->warn('Les bourses expirées sont ignorées.');
            }

            $this->info('La base next a été synchronisée');
        } catch (\Exception $e){
            $this->error('Erreur: ' . $e->getMessage());
            return 1;
        }

        return 0;
               
    }
}
