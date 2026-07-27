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
                'is_active' => true,
            ],

            [
                'name' => 'Premium',
                'slug' => 'premium',
                'blade_view' => 'cv.templates.premium',
                'preview_image' => 'premium.png',
                'is_active' => true,
            ],

            [
                'name' => 'Luxury',
                'slug' => 'luxury',
                'blade_view' => 'cv.templates.luxury',
                'preview_image' => 'luxury.png',
                'is_active' => true,
            ],            

            [
                'name' => 'Terracotta',
                'slug' => 'terracotta',
                'blade_view' => 'cv.templates.terracotta',
                'preview_image' => 'terracotta.png',
                'is_active' => true,
            ],

            [
                'name' => 'Minimal',
                'slug' => 'minimal',
                'blade_view' => 'cv.templates.minimal',
                'preview_image' => 'minimal.png',
                'is_active' => true,
            ],

            [
                'name' => 'Navy Blue',
                'slug' => 'navy-blue',
                'blade_view' => 'cv.templates.navy-blue',
                'preview_image' => 'navy-blue.png',
                'is_active' => true,
            ],

            [
                'name' => 'Forest Green',
                'slug' => 'forest-green',
                'blade_view' => 'cv.templates.forest-green',
                'preview_image' => 'forest-green.png',
                'is_active' => true,
            ],

            [
                'name' => 'Bleu Marine',
                'slug' => 'bleu-marine',
                'blade_view' => 'cv.templates.bleu-marine',
                'preview_image' => 'bleu-marine.png',
                'is_active' => true,
            ],

            [
                'name' => 'Bordeaux',
                'slug' => 'bordeaux',
                'blade_view' => 'cv.templates.bordeaux',
                'preview_image' => 'bordeaux.png',
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