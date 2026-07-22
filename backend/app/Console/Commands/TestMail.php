<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestMail extends Command
{
    protected $signature = 'mail:test';
    protected $description = 'Envoie un email de test pour vérifier la config SMTP';

    public function handle()
    {
        $this->info("Envoi d'un email de test...");

        try {
            Mail::raw('Ceci est un email de test depuis Next.', function ($message) {
                $message->to('cejocal440@suahi.com')
                        ->subject('Test SMTP - Next');
            });

            $this->info('Email envoyé avec succès !');
            return 0;

        } catch (\Exception $e) {
            $this->error('Erreur : ' . $e->getMessage());
            return 1;
        }
    }
}