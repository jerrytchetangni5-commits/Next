<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CvTemplate;

class CvTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [

            [
                'name' => 'Vintage',
                'slug' => 'vintage',
                'blade_view' => 'cv.templates.vintage',
                'preview_image' => 'vintage.png',
                'description' => 'Style rétro avec tons terre et élégance intemporelle',
                'is_active' => true,
            ],

            [
                'name' => 'Premium',
                'slug' => 'premium',
                'blade_view' => 'cv.templates.premium',
                'preview_image' => 'premium.png',
                'description' => 'Design moderne et épuré, idéal pour les profils professionnels',
                'is_active' => true,
            ],

            [
                'name' => 'Luxury',
                'slug' => 'luxury',
                'blade_view' => 'cv.templates.luxury',
                'preview_image' => 'luxury.png',
                'description' => 'Design sombre et élégant avec accents dorés, idéal pour les profils prestigieux',
                'is_active' => true,
            ],            

            [
                'name' => 'Terracotta',
                'slug' => 'terracotta',
                'blade_view' => 'cv.templates.terracotta',
                'preview_image' => 'terracotta.png',
                'description' => 'Design chaleureux aux tons terre cuite et élégance intemporelle',
                'is_active' => true,
            ],

            [
                'name' => 'Minimal',
                'slug' => 'minimal',
                'blade_view' => 'cv.templates.minimal',
                'preview_image' => 'minimal.png',
                'description' => 'Design épuré et minimaliste en tons gris',
                'is_active' => true,
            ],

            [
                'name' => 'Navy Blue',
                'slug' => 'navy-blue',
                'blade_view' => 'cv.templates.navy-blue',
                'preview_image' => 'navy-blue.png',
                'description' => 'Design moderne avec dominante bleu marine et accents élégants',
                'is_active' => true,
            ],

            [
                'name' => 'Forest Green',
                'slug' => 'forest-green',
                'blade_view' => 'cv.templates.forest-green',
                'preview_image' => 'forest-green.png',
                'description' => 'Design moderne et naturel aux tons verts, élégant et professionnel',
                'is_active' => true,
            ],

            [
                'name' => 'Bleu Marine',
                'slug' => 'bleu-marine',
                'blade_view' => 'cv.templates.bleu-marine',
                'preview_image' => 'bleu-marine.png',
                'description' => 'Design élégant avec des tons bleu marine et une présentation moderne',
                'is_active' => true,
            ],

            [
                'name' => 'Bordeaux',
                'slug' => 'bordeaux',
                'blade_view' => 'cv.templates.bordeaux',
                'preview_image' => 'bordeaux.png',
                'description' => 'Design élégant avec des tons bordeaux, chaleureux et raffiné',
                'is_active' => true,
            ],
        ];

        foreach ($templates as $template) {
            CvTemplate::updateOrCreate([
                'slug' => $template['slug']
            ], $template);
        }
    }
}