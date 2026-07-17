<?php

namespace Database\Seeders;

use App\Models\CvTemplate;
use Illuminate\Database\Seeder;

class CleanCvTemplatesSeeder extends Seeder
{
    public function run(): void
    {
        $obsoleteSlugs = ['creative', 'professional', 'elegant', 'tech', 'academic', 'colorful', 'executive'];

        CvTemplate::whereIn('slug', $obsoleteSlugs)->update(['is_active' => false]);

        $this->command->info('✅ Templates obsolètes désactivés.');
    }
}