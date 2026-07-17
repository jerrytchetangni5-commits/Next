<?php

namespace Database\Seeders;

use App\Models\Scholarship;
use Illuminate\Database\Seeder;

class ScholarshipSeeder extends Seeder
{
    public function run(): void
    {
        $scholarships = [
            [
                'title' => 'Mastercard Foundation Scholars Program',
                'country' => 'Canada',
                'university' => 'University of Toronto',
                'domain' => 'Informatique',
                'level' => 'Master',
                'deadline' => '2027-01-15',
                'description' => 'Bourse complète pour étudiants africains en informatique.',
                'funding_type' => 'full',
                'amount' => 25000,
                'currency' => 'CAD',
                'benefits' => 'Frais de scolarité, logement, allocation mensuelle, billets d\'avion',
                'days_remaining' => 120,
                'requirements' => 'Être citoyen africain, excellent dossier académique, engagement communautaire',
                'min_average' => 15.00,
                'required_english_level' => 'B2',
                'languages' => ['Anglais', 'Français'],
                'image' => null,
                'link' => 'https://mastercardfdn.org/scholars',
                'source' => 'Manual'
            ],
            [
                'title' => 'Bourse Eiffel - France',
                'country' => 'France',
                'university' => 'Université Paris-Saclay',
                'domain' => 'Informatique',
                'level' => 'Master',
                'deadline' => '2026-08-30',
                'description' => 'Bourse d\'excellence pour étudiants internationaux en Master.',
                'funding_type' => 'full',
                'amount' => 14400,
                'currency' => 'EUR',
                'benefits' => 'Prise en charge des frais de scolarité + 1 200 €/mois',
                'days_remaining' => 30,
                'requirements' => 'Moins de 30 ans, excellent dossier académique, projet de recherche',
                'min_average' => 16.00,
                'required_english_level' => 'B2',
                'languages' => ['Français', 'Anglais'],
                'image' => null,
                'link' => 'https://www.campusfrance.org/fr/bourse-eiffel',
                'source' => 'Manual'
            ],
            [
                'title' => 'Chevening Scholarship - UK',
                'country' => 'Royaume-Uni',
                'university' => 'University of Oxford',
                'domain' => 'Sciences Sociales',
                'level' => 'Master',
                'deadline' => '2026-06-15',
                'description' => 'Bourse du gouvernement britannique pour les futurs leaders.',
                'funding_type' => 'full',
                'amount' => 0,
                'currency' => 'GBP',
                'benefits' => 'Frais de scolarité, billet d\'avion, allocation de subsistance',
                'days_remaining' => 10,
                'requirements' => '3 ans d\'expérience professionnelle, leadership avéré',
                'min_average' => 14.00,
                'required_english_level' => 'C1',
                'languages' => ['Anglais'],
                'image' => null,
                'link' => 'https://www.chevening.org',
                'source' => 'Manual'
            ],
            [
                'title' => 'DAAD Scholarship - Germany',
                'country' => 'Allemagne',
                'university' => 'Technische Universität Berlin',
                'domain' => 'Ingénierie',
                'level' => 'Master',
                'deadline' => '2026-09-15',
                'description' => 'Bourse pour étudiants en ingénierie en Allemagne.',
                'funding_type' => 'partial',
                'amount' => 850,
                'currency' => 'EUR',
                'benefits' => 'Allocation mensuelle de 850 € + assurance maladie',
                'days_remaining' => 50,
                'requirements' => '2 ans d\'expérience professionnelle, diplôme en ingénierie',
                'min_average' => 14.00,
                'required_english_level' => 'B2',
                'languages' => ['Anglais', 'Allemand'],
                'image' => null,
                'link' => 'https://www.daad.org',
                'source' => 'Manual'
            ],
            [
                'title' => 'Mandela Rhodes Scholarship',
                'country' => 'Afrique du Sud',
                'university' => 'University of Cape Town',
                'domain' => 'Santé Publique',
                'level' => 'Master',
                'deadline' => '2026-10-10',
                'description' => 'Bourse pour jeunes africains ayant un potentiel de leadership.',
                'funding_type' => 'full',
                'amount' => 0,
                'currency' => 'ZAR',
                'benefits' => 'Frais universitaires, mentorat, logement, allocation de subsistance',
                'days_remaining' => 70,
                'requirements' => 'Entre 19 et 29 ans, engagement citoyen, leadership',
                'min_average' => 15.00,
                'required_english_level' => 'B2',
                'languages' => ['Anglais'],
                'image' => null,
                'link' => 'https://www.mandelarhodes.org',
                'source' => 'Manual'
            ],
            [
                'title' => 'Bourse Afrique-France - Droit',
                'country' => 'France',
                'university' => 'Université Paris 1 Panthéon-Sorbonne',
                'domain' => 'Droit',
                'level' => 'Licence',
                'deadline' => '2026-07-01',
                'description' => 'Bourse dédiée aux étudiants africains en Droit.',
                'funding_type' => 'partial',
                'amount' => 5000,
                'currency' => 'EUR',
                'benefits' => 'Prise en charge à 80% des frais de scolarité',
                'days_remaining' => 20,
                'requirements' => 'Moyenne supérieure à 14/20',
                'min_average' => 14.00,
                'required_english_level' => 'B1',
                'languages' => ['Français', 'Anglais'],
                'image' => null,
                'link' => 'https://exemple.com',
                'source' => 'Manual'
            ],
            [
                'title' => 'Erasmus Mundus - Europe',
                'country' => 'Espagne',
                'university' => 'Universitat Pompeu Fabra',
                'domain' => 'Data Science',
                'level' => 'Doctorat',
                'deadline' => '2026-11-01',
                'description' => 'Programme de doctorat en Science des Données avec mobilité en Europe.',
                'funding_type' => 'full',
                'amount' => 18000,
                'currency' => 'EUR',
                'benefits' => 'Bourse complète + mobilité internationale',
                'days_remaining' => 90,
                'requirements' => 'Master en informatique, mathématiques ou statistiques',
                'min_average' => 16.00,
                'required_english_level' => 'C1',
                'languages' => ['Anglais', 'Espagnol'],
                'image' => null,
                'link' => 'https://erasmusmundus.eu',
                'source' => 'Manual'
            ],
            [
                'title' => 'Bourse Orange Afrique - Tech',
                'country' => 'Sénégal',
                'university' => 'Université Cheikh Anta Diop',
                'domain' => 'Informatique',
                'level' => 'Licence',
                'deadline' => '2026-12-01',
                'description' => 'Bourse pour talents numériques en Afrique.',
                'funding_type' => 'partial',
                'amount' => 3000,
                'currency' => 'EUR',
                'benefits' => 'Prise en charge des frais de scolarité + mentorat',
                'days_remaining' => 150,
                'requirements' => 'Projet innovant en technologie',
                'min_average' => 13.00,
                'required_english_level' => 'B1',
                'languages' => ['Français', 'Anglais'],
                'image' => null,
                'link' => 'https://orange.com',
                'source' => 'Manual'
            ],
        ];

        foreach ($scholarships as $scholarship) {
            Scholarship::create($scholarship);
        }

        $this->command->info('✅ ' . count($scholarships) . ' bourses ajoutées avec succès !');
    }
}