<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/reset-password/{token}', function ($token) {
    return redirect('http://localhost:4200/reset-password/' . $token);
})->name('password.reset');



use App\Models\CvTemplate;
use Barryvdh\DomPDF\Facade\Pdf;

Route::get('/test-pdf', function () {
    $template = CvTemplate::findOrFail(1);
    
    $data = [
        "personal_info" => [
            "full_name" => "Jerry Seton",
            "title" => "Développeur Web Full Stack",
            "email" => "jerry@example.com",
            "phone" => "+229 97 00 00 00",
            "address" => "Cotonou, Bénin"
        ],
        "objective" => "Développeur passionné avec 3 ans d'expérience en Laravel et Angular.",
        "experiences" => [
            [
                "position" => "Développeur Full Stack",
                "company" => "TechCorp",
                "start_date" => "2023-01",
                "end_date" => "2025-12",
                "description" => "Développement d'applications web."
            ]
        ],
        "education" => [
            [
                "degree" => "Licence en Informatique",
                "institution" => "Université d'Abomey-Calavi",
                "start_date" => "2020",
                "end_date" => "2023"
            ]
        ],
        "skills" => [
            ["name" => "Laravel", "level" => "Expert"],
            ["name" => "Angular", "level" => "Avancé"]
        ],
        "languages" => [
            ["name" => "Français", "level" => "Natif"],
            ["name" => "Anglais", "level" => "B2"]
        ]
    ];
    
    $html = view($template->blade_view, [
        'data' => $data,
        'language' => 'fr'
    ])->render();
    
    $pdf = Pdf::loadHTML($html);
    return $pdf->stream('CV_Test.pdf'); // ← stream() affiche dans le navigateur
});