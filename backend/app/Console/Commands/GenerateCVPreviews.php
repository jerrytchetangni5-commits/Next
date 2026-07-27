<?php

namespace App\Console\Commands;

use App\Models\CvTemplate;
use Illuminate\Console\Command;
use Spatie\Browsershot\Browsershot;

class GenerateCVPreviews extends Command
{
    protected $signature = 'cv:generate-previews';
    protected $description = 'Génère des captures d\'écran des templates CV pour la prévisualisation';
    public function handle()
    {
        $testData = [
            'personal_info' => [
                'first_name' => 'Jinx',
                'last_name' => 'KOFFI',
                'photo' => null,
                'birth_date' => '2007-05-15',
                'gender' => 'Femme',
                'email' => 'jinxkoffi@mail.com',
                'phone' => '+229 0192566778',
                'address' => 'Cotonou, Bénin',
                'title' => null,
            ],
            'summary' => 'Développeur web passionné par les technologies innovantes avec 3 ans d\'expérience.',
            'educations' => [
                [
                    'degree' => 'Licence Informatique',
                    'school' => 'ENEAM',
                    'city' => 'Cotonou',
                    'start_date' => '2024-09',
                    'end_date' => '2027-06',
                    'description' => 'Spécialisation informatique de gestion'
                ]
            ],
            'experiences' => [
                [
                    'position' => 'Développeur Laravel',
                    'company' => 'MAMERI',
                    'city' => 'Calavi',
                    'start_date' => '2026-01',
                    'end_date' => '2027-06',
                    'description' => 'Création d\'API REST et maintenance d\'applications web.'
                ]
            ],
            'skills' => [
                ['name' => 'Laravel'],
                ['name' => 'PHP'],
                ['name' => 'MySQL'],
                ['name' => 'Angular'],
                ['name' => 'IA']
            ],
            'languages' => [
                ['language_name' => 'Français', 'language_level' => 'Natif'],
                ['language_name' => 'Anglais', 'language_level' => 'C2']
            ],
            'interests' => [
                ['name' => 'Lecture'],
                ['name' => 'Sport']
            ],
        ];
        $templates = CvTemplate::where('is_active', true)->get();
        $destination = public_path('images/cv-templates');
        if (!is_dir($destination)) {
            mkdir($destination, 0755, true);
            $this->info("Dossier créé : {$destination}");
        }
        foreach ($templates as $template) {
            $this->info("Génération de l'aperçu pour : {$template->name} ({$template->slug})");
            try {
                $html = view($template->blade_view, ['data' => $testData])->render();
                $imageData = Browsershot::html($html)
                    ->windowSize(800, 1000)
                    ->fullPage()
                    ->screenshot();
                $filename = $template->slug . '.png';
                $filepath = $destination . '/' . $filename;
                file_put_contents($filepath, $imageData);
                $template->update(['preview_image' => $filename]);
                $this->info("Succès : {$filename} (" . round(filesize($filepath) / 1024, 2) . " KB)");

            } catch (\Exception $e) {
                $this->error("Erreur pour {$template->slug} : " . $e->getMessage());
            }
        }
        $this->info('Toutes les prévisualisations ont été générées !');
        $this->info("Dossier : " . $destination);
    }
}